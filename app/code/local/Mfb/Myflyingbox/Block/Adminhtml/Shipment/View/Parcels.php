<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Parcels extends Mage_Adminhtml_Block_Template
{
    public function getParcelInfo($parcel){

        $api = Mage::helper('mfb_myflyingbox')->getApiInstance();
        $shipment = $parcel->getParentShipment();

        $booking = Lce\Resource\Order::find($shipment->getApiOrderUuid());
        $tracking_info = $booking->tracking();

        return $tracking_info;


    }
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
    
}
