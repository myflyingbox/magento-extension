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
 * Parcel model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Parcel extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_parcel';
    const CACHE_TAG = 'mfb_myflyingbox_parcel';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_parcel';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'parcel';

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
        $this->_init('mfb_myflyingbox/parcel');
    }

    /**
     * before save parcel
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Parcel
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
     * save parcel relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Parcel
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|Mfb_Myflyingbox_Model_Shipment
     * @author Ultimate Module Creator
     */
    public function getParentShipment()
    {
        if (!$this->hasData('_parent_shipment')) {
            if (!$this->getShipmentId()) {
                return null;
            } else {
                $shipment = Mage::getModel('mfb_myflyingbox/shipment')
                    ->load($this->getShipmentId());
                if ($shipment->getId()) {
                    $this->setData('_parent_shipment', $shipment);
                } else {
                    $this->setData('_parent_shipment', null);
                }
            }
        }
        return $this->getData('_parent_shipment');
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
    
    public function getFormattedValue() {
      return $this->getValue() / 100 .' '. $this->getCurrencySymbol($this->getCurrency());      
    }
    
    public function getCurrencySymbol($currency) {
      $attr = new Mfb_Myflyingbox_Model_Parcel_Attribute_Source_Currency();
      $symbols = array(
        'EUR' => '€',
        'USD' => '$',
        'GBP' => '£'
      );
      
      return $symbols[$attr->getOptionText($currency)];
    }
    
    public function getCurrencyCode() {
      $attr = new Mfb_Myflyingbox_Model_Parcel_Attribute_Source_Currency();
      return $attr->getOptionText($this->getCurrency());
    }
    
    public function getApiTrackingData() {
      $shipment = $this->getParentShipment();
      $tracking_data = $shipment->getApiTrackingData($this->getTrackingNumber());

      return $tracking_data;
    }
    
}
