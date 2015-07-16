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
      return true;
    }
    
}
