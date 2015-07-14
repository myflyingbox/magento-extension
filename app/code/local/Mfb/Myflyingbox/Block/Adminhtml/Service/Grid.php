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
 * Service admin grid block
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Service_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('serviceGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Service_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mfb_myflyingbox/service')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Service_Grid
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
            'code',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Code'),
                'align'     => 'left',
                'index'     => 'code',
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
            'name',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Name'),
                'index'  => 'name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'display_name',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Display name'),
                'index'  => 'display_name',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'pickup',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Pickup'),
                'index'  => 'pickup',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                    '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                )

            )
        );
        $this->addColumn(
            'relay',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Relay'),
                'index'  => 'relay',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                    '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                )

            )
        );

        $this->addColumn(
            'flatrate_pricing',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Flatrate pricing'),
                'index'  => 'flatrate_pricing',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                    '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                )

            )
        );
        $this->addColumn(
            'description',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Service description'),
                'index'  => 'description',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'tracking_url',
            array(
                'header' => Mage::helper('mfb_myflyingbox')->__('Tracking URL'),
                'index'  => 'tracking_url',
                'type'=> 'text',

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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Service_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('service');
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
            'pickup',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Pickup'),
                'url'        => $this->getUrl('*/*/massPickup', array('_current'=>true)),
                'additional' => array(
                    'flag_pickup' => array(
                        'name'   => 'flag_pickup',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Pickup'),
                        'values' => array(
                                '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                                '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                            )

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'relay',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Relay'),
                'url'        => $this->getUrl('*/*/massRelay', array('_current'=>true)),
                'additional' => array(
                    'flag_relay' => array(
                        'name'   => 'flag_relay',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Relay'),
                        'values' => array(
                                '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                                '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                            )

                    )
                )
            )
        );

        $this->getMassactionBlock()->addItem(
            'flatrate_pricing',
            array(
                'label'      => Mage::helper('mfb_myflyingbox')->__('Change Flatrate pricing'),
                'url'        => $this->getUrl('*/*/massFlatratePricing', array('_current'=>true)),
                'additional' => array(
                    'flag_flatrate_pricing' => array(
                        'name'   => 'flag_flatrate_pricing',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('mfb_myflyingbox')->__('Flatrate pricing'),
                        'values' => array(
                                '1' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                                '0' => Mage::helper('mfb_myflyingbox')->__('No'),
                            )

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
     * @param Mfb_Myflyingbox_Model_Service
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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Service_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
