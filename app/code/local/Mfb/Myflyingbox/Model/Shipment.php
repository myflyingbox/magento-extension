<?php
/**
 * Mfb_Myflyingbox extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Mfb
 * @package        Mfb_Myflyingbox
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Shipment model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Shipment extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_shipment';
    const CACHE_TAG = 'mfb_myflyingbox_shipment';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_shipment';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'shipment';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('mfb_myflyingbox/shipment');
    }

    /**
     * before save shipment
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Shipment
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save shipment relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Shipment
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Quote_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedQuotesCollection()
    {
        if (!$this->hasData('_quote_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('mfb_myflyingbox/quote_collection')
                        ->addFieldToFilter('shipment_id', $this->getId());
                $this->setData('_quote_collection', $collection);
            }
        }
        return $this->getData('_quote_collection');
    }
    
    public function getLatestQuote() {
      $api_quote_uuid = $this->getApiQuoteUuid();
      if ( empty($api_quote_uuid) ) {
        return false;
      }
      
      $res = Mage::getModel('mfb_myflyingbox/quote')->getCollection()
                  ->addFieldToFilter("shipment_id", $this->getId())
                  ->addFieldToFilter("api_quote_uuid", $api_quote_uuid)
                  ->setOrder("created_at", "DESC")
                  ->setPageSize(1)
                  ->loadData()
                  ->getData();
      
      if ( empty($res) ) {
        return false;
      }
      return Mage::getModel('mfb_myflyingbox/quote')->load($res[0]['entity_id']);
    }

    public function getSelectedOffer() {
      $api_quote_uuid = $this->getApiQuoteUuid();
      $api_offer_uuid = $this->getApiOfferUuid();
      if ( empty($api_quote_uuid) || empty($api_offer_uuid) ) {
        return false;
      }
      
      $quote = $this->getLatestQuote();
      
      $res = Mage::getModel('mfb_myflyingbox/offer')->getCollection()
                  ->addFieldToFilter("api_offer_uuid", $api_offer_uuid)
                  ->addFieldToFilter("quote_id", $quote->getId())
                  ->setOrder("created_at", "DESC")
                  ->setPageSize(1)
                  ->loadData()
                  ->getData();
      
      if ( empty($res) ) {
        return false;
      }
      return Mage::getModel('mfb_myflyingbox/offer')->load($res[0]['entity_id']);
    }


    /**
     * Retrieve  collection
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Parcel_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedParcelsCollection()
    {
        if (!$this->hasData('_parcel_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('mfb_myflyingbox/parcel_collection')
                        ->addFieldToFilter('shipment_id', $this->getId());
                $this->setData('_parcel_collection', $collection);
            }
        }
        return $this->getData('_parcel_collection');
    }


    public function getParcels() {
      return $this->getSelectedParcelsCollection();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
    public function getParentOrder()
    {
        if (!$this->hasData('_parent_order')) {
            if (!$this->getOrderId()) {
                return null;
            } else {
                $order = Mage::getModel('sales/order')
                    ->load($this->getOrderId());
                if ($order->getId()) {
                    $this->setData('_parent_order', $order);
                } else {
                    $this->setData('_parent_order', null);
                }
            }
        }
        return $this->getData('_parent_order');
    }
    
    public function populateFromOrder( $order ) {
      $data = array();
      $carrier = Mage::getModel('mfb_myflyingbox/carrier');
      
      $data['order_id'] = $order->getId();
      
      $data['shipper_name'] = $carrier->getConfigData('shipper_name');
      $data['shipper_company'] = $carrier->getConfigData('shipper_company');
      $data['shipper_street'] = $carrier->getConfigData('shipper_street');
      $data['shipper_city'] = $carrier->getConfigData('shipper_city');
      $data['shipper_state'] = $carrier->getConfigData('shipper_state');
      $data['shipper_postal_code'] = $carrier->getConfigData('shipper_postcode');
      $data['shipper_country'] = $carrier->getConfigData('shipper_country');
      $data['shipper_phone'] = $carrier->getConfigData('shipper_phone');
      $data['shipper_email'] = $carrier->getConfigData('shipper_email');
      
      $recipient = $order->getShippingAddress();
      
      $data['recipient_name'] = $recipient->getFirstname() . ' ' . $recipient->getLastname();
      $data['recipient_company'] = $recipient->getCompany();
      $data['recipient_street'] = implode('\n', $recipient->getStreet());
      $data['recipient_city'] = $recipient->getCity();
      $company = $recipient->getCompany();
      if ( in_array( $company, array('US','CA') )) {
        $data['recipient_state'] = $recipient->getRegion();
      }
      $data['recipient_postal_code'] = $recipient->getPostcode();
      $data['recipient_country'] = $recipient->getCountry();
      $data['recipient_phone'] = $recipient->getTelephone();
      $data['recipient_email'] = $recipient->getEmail();
      
      $this->setData($data);
      
      return $this; 
    }
    
    
    // TODO: Customize when proper status support
    public function canEdit() {
      $api_order_uuid = $this->getApiOrderUuid();
      if ( !empty($api_order_uuid) ) {
        return false;
      } else {
        return true;
      }
    }

  public function getNewQuote() {
      
      // Not proceeding unless the shipment is in draft state
      if (!$this->canEdit()) return false;
      
      // If we requested a new quote, it means we have outdated data.
      // We reset the data whatever happens next.
      $this->setApiOfferUuid('');
      $this->setApiQuoteUuid('');

      $this->save();
      
      $parcels = array();
      foreach( $this->getParcels() as $parcel ) {
        $parcels[] = array(
          'length' => $parcel->getLength(),
          'width'  => $parcel->getWidth(),
          'height'  => $parcel->getHeight(),
          'weight'  => $parcel->getWeight(),
          'insured_value' =>  $parcel->getInsurableValue()/100,
          "insured_currency" => $parcel->getCurrencyCode()
        );
      }

      // No parcel? We stop now.
      if ( empty($parcels) ) {
        $this->save(); // Saving the emptied quote and offer uuids.
        return false;
      }
      
      $params = array(
        'shipper' => array(
          'city'         => $this->getShipperCity(),
          'postal_code'  => $this->getShipperPostalCode(),
          'country'      => $this->getShipperCountry(),
        ),
        'recipient' => array(
          'city'         => $this->getRecipientCity(),
          'postal_code'  => $this->getRecipientPostalCode(),
          'country'      => $this->getRecipientCountry(),
          'is_a_company' => false
        ),
        'parcels' => $parcels
      );
      $api = Mage::helper('mfb_myflyingbox')->getApiInstance();
      $api_quote = Lce\Resource\Quote::request($params);

      $quote = Mage::getModel('mfb_myflyingbox/quote');

      $quote_data = array(
        'api_quote_uuid' => $api_quote->id,
        'shipment_id' => $this->getId()
      );
      
      $quote->addData($quote_data)->save();

      $selected_offer = null;

      if ($quote->getId() > 0) {
        // Now we create the offers

        foreach($api_quote->offers as $k => $api_offer) {

            Mage::log("getNewQuote from API: " ,null,"mfb_myflyingbox.log");
            //Mage::log($api_offer ,null,"mfb_myflyingbox.log");
          $offer = Mage::getModel('mfb_myflyingbox/offer');
          
          $offer_data = array(
            'quote_id' => $quote->getId(),
            'api_offer_uuid' => $api_offer->id,
            'mfb_product_code' => $api_offer->product->code,
            'mfb_product_name' => $api_offer->product->name,
            'pickup' => $api_offer->product->pick_up,
            'relay' => $api_offer->product->preset_delivery_location,
            'collection_dates' => $api_offer->collection_dates,
            'base_price_in_cents' => $api_offer->price->amount_in_cents,
            'total_price_in_cents' => $api_offer->total_price->amount_in_cents,
            'insurable' => $api_offer->{'insurable?'},
            'currency' => $api_offer->total_price->currency
          );

          if(isset($api_offer->insurance_price->amount_in_cents)){
              $offer_data['insurance_price_in_cents'] = $api_offer->insurance_price->amount_in_cents;
          }
          if ($api_offer->product->preset_delivery_location) {
            // Getting available delivery locations immediately, to make things easier later on
            $params = array(
              'street' => $this->getRecipientStreet(),
              'city' => $this->getRecipientCity()
            );
            $offer_data['delivery_locations'] = $api_offer->available_delivery_locations($params);
          }
          
          $offer->addData($offer_data)->save();

            if ($api_offer->product->preset_delivery_location) {
                $code = 'mfb_myflyingbox_'.$offer->getMfbProductCode()."_relay_";

                if (stripos($this->getParentOrder()->getShippingMethod(), $code) !==false) {
                    $selected_offer = $offer;
                }
            }else{
                if ($this->getParentOrder()->getShippingMethod() == 'mfb_myflyingbox_'.$offer->getMfbProductCode()) {
                    $selected_offer = $offer;
                }
            }

        }
        $this->setApiQuoteUuid($quote->getApiQuoteUuid());
        $this->setApiOfferUuid('');
        if ($selected_offer) $this->setApiOfferUuid($selected_offer->getApiOfferUuid());
      }

      $this->save();
      
      return $this;
  }

  public function isBooked() {
    $api_order_uuid = $this->getApiOrderUuid();
    return !empty($api_order_uuid);
  }

  public function bookOrder($booking_data) {
  
    $offer = Mage::getModel('mfb_myflyingbox/offer')->load($booking_data['offer_id']);
    
    $params = array(
      'shipper' => array(
        'company' => $this->getShipperCompany(),
        'name' => $this->getShipperName(),
        'street' => $this->getShipperStreet(),
        'city' => $this->getShipperCity(),
        'state' => $this->getShipperState(),
        'phone' => $this->getShipperPhone(),
        'email' => $this->getShipperEmail()
      ),
      'recipient' => array(
        'company' => $this->getRecipientCompany(),
        'name' => $this->getRecipientName(),
        'street' => $this->getRecipientStreet(),
        'city' => $this->getRecipientCity(),
        'state' => $this->getRecipientState(),
        'phone' => $this->getRecipientPhone(),
        'email' => $this->getRecipientEmail()
      ),
      'parcels' => array()
    );
    
    //Mage::log($booking_data);
    
    if( $offer->getPickup() == true ) {
      $params['shipper']['collection_date'] = $booking_data['collection_date'];
      $this->setCollectionDate($booking_data['collection_date']);
    }

    if( $offer->getRelay() == true ) {
      $params['recipient']['location_code'] = $booking_data['delivery_location_code'];
      $this->setRelayDeliveryCode($booking_data['delivery_location_code']);
      $this->setRelayDeliveryAddress($offer->getFormattedRelayAddress($booking_data['delivery_location_code']));
    }

    if(isset($booking_data["insurance"]) && $booking_data["insurance"]== "on"){
        if($offer->getInsurable()){
            $params['insure_shipment']= true;
        }else{
            //lever exception ?
        }
    }
    
    foreach( $this->getParcels() as $parcel ) {
      $params['parcels'][] = array('description' => $parcel->getDescription(), 'value' => $parcel->getValue()/100, 'currency' => $parcel->getCurrencyCode(), 'country_of_origin' => $parcel->getCountryOfOrigin(),
      //    'insured_value' =>  $parcel->getInsurableValue()/100,"insured_currency" => $parcel->getCurrencyCode()
      );
    }

    // Placing the order on the API
    $api = Mage::helper('mfb_myflyingbox')->getApiInstance();
    $api_order = Lce\Resource\Order::place($offer->getApiOfferUuid(), $params);
    
    // Saving the order uuid
    $this->setApiOrderUuid($api_order->id);
    $this->setBookedAt(Mage::getSingleton('core/date')->gmtDate());
    $this->setApiOfferUuid($offer->getApiOfferUuid()); // Making sure that we have the selected offer associated to the shipment, to use this information to display booking details
    $this->save();

    $i = 0;
    // Saving tracking reference
    foreach($this->getParcels() as $parcel) {
      if(!$api_order->insure_shipment){
          $parcel->setInsurableValue(0);
      }
      $parcel->setTrackingNumber($api_order->parcels[$i]->reference);
      $parcel->save();
      $i++;
    }

  }
  
  
  // After the shipment has been booked, we can register the tracking data on the standard Magento Shipment object
  public function registerTrackingData($magentoShipment){
      $offer = $this->getSelectedOffer();
      // Setting the shipment service title based on service configuration, is available
      $service = Mage::getModel('mfb_myflyingbox/service')->loadByCode($offer->getMfbProductCode());
      $service_title = $service ? $service->getCarrierDisplayName() : $offer->getMfbProductName();

      foreach($this->getParcels() as $parcel) {
          //Save tracking on magento shipment
          Mage::getModel('sales/order_shipment_track')
                     ->setShipment($magentoShipment)
                     ->setData('title', $service_title)
                     ->setData('number', $parcel->getTrackingNumber())
                     ->setData('carrier_code', "mfb_myflyingbox")
                     ->setData('description', $offer->getMfbProductCode())
                     ->setData('order_id', $this->getApiOrderUuid())
                     ->save();

      }
  }
  
  public function getParcelIndex($tracking_number){
    $api = Mage::helper('mfb_myflyingbox')->getApiInstance();
    $api_order = Lce\Resource\Order::find($this->getApiOrderUuid());
    foreach($api_order->parcels as $key => $parcel) {
      if ($parcel->reference == $tracking_number)
        return $key;
    }
    return 0;
  }
  
  public function getApiTrackingData($tracking_number = null){
    $api = Mage::helper('mfb_myflyingbox')->getApiInstance();
    $order_uuid = $this->getApiOrderUuid();
    $api_order = Lce\Resource\Order::find($order_uuid);
    $tracking = $api_order->tracking();
    
    $index = $this->getParcelIndex($tracking_number);
    return $tracking[$index]->events;
  }
}
