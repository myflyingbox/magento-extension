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
 * Parcel admin grid block
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Parcel_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('parcelGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Parcel_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mfb_myflyingbox/parcel')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Parcel_Grid
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
            'shipment_id',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Shipment'),
                'index'     => 'shipment_id',
                'type'      => 'options',
                'options'   => Mage::getResourceModel('mfb_myflyingbox/shipment_collection')
                    ->toOptionHash(),
                'renderer'  => 'mfb_myflyingbox/adminhtml_helper_column_renderer_parent',
                'params'    => array(
                    'id'    => 'getShipmentId'
                ),
                'base_link' => 'adminhtml/myflyingbox_shipment/edit'
            )
        );
        $this->addColumn(
            'shipper_reference',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Shipper reference'),
                'align'     => 'left',
                'index'     => 'shipper_reference',
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
            'length',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Length (cm)'),
                'index'  => 'length',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'width',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Width (cm)'),
                'index'  => 'width',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'height',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Height (cm)'),
                'index'  => 'height',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'weight',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Weigh (kg)'),
                'index'  => 'weight',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'value',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Declared value (in cents)'),
                'index'  => 'value',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'currency',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Value currency'),
                'index'  => 'currency',
                'type'  => 'options',
                'options' => Mage::helper('mfb_myflyingbox')->convertOptions(
                    Mage::getModel('mfb_myflyingbox/parcel_attribute_source_currency')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'country_of_origin',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Country of origin'),
                'index'  => 'country_of_origin',
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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Parcel_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('parcel');
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
            'currency',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Value currency'),
                'url'        => $this->getUrl('*/*/massCurrency', array('_current'=>true)),
                'additional' => array(
                    'flag_currency' => array(
                        'name'   => 'flag_currency',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Value currency'),
                        'values' => Mage::getModel('mfb_myflyingbox/parcel_attribute_source_currency')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'country_of_origin',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Country of origin'),
                'url'        => $this->getUrl('*/*/massCountryOfOrigin', array('_current'=>true)),
                'additional' => array(
                    'flag_country_of_origin' => array(
                        'name'   => 'flag_country_of_origin',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Country of origin'),
                        'values' => Mage::getResourceModel('directory/country_collection')->toOptionArray()

                    )
                )
            )
        );
        $values = Mage::getResourceModel('mfb_myflyingbox/shipment_collection')->toOptionHash();
        $values = array_reverse($values, true);
        $values[''] = '';
        $values = array_reverse($values, true);
        $this->getMassactionBlock()->addItem(
            'shipment_id',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Shipment'),
                'url'        => $this->getUrl('*/*/massShipmentId', array('_current'=>true)),
                'additional' => array(
                    'flag_shipment_id' => array(
                        'name'   => 'flag_shipment_id',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Shipment'),
                        'values' => $values
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
     * @param Mfb_Myflyingbox_Model_Parcel
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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Parcel_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
