<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Parcels extends Mage_Adminhtml_Block_Template
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
        return $this->getUrl('*/*/addParcel', array('id'=>$this->getShipment()->getId()));
    }
    
    public function getDeleteUrl($parcel_id)
    {
        return $this->getUrl('*/*/deleteParcel', array('parcel_id' => $parcel_id, 'id'=>$this->getShipment()->getId()));
    }

    public function getPopUpUrl($parcel_id)
    {
        return $this->getUrl('*/myflyingbox_parcel/popup', array('id' => $parcel_id, 'shipment_id'=>$this->getShipment()->getId()));
    }

}
