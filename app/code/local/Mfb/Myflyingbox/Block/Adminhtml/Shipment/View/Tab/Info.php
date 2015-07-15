<?php
class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Tab_Info
 extends Mfb_Myflyingbox_Block_Adminhtml_Shipment_Abstract
 implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

  public function getShipmentId()
  {
    return Mage::registry('current_shipment')->getId();
  }
  public function getTabLabel()
  {
    return $this->__('Shipment info');
  }
  public function getTabTitle()
  {
    return $this->__('Information about this MyFlyingBox shipment');
  }
  public function canShowTab()
  {
    return true;
  }
  public function isHidden()
  {
    return false;
  }
}
