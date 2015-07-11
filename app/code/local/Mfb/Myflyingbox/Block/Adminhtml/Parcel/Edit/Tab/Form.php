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
 * Parcel edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Parcel_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Parcel_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('parcel_');
        $form->setFieldNameSuffix('parcel');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'parcel_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Parcel'))
        );
        $values = Mage::getResourceModel('mfb_myflyingbox/shipment_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="parcel_shipment_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeShipmentIdLink() {
                if ($(\'parcel_shipment_id\').value == \'\') {
                    $(\'parcel_shipment_id_link\').hide();
                } else {
                    $(\'parcel_shipment_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/myflyingbox_shipment/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'parcel_shipment_id\').value);
                    $(\'parcel_shipment_id_link\').href = realUrl;
                    $(\'parcel_shipment_id_link\').innerHTML = text.replace(\'{#name}\', $(\'parcel_shipment_id\').options[$(\'parcel_shipment_id\').selectedIndex].innerHTML);
                }
            }
            $(\'parcel_shipment_id\').observe(\'change\', changeShipmentIdLink);
            changeShipmentIdLink();
            </script>';

        $fieldset->addField(
            'shipment_id',
            'select',
            array(
                'label'     => Mage::helper('mfb_myflyingbox')->__('Shipment'),
                'name'      => 'shipment_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
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
            'weight',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Weigh (kg)'),
                'name'  => 'weight',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'shipper_reference',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Shipper reference'),
                'name'  => 'shipper_reference',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'recipient_reference',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Receiver reference'),
                'name'  => 'recipient_reference',

           )
        );

        $fieldset->addField(
            'customer_reference',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Customer reference'),
                'name'  => 'customer_reference',

           )
        );

        $fieldset->addField(
            'value',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Declared value (in cents)'),
                'name'  => 'value',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'currency',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Value currency'),
                'name'  => 'currency',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getModel('mfb_myflyingbox/parcel_attribute_source_currency')->getAllOptions(true),
           )
        );

        $fieldset->addField(
            'description',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Content description'),
                'name'  => 'description',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'country_of_origin',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Country of origin'),
                'name'  => 'country_of_origin',
            'required'  => true,
            'class' => 'required-entry',

            'values'=> Mage::getResourceModel('directory/country_collection')->toOptionArray(),
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
        $formValues = Mage::registry('current_parcel')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getParcelData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getParcelData());
            Mage::getSingleton('adminhtml/session')->setParcelData(null);
        } elseif (Mage::registry('current_parcel')) {
            $formValues = array_merge($formValues, Mage::registry('current_parcel')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
