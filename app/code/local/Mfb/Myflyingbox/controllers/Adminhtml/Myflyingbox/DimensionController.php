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
 * Dimension admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_DimensionController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the dimension
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Dimension
     */
    protected function _initDimension()
    {
        $dimensionId  = (int) $this->getRequest()->getParam('id');
        $dimension    = Mage::getModel('mfb_myflyingbox/dimension');
        if ($dimensionId) {
            $dimension->load($dimensionId);
        }
        Mage::register('current_dimension', $dimension);
        return $dimension;
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
             ->_title(Mage::helper('mfb_myflyingbox')->__('Dimensions'));
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
     * edit dimension - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $dimensionId    = $this->getRequest()->getParam('id');
        $dimension      = $this->_initDimension();
        if ($dimensionId && !$dimension->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This dimension no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getDimensionData(true);
        if (!empty($data)) {
            $dimension->setData($data);
        }
        Mage::register('dimension_data', $dimension);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Dimensions'));
        if ($dimension->getId()) {
            $this->_title($dimension->getWeightTo());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add dimension'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new dimension action
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
     * save dimension - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('dimension')) {
            try {
                $dimension = $this->_initDimension();
                $dimension->addData($data);
                $dimension->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Dimension was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $dimension->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setDimensionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the dimension.')
                );
                Mage::getSingleton('adminhtml/session')->setDimensionData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find dimension to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete dimension - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $dimension = Mage::getModel('mfb_myflyingbox/dimension');
                $dimension->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Dimension was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting dimension.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find dimension to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete dimension - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $dimensionIds = $this->getRequest()->getParam('dimension');
        if (!is_array($dimensionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select dimensions to delete.')
            );
        } else {
            try {
                foreach ($dimensionIds as $dimensionId) {
                    $dimension = Mage::getModel('mfb_myflyingbox/dimension');
                    $dimension->setId($dimensionId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d dimensions were successfully deleted.', count($dimensionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting dimensions.')
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
        $dimensionIds = $this->getRequest()->getParam('dimension');
        if (!is_array($dimensionIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select dimensions.')
            );
        } else {
            try {
                foreach ($dimensionIds as $dimensionId) {
                $dimension = Mage::getSingleton('mfb_myflyingbox/dimension')->load($dimensionId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d dimensions were successfully updated.', count($dimensionIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating dimensions.')
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
        $fileName   = 'dimension.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_dimension_grid')
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
        $fileName   = 'dimension.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_dimension_grid')
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
        $fileName   = 'dimension.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_dimension_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/dimension');
    }
}
