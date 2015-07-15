<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('mfb_shipment_view_tabs');
        $this->setDestElementId('mfb_shipment_view');
        $this->setTitle(Mage::helper('mfb_myflyingbox')->__('Shipment View'));
    }

    public function getShipment()
    {
        if ($this->hasShipment()) {
            return $this->getData('shipment');
        }
        if (Mage::registry('current_shipment')) {
            return Mage::registry('current_shipment');
        }
        if (Mage::registry('shipment')) {
            return Mage::registry('shipment');
        }
        Mage::throwException(Mage::helper('mfb_myflyingbox')->__('Cannot get shipment instance'));
    }



}
