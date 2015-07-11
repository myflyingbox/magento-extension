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
 * Shipment edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Shipment_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Shipment_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('shipment_');
        $form->setFieldNameSuffix('shipment');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'shipment_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Shipment'))
        );

        $fieldset->addField(
            'api_quote_uuid',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('API Quote ID'),
                'name'  => 'api_quote_uuid',

           )
        );

        $fieldset->addField(
            'api_offer_uuid',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('API offer UUID'),
                'name'  => 'api_offer_uuid',

           )
        );

        $fieldset->addField(
            'api_order_uuid',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('API order uuid'),
                'name'  => 'api_order_uuid',

           )
        );

        $fieldset->addField(
            'collection_date',
            'date',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Pickup date'),
                'name'  => 'collection_date',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
           )
        );

        $fieldset->addField(
            'relay_delivery_code',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Relay delivery code'),
                'name'  => 'relay_delivery_code',

           )
        );

        $fieldset->addField(
            'relay_delivery_address',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Relay delivery address'),
                'name'  => 'relay_delivery_address',

           )
        );

        $fieldset->addField(
            'shipper_name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper name'),
                'name'  => 'shipper_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'shipper_company',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper company'),
                'name'  => 'shipper_company',

           )
        );

        $fieldset->addField(
            'shipper_street',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper address'),
                'name'  => 'shipper_street',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'shipper_city',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper city'),
                'name'  => 'shipper_city',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'shipper_state',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper state'),
                'name'  => 'shipper_state',

           )
        );

        $fieldset->addField(
            'shipper_postal_code',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper postal code'),
                'name'  => 'shipper_postal_code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'sihpper_country',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper country'),
                'name'  => 'sihpper_country',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
           )
        );

        $fieldset->addField(
            'shipper_phone',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper phone'),
                'name'  => 'shipper_phone',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'shipper_email',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper email'),
                'name'  => 'shipper_email',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_name',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver name'),
                'name'  => 'recipient_name',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_company',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver company'),
                'name'  => 'recipient_company',

           )
        );

        $fieldset->addField(
            'recipient_street',
            'textarea',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver address'),
                'name'  => 'recipient_street',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_city',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver city'),
                'name'  => 'recipient_city',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_state',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver state'),
                'name'  => 'recipient_state',

           )
        );

        $fieldset->addField(
            'recipient_postal_code',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver postal code'),
                'name'  => 'recipient_postal_code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_country',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver country'),
                'name'  => 'recipient_country',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
           )
        );

        $fieldset->addField(
            'recipient_phone',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver phone'),
                'name'  => 'recipient_phone',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_email',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver email'),
                'name'  => 'recipient_email',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'booked_at',
            'date',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Date of booking'),
                'name'  => 'booked_at',

            'image' => $this->getSkinUrl('images/grid-cal.gif'),
            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
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
        $formValues = Mage::registry('current_shipment')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getShipmentData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getShipmentData());
            Mage::getSingleton('adminhtml/session')->setShipmentData(null);
        } elseif (Mage::registry('current_shipment')) {
            $formValues = array_merge($formValues, Mage::registry('current_shipment')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
