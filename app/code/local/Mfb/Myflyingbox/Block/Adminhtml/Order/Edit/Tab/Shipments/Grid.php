<?php
class Mfb_Myflyingbox_Block_Adminhtml_Order_Edit_Tab_Shipments_Grid
 extends Mage_Adminhtml_Block_Widget_Grid
 implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    /**
     * constructor
     *
     * @access public
     * @author Ultimate Module Creator
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('orderShipmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setUseAjax(true);
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        
        Mage::log("block tab shipments grid initialized");
    }

  public function getOrderId()
  {
    return Mage::registry('current_order')->getId();
  }
  public function getTabLabel()
  {
    return $this->__('MyFlyingBox Shipments');
  }
  public function getTabTitle()
  {
    return $this->__('Click to view the list of MyFlyingBox shipments for this order');
  }
  public function canShowTab()
  {
    return true;
  }
  public function isHidden()
  {
    return false;
  }


    /**
     * prepare collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mfb_myflyingbox/shipment')
            ->getCollection()
            ->addFieldToFilter(
                'order_id',
                $this->getRequest()->getParam('order_id'));

        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'shipper_name',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Shipper name'),
                'align'     => 'left',
                'index'     => 'shipper_name',
            )
        );
        
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('mfb_myflyingbox')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('mfb_myflyingbox')->__('Enabled'),
                    '0' => Mage::helper('mfb_myflyingbox')->__('Disabled'),
                )
            )
        );
        $this->addColumn(
            'collection_date',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Pickup date'),
                'index'  => 'collection_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'relay_delivery_code',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Relay delivery code'),
                'index'  => 'relay_delivery_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'recipient_name',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Receiver name'),
                'index'  => 'recipient_name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'recipient_company',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Receiver company'),
                'index'  => 'recipient_company',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'recipient_city',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Receiver city'),
                'index'  => 'recipient_city',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'recipient_postal_code',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Receiver postal code'),
                'index'  => 'recipient_postal_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'recipient_country',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Receiver country'),
                'index'  => 'recipient_country',
                'type'=> 'country',

            )
        );
        $this->addColumn(
            'created_at',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Created at'),
                'index'  => 'created_at',
                'width'  => '120px',
                'type'   => 'datetime',
            )
        );
        $this->addColumn(
            'updated_at',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Updated at'),
                'index'     => 'updated_at',
                'width'     => '120px',
                'type'      => 'datetime',
            )
        );
        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('mfb_myflyingbox')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('mfb_myflyingbox')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        return parent::_prepareColumns();
    }


    public function getRowUrl($row)
    {
        return $this->getUrl('adminhtml/myflyingbox_shipment/view', array('id' => $row->getId()));
    }

}
