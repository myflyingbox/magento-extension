<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Parcels extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {
        $onclick = "submitAndReloadArea($('shipment_parcels_block').parentNode, '".$this->getSubmitUrl()."')";
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('mfb_myflyingbox')->__('Add new parcel'),
                'class'   => 'save',
                'onclick' => $onclick
            ));
        $this->setChild('submit_button', $button);
        
        $onclick = "submitAndReloadArea($('shipment_parcels_block').parentNode, '".$this->getDeleteUrl()."')";
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'label'   => Mage::helper('mfb_myflyingbox')->__('Delete parcel'),
                'class'   => 'delete',
                'onclick' => $onclick
            ));
        $this->setChild('delete_button', $button);
        
        
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
