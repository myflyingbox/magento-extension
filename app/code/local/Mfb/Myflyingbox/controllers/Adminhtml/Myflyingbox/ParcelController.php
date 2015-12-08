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
 * Parcel admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_ParcelController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the parcel
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Parcel
     */
    protected function _initParcel()
    {
        $parcelId  = (int) $this->getRequest()->getParam('id');
        $parcel    = Mage::getModel('mfb_myflyingbox/parcel');
        if ($parcelId) {
            $parcel->load($parcelId);
        }
        Mage::register('current_parcel', $parcel);
        return $parcel;
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
             ->_title(Mage::helper('mfb_myflyingbox')->__('Parcels'));
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
     * edit parcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $parcelId    = $this->getRequest()->getParam('id');
        $parcel      = $this->_initParcel();
        if ($parcelId && !$parcel->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This parcel no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getParcelData(true);
        if (!empty($data)) {
            $parcel->setData($data);
        }
        Mage::register('parcel_data', $parcel);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Parcels'));
        if ($parcel->getId()) {
            $this->_title($parcel->getShipperReference());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add parcel'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new parcel action
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
     * save parcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('parcel')) {
            try {
                $parcel = $this->_initParcel();
                $parcel->addData($data);
                $parcel->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Parcel was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $parcel->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setParcelData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the parcel.')
                );
                Mage::getSingleton('adminhtml/session')->setParcelData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find parcel to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete parcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $parcel = Mage::getModel('mfb_myflyingbox/parcel');
                $parcel->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Parcel was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting parcel.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find parcel to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete parcel - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $parcelIds = $this->getRequest()->getParam('parcel');
        if (!is_array($parcelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select parcels to delete.')
            );
        } else {
            try {
                foreach ($parcelIds as $parcelId) {
                    $parcel = Mage::getModel('mfb_myflyingbox/parcel');
                    $parcel->setId($parcelId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d parcels were successfully deleted.', count($parcelIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting parcels.')
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
        $parcelIds = $this->getRequest()->getParam('parcel');
        if (!is_array($parcelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select parcels.')
            );
        } else {
            try {
                foreach ($parcelIds as $parcelId) {
                $parcel = Mage::getSingleton('mfb_myflyingbox/parcel')->load($parcelId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d parcels were successfully updated.', count($parcelIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating parcels.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Value currency change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massCurrencyAction()
    {
        $parcelIds = $this->getRequest()->getParam('parcel');
        if (!is_array($parcelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select parcels.')
            );
        } else {
            try {
                foreach ($parcelIds as $parcelId) {
                $parcel = Mage::getSingleton('mfb_myflyingbox/parcel')->load($parcelId)
                    ->setCurrency($this->getRequest()->getParam('flag_currency'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d parcels were successfully updated.', count($parcelIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating parcels.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Country of origin change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massCountryOfOriginAction()
    {
        $parcelIds = $this->getRequest()->getParam('parcel');
        if (!is_array($parcelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select parcels.')
            );
        } else {
            try {
                foreach ($parcelIds as $parcelId) {
                $parcel = Mage::getSingleton('mfb_myflyingbox/parcel')->load($parcelId)
                    ->setCountryOfOrigin($this->getRequest()->getParam('flag_country_of_origin'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d parcels were successfully updated.', count($parcelIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating parcels.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass shipment change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massShipmentIdAction()
    {
        $parcelIds = $this->getRequest()->getParam('parcel');
        if (!is_array($parcelIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select parcels.')
            );
        } else {
            try {
                foreach ($parcelIds as $parcelId) {
                $parcel = Mage::getSingleton('mfb_myflyingbox/parcel')->load($parcelId)
                    ->setShipmentId($this->getRequest()->getParam('flag_shipment_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d parcels were successfully updated.', count($parcelIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating parcels.')
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
        $fileName   = 'parcel.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_parcel_grid')
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
        $fileName   = 'parcel.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_parcel_grid')
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
        $fileName   = 'parcel.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_parcel_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/parcel');
    }

    public function popupAction(){
        $this->loadLayout()->renderLayout();
    }
}
