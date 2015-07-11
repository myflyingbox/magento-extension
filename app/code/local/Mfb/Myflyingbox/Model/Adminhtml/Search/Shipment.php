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
 * Admin search model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Adminhtml_Search_Shipment extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Adminhtml_Search_Shipment
     * @author Ultimate Module Creator
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('mfb_myflyingbox/shipment_collection')
            ->addFieldToFilter('shipper_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $shipment) {
            $arr[] = array(
                'id'          => 'shipment/1/'.$shipment->getId(),
                'type'        => Mage::helper('mfb_myflyingbox')->__('Shipment'),
                'name'        => $shipment->getShipperName(),
                'description' => $shipment->getShipperName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/myflyingbox_shipment/edit',
                    array('id'=>$shipment->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
