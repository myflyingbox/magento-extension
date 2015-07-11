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
 * Offer edit form tab
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Block_Adminhtml_Offer_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return Mfb_Myflyingbox_Block_Adminhtml_Offer_Edit_Tab_Form
     * @author Ultimate Module Creator
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('offer_');
        $form->setFieldNameSuffix('offer');
        $this->setForm($form);
        $fieldset = $form->addFieldset(
            'offer_form',
            array('legend' => Mage::helper('mfb_myflyingbox')->__('Offer'))
        );
        $values = Mage::getResourceModel('mfb_myflyingbox/quote_collection')
            ->toOptionArray();
        array_unshift($values, array('label' => '', 'value' => ''));

        $html = '<a href="{#url}" id="offer_quote_id_link" target="_blank"></a>';
        $html .= '<script type="text/javascript">
            function changeQuoteIdLink() {
                if ($(\'offer_quote_id\').value == \'\') {
                    $(\'offer_quote_id_link\').hide();
                } else {
                    $(\'offer_quote_id_link\').show();
                    var url = \''.$this->getUrl('adminhtml/myflyingbox_quote/edit', array('id'=>'{#id}', 'clear'=>1)).'\';
                    var text = \''.Mage::helper('core')->escapeHtml($this->__('View {#name}')).'\';
                    var realUrl = url.replace(\'{#id}\', $(\'offer_quote_id\').value);
                    $(\'offer_quote_id_link\').href = realUrl;
                    $(\'offer_quote_id_link\').innerHTML = text.replace(\'{#name}\', $(\'offer_quote_id\').options[$(\'offer_quote_id\').selectedIndex].innerHTML);
                }
            }
            $(\'offer_quote_id\').observe(\'change\', changeQuoteIdLink);
            changeQuoteIdLink();
            </script>';

        $fieldset->addField(
            'quote_id',
            'select',
            array(
                'label'     => Mage::helper('mfb_myflyingbox')->__('Quote'),
                'name'      => 'quote_id',
                'required'  => false,
                'values'    => $values,
                'after_element_html' => $html
            )
        );

        $fieldset->addField(
            'api_offer_uuid',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('API offer uuid'),
                'name'  => 'api_offer_uuid',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'mfb_product_code',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('MFB product code'),
                'name'  => 'mfb_product_code',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'pickup',
            'select',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Pickup supported'),
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
                'label' => Mage::helper('mfb_myflyingbox')->__('Relay delivery supported'),
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
            'base_price_in_cents',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Base price (in cents)'),
                'name'  => 'base_price_in_cents',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'total_price_in_cents',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Total price (cents)'),
                'name'  => 'total_price_in_cents',
            'required'  => true,
            'class' => 'required-entry',

           )
        );

        $fieldset->addField(
            'currency',
            'text',
            array(
                'label' => Mage::helper('mfb_myflyingbox')->__('Price curency'),
                'name'  => 'currency',
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
        $formValues = Mage::registry('current_offer')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getOfferData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getOfferData());
            Mage::getSingleton('adminhtml/session')->setOfferData(null);
        } elseif (Mage::registry('current_offer')) {
            $formValues = array_merge($formValues, Mage::registry('current_offer')->getData());
        }
        $form->setValues($formValues);
        return parent::_prepareForm();
    }
}
