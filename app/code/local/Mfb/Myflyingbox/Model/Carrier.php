<?php
class Mfb_Myflyingbox_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'mfb_myflyingbox';

    protected $_result = null; // Used to store the list of quotes


    public function __construct()
    {
      parent::__construct();

      require_once(Mage::getBaseDir('lib') . '/Lce/bootstrap.php');

      // Check is php-curl is available
      if(!extension_loaded('curl')) print_r("php-curl does not seem te be installed on your system. Please contact your hosting provider. This extension is required for the module to work properly.");

      // API Environment
      $env = $this->getConfigData('api_env');
      if ($env != 'staging' && $env != 'production') $env = 'staging';

      // Initializing API lib
      $api = Lce\Lce::configure($this->getConfigData('api_login'), $this->getConfigData('api_password'), $env, '2');
      $api->application = "magento-mfb";
      $api->application_version = Mage::getConfig()->getNode()->modules->Mfb_Myflyingbox->version . " (Magento ". Mage::getVersion() .")";

    }

    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        $this->_result = Mage::getModel('shipping/rate_result');

        // Getting destination country
        if ($request->getDestCountryId()) {
          $recipient_country_id = $request->getDestCountryId();
          $recipient_country = Mage::getModel('directory/country')->load($recipient_country_id)->getIso2Code();
        } else {
          $recipient_country = false;
        }

        // Getting destination city
        if ($request->getDestCity()) {
          $recipient_city = $request->getDestCity();
        } else {
          $recipient_city = false;
        }

        // Getting destination postcode
        if ($request->getDestPostcode()) {
          $recipient_postcode = $request->getDestPostcode();
        } else {
          $recipient_postcode = false;
        }

        // Calculating total weight
        $quote = Mage::getSingleton('checkout/session')->getQuote();
        $items = $quote->getAllItems();
        $totals = $quote->getTotals();
        $weight = 0;
        foreach($items as $item) {
            $weight += ($item->getWeight() * $item->getQty()) ;
        }

        // We do not proceed further if there is not enough data
        if (!$recipient_city || !$recipient_country || !$recipient_postcode || $weight == 0)
          return $this->_result;

        // Now extracting the default parcel size to use
        $dimension = Mage::getModel('mfb_myflyingbox/dimension')->getForWeight($weight);

        if (!$dimension) return $this->_result; // If no fitting dimension, we just leave.

        $params = array(
          'shipper' => array(
            'city' => $this->getConfigData('shipper_city'),
            'postal_code' => $this->getConfigData('shipper_postcode'),
            'country' => $this->getConfigData('shipper_country')),
          'recipient' => array(
            'city' => $recipient_city,
            'postal_code' => $recipient_postcode,
            'country' => $recipient_country,
            'is_a_company' => false),
          'parcels' => array(
            array('length' => $dimension->getLength(), 'height' => $dimension->getHeight(), 'width' => $dimension->getWidth(), 'weight' => $weight,
                'insured_value' =>  $totals["subtotal"]->getValue(),"insured_currency" => $quote->getQuoteCurrencyCode() )
          )
        );

        Mage::log('MFB: sending quote request to API',null,"mfb_myflyingbox.log");
        //Mage::log($params ,null,"mfb_myflyingbox.log");
        $api_quote = Lce\Resource\Quote::request($params);

        Mage::log('Number of offers:'.count($api_quote->offers),null,"mfb_myflyingbox.log");
        foreach($api_quote->offers as $k => $api_offer) {
            // Getting the corresponding service
            //Mage::log($api_offer ,null,"mfb_myflyingbox.log");

            $offer_uuid = $api_offer->id;
            $offer_product_code = $api_offer->product->code;
            $offer_product_name = $api_offer->product->name;
            $offer_base_price_in_cents = $api_offer->price->amount_in_cents;
            $offer_currency = $api_offer->total_price->currency;

            $service = Mage::getModel('mfb_myflyingbox/service')->loadByCode($offer_product_code);

            // Skipping if this service is not enabled
            if (!$service || !$service->isEnabled())
                continue;

            // Checking any restriction the service
            if (isset($params['recipient']) && !$service->destinationSupported($params['recipient']['postal_code'], $params['recipient']['country']))
                continue;

            // Determining the price
            if ($service->getFlatratePricing() == true) {
                // If flatrate pricing enabled, we get the price from the static pricelist
                $rate_price = $service->flatratePriceForWeight($weight, $params['recipient']['country']);
            } else {
                // Otherwise, we take the price from the API offer, and make relevant adjustments
                $rate_price = $this->_getAdjustedPrice($offer_base_price_in_cents);
            }

            // If we do not have any price, we do not propose this service.
            if (!$rate_price) {
                continue;
            }

            if($service->getInsurance() && $quote->getBaseSubtotalWithDiscount() > $service->getInsuranceMinimumAmount() && $api_offer->{'insurable?'}){
                //var_dump($api_offer);exit;
                $rate_price +=  (float)$api_offer->insurance_price->amount_in_cents/100;
            }

            if ($service->getRelay()) {

                $compteur = 0;
                $params = array(
                    'street' => $request->getDestStreet(),
                    'city' => $recipient_city
                );
                $offer_data['delivery_locations'] = $api_offer->available_delivery_locations($params);

                //Construct one shipping method by pickup relay
                foreach($offer_data['delivery_locations'] as $delivery){

                    if($compteur>10)break;
                    //var_dump($delivery->company);
                    $rate = Mage::getModel('shipping/rate_result_method');
                    $rate->setCarrier($this->_code);
                    $rate->setCarrierTitle($service->getCarrierDisplayName());
                    $rate->setMethod($offer_product_code."_relay_".$delivery->code);
                    $title = $delivery->company." - ". $delivery->city." ". $delivery->street;
                    $rate->setMethodTitle($title);
                    $rate->setCost(0);
                    $rate->setPrice($rate_price);

                    $this->_result->append($rate);

                    $compteur++;
                    //var_dump($this->_result);exit;
                }




            }else{


                $rate = Mage::getModel('shipping/rate_result_method');
                $rate->setCarrier($this->_code);
                $rate->setCarrierTitle($service->getCarrierDisplayName());
                $rate->setMethod($offer_product_code);
                $rate->setMethodTitle($service->getDisplayName());
                $rate->setCost(0);
                $rate->setPrice($rate_price);

                $this->_result->append($rate);

            }



        }

        return $this->_result;

    }

    public function getAllowedMethods()
    {
        return array(
            'standard' => 'Standard'
        );
    }

    //~ protected function _getDefaultShippingRate(){
        //~ $rate = Mage::getModel('shipping/rate_result_method');
        //~ /* @var $rate Mage_Shipping_Model_Rate_Result_Method */
