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
                'disabled' => true,
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
            'disabled' => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'pickup',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Pickup'),
                'name'  => 'pickup',
                'note'  => $this->__('Does this service support pickup?'),
            'required'  => true,
            'disabled' => true,
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
                'note'  => $this->__('Does this service support relay delivery?'),
            'required'  => true,
            'disabled' => true,
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
            'insurance',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Insurance'),
                'name'  => 'insurance',
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
            'insurance_minimum_amount',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Insurance minimum amount'),
                'name'  => 'insurance_minimum_amount',
                'required'  => true,
                'class' => 'required-entry',


            )
        );
        
        $fieldset->addField(
            'carrier_display_name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Carrier display name'),
                'name'  => 'carrier_display_name',
                'note'  => $this->__('This controls the carrier name which the user sees during checkout'),
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'display_name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Service display name'),
                'name'  => 'display_name',
                'note'  => $this->__('This controls the shipping service name which the user sees during checkout'),
            'required'  => true,
            'class' => 'required-entry',

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
                'note'  => $this->__('Put the variable TRACKING_NUMBER in the URL, it will be automatically replaced with the real tracking number when generating the link')

           )
        );


        $fieldset->addField(
            'flatrate_pricing',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Flatrate pricing'),
                'name'  => 'flatrate_pricing',
                'note'  => $this->__('Activate to enable pricing based on flat-rate pricing table, as defined below. When enabled, the prices returned by the API will not be used.'),
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
                'note'  => $this->__('Prices are based on weight, so you can define as many rules as you want in the following format: Weight (up to, in kg, e.g. 6 or 7.5) | Price (float with dot as separator, e.g. 3.54). One rule per line.')
           )
        );

        $fieldset->addField(
            'included_postcodes',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Limit by country/postcode'),
                'name'  => 'included_postcodes',
                'note'  => $this->__('Limits the service only to these postcodes. One rule per line (or same line and separated by a comma), using the following format: XX-YYYYY (XX = alpha2 country code, YYYYY = full postcode or prefix). Whitespaces will be ignored. You can also specify country codes without postcodes (just "XX"). Leave blank to apply no limitation.'),

           )
        );

        $fieldset->addField(
            'excluded_postcodes',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Excluded countries/postcodes'),
                'name'  => 'excluded_postcodes',
                'note'	=> $this->__('Excludes postcodes matching this list. One rule per line, using the following format: XX-YYYYY (XX = alpha2 country code, YYYYY = full postcode or just beginning). Whitespaces will be ignored. You can also specify country codes without postcodes (just "XX").'),

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
