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
 * Dimension admin grid block
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Dimension_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('dimensionGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('mfb_myflyingbox/dimension')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Grid
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
            'weight_to',
            array(
                'header'    => Mage::helper('mfb_myflyingbox')->__('Weight (up to)'),
                'align'     => 'left',
                'index'     => 'weight_to',
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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Grid
     * @author Ultimate Module Creator
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('dimension');
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
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param Mfb_Myflyingbox_Model_Dimension
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
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Grid
     * @author Ultimate Module Creator
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
