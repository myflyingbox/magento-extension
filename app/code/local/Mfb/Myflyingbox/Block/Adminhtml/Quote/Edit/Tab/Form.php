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
 * Quote edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Quote_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Quote_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('quote_');
        $form->setFieldNameSuffix('quote');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'quote_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Quote'))
        );
        $values = Mage::getResourceModel('mfb_myflyingbox/shipment_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="quote_shipment_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeShipmentIdLink() {
                if ($(\'quote_shipment_id\').value == \'\') {
                    $(\'quote_shipment_id_link\').hide();
                } else {
                    $(\'quote_shipment_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/myflyingbox_shipment/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'quote_shipment_id\').value);
                    $(\'quote_shipment_id_link\').href = realUrl;
                    $(\'quote_shipment_id_link\').innerHTML = text.replace(\'{#name}\', $(\'quote_shipment_id\').options[$(\'quote_shipment_id\').selectedIndex].innerHTML);
                }
            }
            $(\'quote_shipment_id\').observe(\'change\', changeShipmentIdLink);
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
            'api_quote_uuid',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('API quote UUID'),
                'name'  => 'api_quote_uuid',
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
        $formValues = Mage::registry('current_quote')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getQuoteData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getQuoteData());
            Mage::getSingleton('adminhtml/session')->setQuoteData(null);
        } elseif (Mage::registry('current_quote')) {
            $formValues = array_merge($formValues, Mage::registry('current_quote')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
