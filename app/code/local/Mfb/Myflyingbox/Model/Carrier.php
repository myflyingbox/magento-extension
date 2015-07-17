<?php
class Mfb_Myflyingbox_Model_Carrier
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    protected $_code = 'mfb_myflyingbox';
    
    protected $_result = null; // Used to store the list of quotes
    
    
    public function __construct()
    {
      Mage::log('MFB: Initializing MFB Carrier');
      parent::__construct();

      Mage::log('MFB: Including MFB API lib');
      require_once(Mage::getBaseDir('lib') . '/Lce/bootstrap.php');
      
      Mage::log('MFB: Testing presence of php-curl');
      // Check is php-curl is available
      if(!extension_loaded('curl')) print_r("php-curl does not seem te be installed on your system. Please contact your hosting provider. This extension is required for the module to work properly.");
      
      // API Environment
      $env = $this->getConfigData('api_env');
      if ($env != 'staging' && $env != 'production') $env = 'staging';
      
      Mage::log('MFB: Instanciating the MFB API');
      // Initializing API lib
      $api = Lce\Lce::configure($this->getConfigData('api_login'), $this->getConfigData('api_password'), $env);
      $api->application = "magento-mfb";
      $api->application_version = Mage::getConfig()->getNode()->modules->Mfb_Myflyingbox->version . " (Magento ". Mage::getVersion() .")";

    }


    public function collectRates(
        Mage_Shipping_Model_Rate_Request $request
    )
    {
        Mage::log('MFB: #collectRates has been called');

        Mage::log('MFB: initializing result variable');
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
        $items = Mage::getSingleton('checkout/session')->getQuote()->getAllItems();
        $weight = 0;
        foreach($items as $item) {
            $weight += ($item->getWeight() * $item->getQty()) ;
        }
        
        // We do not proceed further if there is not enough data
        if (!$recipient_city || !$recipient_country || !$recipient_postcode || $weight == 0)
          return $this->_result;

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
            array('length' => 10, 'height' => 10, 'width' => 10, 'weight' => 1.2)
          )
        );
        
        Mage::log('MFB: sending quote request to API');
        $api_quote = Lce\Resource\Quote::request($params);
        
        Mage::log('MFB: looping through API offers');
        Mage::log('Number of offers:'.count($api_quote->offers));
        foreach($api_quote->offers as $k => $api_offer) {

          $offer_uuid = $api_offer->id;
          $offer_product_code = $api_offer->product->code;
          $offer_product_name = $api_offer->product->name;
          $offer_base_price_in_cents = $api_offer->price->amount_in_cents;
          $offer_currency = $api_offer->total_price->currency;
          
          $rate = Mage::getModel('shipping/rate_result_method');
          $rate->setCarrier($this->_code);
          $rate->setCarrierTitle("MYFLYINGBOX");
          $rate->setMethod($offer_product_code);
          $rate->setMethodTitle($offer_product_name);
          $rate->setCost(0);
          $rate->setPrice($offer_base_price_in_cents/100);
          
          Mage::log($rate);
          
          $this->_result->append($rate);
          
        }

        return $this->_result;
        
    }

    public function getAllowedMethods()
    {
        return array(
            'standard' => 'Standard'
        );
    }
    
    protected function _getDefaultShippingRate(){
        $rate = Mage::getModel('shipping/rate_result_method');
        /* @var $rate Mage_Shipping_Model_Rate_Result_Method */

        $rate->setCarrier($this->_code);
        /**
         * getConfigData(config_key) returns the configuration value for the
         * carriers/[carrier_code]/[config_key]
         */
        $rate->setCarrierTitle($this->getConfigData('title'));

        $rate->setMethod('standard');
        $rate->setMethodTitle('Standard');

        $rate->setPrice(4.99);
        $rate->setCost(0);

        return $rate;
    }
    
}
?>
