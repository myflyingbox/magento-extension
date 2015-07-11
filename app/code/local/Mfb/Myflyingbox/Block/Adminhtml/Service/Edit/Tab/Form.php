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
 * Service edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Service_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Service_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('service_');
        $form->setFieldNameSuffix('service');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'service_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Service'))
        );

        $fieldset->addField(
            'code',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Code'),
                'name'  => 'code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Name'),
                'name'  => 'name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'display_name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Display name'),
                'name'  => 'display_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'pickup',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Pickup'),
                'name'  => 'pickup',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('mfb_myflyingbox')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'relay',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Relay'),
                'name'  => 'relay',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('mfb_myflyingbox')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'active',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Active'),
                'name'  => 'active',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('mfb_myflyingbox')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'flatrate_pricing',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Flatrate pricing'),
                'name'  => 'flatrate_pricing',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> array(
                array(
                    'value' => 1,
                    'label' => Mage::helper('mfb_myflyingbox')->__('Yes'),
                ),
                array(
                    'value' => 0,
                    'label' => Mage::helper('mfb_myflyingbox')->__('No'),
                ),
            ),
           )
        );

        $fieldset->addField(
            'flatrate_pricelist',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('flatrate_pricelist'),
                'name'  => 'flatrate_pricelist',

           )
        );

        $fieldset->addField(
            'included_postcodes',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Limit by country/postcode'),
                'name'  => 'included_postcodes',
            'note'	=> $this->__('Will limit this service only to specified postcodes (in the form: FR-86 | FR-75010, one rule per line)'),

           )
        );

        $fieldset->addField(
            'excluded_postcodes',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Excluded countries/postcodes'),
                'name'  => 'excluded_postcodes',
            'note'	=> $this->__('Exclude the specified countries/postcodes, in the form 'FR-94 | FR-75020', one rule per line.'),

           )
        );

        $fieldset->addField(
            'description',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Service description'),
                'name'  => 'description',

           )
        );

        $fieldset->addField(
            'tracking_url',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Tracking URL'),
                'name'  => 'tracking_url',

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
        $formValues = Mage::registry('current_service')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getServiceData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getServiceData());
            Mage::getSingleton('adminhtml/session')->setServiceData(null);
        } elseif (Mage::registry('current_service')) {
            $formValues = array_merge($formValues, Mage::registry('current_service')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
