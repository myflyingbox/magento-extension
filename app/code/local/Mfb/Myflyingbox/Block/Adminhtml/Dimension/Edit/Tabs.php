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
 * Dimension admin edit tabs
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Dimension_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Initialize Tabs
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('dimension_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('mfb_myflyingbox')->__('Dimension'));
    }

    /**
     * before render html
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Edit_Tabs
     * @author Ultimate Module Creator
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'form_dimension',
            array(
                'label'   => Mage::helper('mfb_myflyingbox')->__('Dimension'),
                'title'   => Mage::helper('mfb_myflyingbox')->__('Dimension'),
                'content' => $this->getLayout()->createBlock(
                    'mfb_myflyingbox/adminhtml_dimension_edit_tab_form'
                )
                ->toHtml(),
            )
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve dimension entity
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Dimension
     * @author Ultimate Module Creator
     */
    public function getDimension()
    {
        return Mage::registry('current_dimension');
    }
}
