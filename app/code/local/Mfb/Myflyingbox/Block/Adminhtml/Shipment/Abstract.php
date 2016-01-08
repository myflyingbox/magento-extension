<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_Abstract extends Mage_Adminhtml_Block_Widget
{

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
