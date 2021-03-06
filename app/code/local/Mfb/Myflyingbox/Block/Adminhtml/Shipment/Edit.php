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
 * Shipment admin edit form
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Shipment_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
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
        parent::__construct();
        $this->_blockGroup = 'mfb_myflyingbox';
        $this->_controller = 'adminhtml_shipment';
        $this->_updateButton(
            'save',
            'label',
            Mage::helper('mfb_myflyingbox')->__('Save Shipment')
        );
        $this->_updateButton(
            'delete',
            'label',
            Mage::helper('mfb_myflyingbox')->__('Delete Shipment')
        );
        $this->_addButton(
            'saveandcontinue',
            array(
                'label'   => Mage::helper('mfb_myflyingbox')->__('Save And Continue Edit'),
                'onclick' => 'saveAndContinueEdit()',
                'class'   => 'save',
            ),
            -100
        );
        $this->_formScripts[] = "
            function saveAndContinueEdit() {
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * get the edit form header
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getHeaderText()
    {
        if (Mage::registry('current_shipment') && Mage::registry('current_shipment')->getId()) {
            return Mage::helper('mfb_myflyingbox')->__(
                "Edit Shipment '%s'",
                $this->escapeHtml(Mage::registry('current_shipment')->getShipperName())
            );
        } else {
            return Mage::helper('mfb_myflyingbox')->__('Add Shipment');
        }
    }
}
