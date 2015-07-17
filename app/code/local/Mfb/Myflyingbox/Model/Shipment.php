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
    
      if ( empty($this->getApiQuoteUuid()) ) {
        return false;
      }
      
      $res = Mage::getModel('mfb_myflyingbox/quote')->getCollection()
                  ->addFieldToFilter("shipment_id", $this->getId())
                  ->addFieldToFilter("api_quote_uuid", $this->getApiQuoteUuid())
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
    
      if ( empty($this->getApiQuoteUuid()) || empty($this->getApiOfferUuid()) ) {
        return false;
      }
      
      $quote = $this->getLatestQuote();
      
      $res = Mage::getModel('mfb_myflyingbox/offer')->getCollection()
                  ->addFieldToFilter("api_offer_uuid", $this->getApiOfferUuid())
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
      if ( in_array( $recipient->getCompany(), ['US','CA']) ) {
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
      if ( !empty($this->getApiOrderUuid()) ) {
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
      
      $parcels = array();
      foreach( $this->getParcels() as $parcel ) {
        $parcels[] = array(
          'length' => $parcel->getLength(),
          'width'  => $parcel->getWidth(),
          'height'  => $parcel->getHeight(),
          'weight'  => $parcel->getWeight()
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
            'currency' => $api_offer->total_price->currency
          );
          
          if ($api_offer->product->preset_delivery_location) {
            // Getting available delivery locations immediately, to make things easier later on
            $params = array(
              'street' => $this->getRecipientStreet(),
              'city' => $this->getRecipientCity()
            );
            $offer_data['delivery_locations'] = $api_offer->available_delivery_locations($params);
          }
          
          $offer->addData($offer_data)->save();
          
          if ($this->getParentOrder()->getShippingMethod() == 'mfb_carrier_'.$offer->getMfbProductCode()) {
            $selected_offer = $offer;
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
    return !empty($this->getApiOrderUuid());
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
    
    Mage::log($booking_data);
    
    if( $offer->getPickup() == true ) {
      $params['shipper']['collection_date'] = $booking_data['collection_date'];
      $this->setCollectionDate($booking_data['collection_date']);
    }

    if( $offer->getRelay() == true ) {
      $params['recipient']['location_code'] = $booking_data['delivery_location_code'];
      $this->setRelayDeliveryCode($booking_data['delivery_location_code']);
      $this->setRelayDeliveryAddress($offer->getFormattedRelayAddress($booking_data['delivery_location_code']));
    }
    
    foreach( $this->getParcels() as $parcel ) {
      $params['parcels'][] = array('description' => $parcel->getDescription(), 'value' => $parcel->getValue()/100, 'currency' => $parcel->getCurrencyCode(), 'country_of_origin' => $parcel->getCountryOfOrigin());
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
    foreach($this->getParcels() as $parcel) {
      $parcel->setTrackingNumber($api_order->parcels[$i]->reference);
      $parcel->save();
    }
  }

}
