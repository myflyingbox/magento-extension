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
 * Dimension edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Dimension_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Dimension_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('dimension_');
        $form->setFieldNameSuffix('dimension');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'dimension_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Dimension'))
        );

        $fieldset->addField(
            'weight_from',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Weight (from)'),
                'name'  => 'weight_from',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'weight_to',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Weight (up to)'),
                'name'  => 'weight_to',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'length',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Length (cm)'),
                'name'  => 'length',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'width',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Width (cm)'),
                'name'  => 'width',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'height',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Height (cm)'),
                'name'  => 'height',
            'required'  => true,
            'class' => 'required-entry',

           )
        );
        $fieldset->addField(
            'status',
            'select',
            array(
                'label'  => Mage::helper('mfb_myflyingbox')->__('Status'),
                'name'   => 'status',
                'values' => array(
                    array(
                        'value' => 1,
                        'label' => Mage::helper('mfb_myflyingbox')->__('Enabled'),
                    ),
                    array(
                        'value' => 0,
                        'label' => Mage::helper('mfb_myflyingbox')->__('Disabled'),
                    ),
                ),
            )
        );
        $formValues = Mage::registry('current_dimension')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getDimensionData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getDimensionData());
            Mage::getSingleton('adminhtml/session')->setDimensionData(null);
        } elseif (Mage::registry('current_dimension')) {
            $formValues = array_merge($formValues, Mage::registry('current_dimension')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
