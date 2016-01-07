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
  
  // Maybe could be used to get a better carrier name display?
  public function salesQuoteCollectTotalsBefore(Varien_Event_Observer $observer)
  {
      /** @var Mage_Sales_Model_Quote $quote */
      $quote = $observer->getQuote();

      $store = Mage::app()->getStore($quote->getStoreId());
      $store->setConfig('carriers/mfb_myflyingbox/title', 'Available shipping methods:');
  }

  public function addMassActionMFB($observer)
  {   
      $block = $observer->getEvent()->getBlock();
      if(get_class($block) =='Mage_Adminhtml_Block_Widget_Grid_Massaction'
          && $block->getRequest()->getControllerName() == 'sales_order')
      {
          $block->addItem('mfb_massship', array(
              'label' => 'MyFlyingBox : Ship orders',
              'url' => Mage::app()->getStore()->getUrl('*/myflyingbox_shipment/massBookOrder'),
          ));
          // TODO: UNCOMMENT WHEN MASS LABEL DOWNLOAD SUPPORTED BY API!!
          //~ $block->addItem('mfb_massprintlabels', array(
              //~ 'label' => 'MyFlyingBox : Print labels',
              //~ 'url' => Mage::app()->getStore()->getUrl('*/myflyingbox_shipment/massDownloadLabels'),
          //~ ));
      }
  }
}

?>
