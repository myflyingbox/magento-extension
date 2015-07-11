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
 * Dimension model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Dimension extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_dimension';
    const CACHE_TAG = 'mfb_myflyingbox_dimension';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_dimension';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'dimension';

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
        $this->_init('mfb_myflyingbox/dimension');
    }

    /**
     * before save dimension
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Dimension
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
     * save dimension relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Dimension
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
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
