<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View extends Mage_Adminhtml_Block_Widget_Form_Container
{

    public function __construct()
    {
        $this->_objectId    = 'id';
        $this->_blockGroup = 'mfb_myflyingbox';
        $this->_controller  = 'adminhtml_shipment';
        $this->_mode        = 'view';

        parent::__construct();
        
        $this->_removeButton('delete');
        $this->_removeButton('reset');
        $this->_removeButton('save');
        $this->_updateButton(
            'back',
            'label',
            Mage::helper('mfb_myflyingbox')->__('Back to order')
        );
        
        $this->setId('mfb_shipment_view');
        
        $shipment = $this->getShipment();
        $order = $this->getOrder();
        
        if ($shipment->canEdit()) {
        
            $onclickJs = 'setLocation(\''.$this->getUrl('*/*/edit').'\');';
            $this->_addButton('shipment_edit', array(
                'label'    => Mage::helper('mfb_myflyingbox')->__('Edit shipment'),
                'onclick'  => $onclickJs,
            ));
        }
        
        $coreHelper = Mage::helper('core');
    }

    /**
     * Retrieve order model object
     *
     * @return Mage_Sales_Model_Order
     */
    public function getShipment()
    {
        return Mage::registry('current_shipment');
    }

    /**
     * Retrieve Order Identifier
     *
     * @return int
     */
    public function getShipmentId()
    {
        return $this->getShipment()->getId();
    }


    public function getOrder()
    {
      return $this->getShipment()->getParentOrder();
    }

    public function getUrl($params='', $params2=array())
    {
        $params2['id'] = $this->getShipmentId();
        return parent::getUrl($params, $params2);
    }

    public function getBackUrl()
    {
        return $this->getUrl('*/sales_order/view', array('order_id'=>$this->getOrder()->getId()));
    }

    public function getEditUrl()
    {
        return $this->getUrl('*/*/edit');
    }

    public function getHeaderText()
    {
        return Mage::helper('mfb_myflyingbox')->__('Myflyingbox Shipment for order # %s', $this->getOrder()->getRealOrderId(), 'medium', true);
    }

}
