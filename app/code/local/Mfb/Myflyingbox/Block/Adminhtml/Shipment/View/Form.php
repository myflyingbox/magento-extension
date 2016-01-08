<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Form extends Mage_Adminhtml_Block_Template
{
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('mfb/myflyingbox/shipment/view/form.phtml');
    }
}
