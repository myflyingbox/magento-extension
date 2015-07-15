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
 * Shipment admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_ShipmentController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the shipment
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Shipment
     */
    protected function _initShipment()
    {
        $shipmentId  = (int) $this->getRequest()->getParam('id');
        $shipment    = Mage::getModel('mfb_myflyingbox/shipment');
        if ($shipmentId) {
            $shipment->load($shipmentId);
        }
        Mage::register('current_shipment', $shipment);
        return $shipment;
    }

    /**
     * default action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Shipments'));
        $this->renderLayout();
    }

    /**
     * grid action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function gridAction()
    {
        $this->loadLayout()->renderLayout();
    }

    /**
     * edit shipment - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $shipmentId    = $this->getRequest()->getParam('id');
        $shipment      = $this->_initShipment();
        if ($shipmentId && !$shipment->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This shipment no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getShipmentData(true);
        if (!empty($data)) {
            $shipment->setData($data);
        }
        Mage::register('shipment_data', $shipment);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Shipments'));
        if ($shipment->getId()) {
            $this->_title($shipment->getShipperName());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add shipment'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    public function viewAction()
    {
        $this->_title($this->__('MyFlyingBox'))->_title($this->__('Shipments'));

        $shipmentId    = $this->getRequest()->getParam('id');
        $shipment      = $this->_initShipment();
        if ($shipment) {
            $this->loadLayout();
            $this->renderLayout();
        }
    }



    /**
     * new shipment action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * save shipment - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('shipment')) {
            try {
                $data = $this->_filterDates($data, array('collection_date' ,'booked_at'));
                $shipment = $this->_initShipment();
                $shipment->addData($data);
                $shipment->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Shipment was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $shipment->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setShipmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the shipment.')
                );
                Mage::getSingleton('adminhtml/session')->setShipmentData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find shipment to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete shipment - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $shipment = Mage::getModel('mfb_myflyingbox/shipment');
                $shipment->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Shipment was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting shipment.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find shipment to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete shipment - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $shipmentIds = $this->getRequest()->getParam('shipment');
        if (!is_array($shipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select shipments to delete.')
            );
        } else {
            try {
                foreach ($shipmentIds as $shipmentId) {
                    $shipment = Mage::getModel('mfb_myflyingbox/shipment');
                    $shipment->setId($shipmentId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d shipments were successfully deleted.', count($shipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting shipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass status change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massStatusAction()
    {
        $shipmentIds = $this->getRequest()->getParam('shipment');
        if (!is_array($shipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select shipments.')
            );
        } else {
            try {
                foreach ($shipmentIds as $shipmentId) {
                $shipment = Mage::getSingleton('mfb_myflyingbox/shipment')->load($shipmentId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating shipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Shipper country change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massSihpperCountryAction()
    {
        $shipmentIds = $this->getRequest()->getParam('shipment');
        if (!is_array($shipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select shipments.')
            );
        } else {
            try {
                foreach ($shipmentIds as $shipmentId) {
                $shipment = Mage::getSingleton('mfb_myflyingbox/shipment')->load($shipmentId)
                    ->setSihpperCountry($this->getRequest()->getParam('flag_sihpper_country'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating shipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Receiver country change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massRecipientCountryAction()
    {
        $shipmentIds = $this->getRequest()->getParam('shipment');
        if (!is_array($shipmentIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select shipments.')
            );
        } else {
            try {
                foreach ($shipmentIds as $shipmentId) {
                $shipment = Mage::getSingleton('mfb_myflyingbox/shipment')->load($shipmentId)
                    ->setRecipientCountry($this->getRequest()->getParam('flag_recipient_country'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d shipments were successfully updated.', count($shipmentIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating shipments.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * export as csv - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportCsvAction()
    {
        $fileName   = 'shipment.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_shipment_grid')
            ->getCsv();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as MsExcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportExcelAction()
    {
        $fileName   = 'shipment.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_shipment_grid')
            ->getExcelFile();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * export as xml - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function exportXmlAction()
    {
        $fileName   = 'shipment.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_shipment_grid')
            ->getXml();
        $this->_prepareDownloadResponse($fileName, $content);
    }

    /**
     * Check if admin has permissions to visit related pages
     *
     * @access protected
     * @return boolean
     * @author Ultimate Module Creator
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/shipment');
    }
}
