<?php
/**
 * Mfb_Myflyingbox extension
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 * 
 * @category       Mfb
 * @package        Mfb_Myflyingbox
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Shipment admin grid block
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Shipment_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('shipmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
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
            ->getCollection();
        
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
        $this->addExportType('*/*/exportCsv', Mage::helper('mfb_myflyingbox')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('mfb_myflyingbox')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('mfb_myflyingbox')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('shipment');
        $this->getMassactionBlock()->addItem(
            'delete',
            array(
                'label'=> Mage::helper('mfb_myflyingbox')->__('Delete'),
                'url'  => $this->getUrl('*/*/massDelete'),
                'confirm'  => Mage::helper('mfb_myflyingbox')->__('Are you sure?')
            )
        );
        $this->getMassactionBlock()->addItem(
            'status',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change status'),
                'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                'additional' => array(
                    'status' => array(
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Status'),
                        'values' => array(
                            '1' => Mage::helper('mfb_myflyingbox')->__('Enabled'),
                            '0' => Mage::helper('mfb_myflyingbox')->__('Disabled'),
                        )
                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'sihpper_country',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Shipper country'),
                'url'        => $this->getUrl('*/*/massSihpperCountry', array('_current'=>true)),
                'additional' => array(
                    'flag_sihpper_country' => array(
                        'name'   => 'flag_sihpper_country',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Shipper country'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'recipient_country',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Receiver country'),
                'url'        => $this->getUrl('*/*/massRecipientCountry', array('_current'=>true)),
                'additional' => array(
                    'flag_recipient_country' => array(
                        'name'   => 'flag_recipient_country',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Receiver country'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Mfb_Myflyingbox_Model_Shipment
     * @return string
     * @author Ultimate Module Creator
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Shipment_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
