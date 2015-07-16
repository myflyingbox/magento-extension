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
 * Offer model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Offer extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_offer';
    const CACHE_TAG = 'mfb_myflyingbox_offer';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_offer';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'offer';

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
        $this->_init('mfb_myflyingbox/offer');
    }

    /**
     * before save offer
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Offer
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
     * save offer relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Offer
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
     * @return null|Mfb_Myflyingbox_Model_Quote
     * @author Ultimate Module Creator
     */
    public function getParentQuote()
    {
        if (!$this->hasData('_parent_quote')) {
            if (!$this->getQuoteId()) {
                return null;
            } else {
                $quote = Mage::getModel('mfb_myflyingbox/quote')
                    ->load($this->getQuoteId());
                if ($quote->getId()) {
                    $this->setData('_parent_quote', $quote);
                } else {
                    $this->setData('_parent_quote', null);
                }
            }
        }
        return $this->getData('_parent_quote');
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
    
    public function formattedPrice() {
      return ($this->getBasePriceInCents() / 100).' '.$this->getCurrency();
    }

    
    
}
