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
 * Quote model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Quote extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_quote';
    const CACHE_TAG = 'mfb_myflyingbox_quote';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_quote';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'quote';

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
        $this->_init('mfb_myflyingbox/quote');
    }

    /**
     * before save quote
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Quote
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
     * save quote relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Quote
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
     * @return Mfb_Myflyingbox_Model_Offer_Collection
     * @author Ultimate Module Creator
     */
    public function getSelectedOffersCollection()
    {
        if (!$this->hasData('_offer_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('mfb_myflyingbox/offer_collection')
                        ->addFieldToFilter('quote_id', $this->getId());
                $this->setData('_offer_collection', $collection);
            }
        }
        return $this->getData('_offer_collection');
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
    
}
