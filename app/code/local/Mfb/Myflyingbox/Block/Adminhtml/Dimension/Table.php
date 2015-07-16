<?php

class Mfb_Myflyingbox_Block_Adminhtml_Dimension_Table extends Mage_Adminhtml_Block_Widget_Form_Container
{ 
  public function __construct()
  {
    parent::__construct();
  }

  public function getDimensionSaveUrl() 
  { 
    return $this->getUrl('*/*/configSave');
  }

}
