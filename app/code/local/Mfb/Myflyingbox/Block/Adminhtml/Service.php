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
 * Service admin block
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Service extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        $this->_controller         = 'adminhtml_service';
        $this->_blockGroup         = 'mfb_myflyingbox';
        parent::__construct();
        $this->_headerText         = Mage::helper('mfb_myflyingbox')->__('Service');
        $this->_updateButton('add', 'label', Mage::helper('mfb_myflyingbox')->__('Add Service'));

    }
}
