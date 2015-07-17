<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Service extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        return parent::_prepareLayout();
    }

    public function getShipment()
    {
        return Mage::registry('current_shipment');
    }
    
    public function getSubmitUrl()
    {
        return $this->getUrl('*/*/bookOrder', array('id'=>$this->getShipment()->getId()));
    }

    public function getLabelUrl()
    {
        return $this->getUrl('*/*/downloadLabels', array('id'=>$this->getShipment()->getId()));
    }

}
