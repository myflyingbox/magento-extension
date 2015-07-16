<?php

class Mfb_Myflyingbox_Model_Observer
{
  
  function addOrderViewShipButton($event) {
    $block = $event->getBlock();

    if ($block instanceof Mage_Adminhtml_Block_Sales_Order_View) {
        $block->addButton('mfb_order_ship', array(
            'label'     => Mage::helper('mfb_myflyingbox')->__('Ship w/ MyFlyingBox'),
            'onclick'   => "setLocation('{$block->getUrl('*/myflyingbox_shipment/newAuto')}')",
            'class'     => 'go'
        ));
    }
  }
}

?>