//~
        //~ $rate->setCarrier($this->_code);
        //~ /**
         //~ * getConfigData(config_key) returns the configuration value for the
         //~ * carriers/[carrier_code]/[config_key]
         //~ */
        //~ $rate->setCarrierTitle($this->getConfigData('title'));
//~
        //~ $rate->setMethod('standard');
        //~ $rate->setMethodTitle('Standard');
//~
        //~ $rate->setPrice(4.99);
        //~ $rate->setCost(0);
//~
        //~ return $rate;
    //~ }

    protected function _getAdjustedPrice($price) {

        $increment = (int)$this->getConfigData('price_rounding_increment');
        $surcharge_amount = (int)$this->getConfigData('price_surcharge_static');
        $surcharge_percent = (int)$this->getConfigData('price_surcharge_percent');

        if (is_int($surcharge_percent) && $surcharge_percent > 0) {
          $price = $price + ($price * $surcharge_percent / 100);
        }

        if (is_int($surcharge_amount) && $surcharge_amount > 0) {
          $price = $price+$surcharge_amount;
        }

        if (is_int($increment) && $increment > 0) {
          $increment = 1 / $increment;
          $price = (ceil($price * $increment) / $increment);
        }

        return  $price / 100;
    }

    public function getTrackingInfo($tracking){

        //Get the tracking shipment
        $track = Mage::getModel('sales/order_shipment_track')->getCollection()
        ->addFieldToFilter("track_number",$tracking)
        ->getFirstItem()
        ;

        //Get the service for the tracking url
        $service = Mage::getModel('mfb_myflyingbox/service')->getCollection()
        ->addFieldToFilter("code",$track->getDescription())
        ->getFirstItem()
        ;

        //Set the result
        $trackingUrl = $service->getTrackingUrl();
        $finalTrackingUrl = str_replace("TRACKING_NUMBER", $tracking, $trackingUrl);
        $trackResult = Mage::getModel("shipping/tracking_result_status");
        $trackResult->setUrl($finalTrackingUrl)
          ->setTracking($tracking)
          ->setCarrierTitle($track->getData("title"));

        return $trackResult;
    }
}
?>
