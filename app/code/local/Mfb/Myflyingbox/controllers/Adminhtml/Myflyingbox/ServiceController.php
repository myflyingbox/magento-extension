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
 * Service admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_ServiceController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the service
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Service
     */
    protected function _initService()
    {
        $serviceId  = (int) $this->getRequest()->getParam('id');
        $service    = Mage::getModel('mfb_myflyingbox/service');
        if ($serviceId) {
            $service->load($serviceId);
        }
        Mage::register('current_service', $service);
        return $service;
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
             ->_title(Mage::helper('mfb_myflyingbox')->__('Services'));
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
     * edit service - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $serviceId    = $this->getRequest()->getParam('id');
        $service      = $this->_initService();
        if ($serviceId && !$service->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This service no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getServiceData(true);
        if (!empty($data)) {
            $service->setData($data);
        }
        Mage::register('service_data', $service);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Services'));
        if ($service->getId()) {
            $this->_title($service->getCode());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add service'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new service action
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
     * save service - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('service')) {
            try {
                $service = $this->_initService();
                $service->addData($data);
                $service->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Service was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $service->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setServiceData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the service.')
                );
                Mage::getSingleton('adminhtml/session')->setServiceData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find service to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete service - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $service = Mage::getModel('mfb_myflyingbox/service');
                $service->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Service was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting service.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find service to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete service - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services to delete.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                    $service = Mage::getModel('mfb_myflyingbox/service');
                    $service->setId($serviceId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d services were successfully deleted.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting services.')
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
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                $service = Mage::getSingleton('mfb_myflyingbox/service')->load($serviceId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d services were successfully updated.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating services.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Pickup change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massPickupAction()
    {
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                $service = Mage::getSingleton('mfb_myflyingbox/service')->load($serviceId)
                    ->setPickup($this->getRequest()->getParam('flag_pickup'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d services were successfully updated.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating services.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Relay change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massRelayAction()
    {
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                $service = Mage::getSingleton('mfb_myflyingbox/service')->load($serviceId)
                    ->setRelay($this->getRequest()->getParam('flag_relay'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d services were successfully updated.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating services.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Active change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massActiveAction()
    {
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                $service = Mage::getSingleton('mfb_myflyingbox/service')->load($serviceId)
                    ->setActive($this->getRequest()->getParam('flag_active'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d services were successfully updated.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating services.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Flatrate pricing change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massFlatratePricingAction()
    {
        $serviceIds = $this->getRequest()->getParam('service');
        if (!is_array($serviceIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select services.')
            );
        } else {
            try {
                foreach ($serviceIds as $serviceId) {
                $service = Mage::getSingleton('mfb_myflyingbox/service')->load($serviceId)
                    ->setFlatratePricing($this->getRequest()->getParam('flag_flatrate_pricing'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d services were successfully updated.', count($serviceIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating services.')
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
        $fileName   = 'service.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_service_grid')
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
        $fileName   = 'service.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_service_grid')
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
        $fileName   = 'service.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_service_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/service');
    }
}
