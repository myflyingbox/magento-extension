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
 * Quote admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_QuoteController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the quote
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Quote
     */
    protected function _initQuote()
    {
        $quoteId  = (int) $this->getRequest()->getParam('id');
        $quote    = Mage::getModel('mfb_myflyingbox/quote');
        if ($quoteId) {
            $quote->load($quoteId);
        }
        Mage::register('current_quote', $quote);
        return $quote;
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
             ->_title(Mage::helper('mfb_myflyingbox')->__('Quotes'));
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
     * edit quote - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $quoteId    = $this->getRequest()->getParam('id');
        $quote      = $this->_initQuote();
        if ($quoteId && !$quote->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This quote no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getQuoteData(true);
        if (!empty($data)) {
            $quote->setData($data);
        }
        Mage::register('quote_data', $quote);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Quotes'));
        if ($quote->getId()) {
            $this->_title($quote->getApiQuoteUuid());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add quote'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new quote action
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
     * save quote - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('quote')) {
            try {
                $quote = $this->_initQuote();
                $quote->addData($data);
                $quote->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Quote was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $quote->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setQuoteData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the quote.')
                );
                Mage::getSingleton('adminhtml/session')->setQuoteData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find quote to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete quote - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $quote = Mage::getModel('mfb_myflyingbox/quote');
                $quote->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Quote was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting quote.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find quote to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete quote - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $quoteIds = $this->getRequest()->getParam('quote');
        if (!is_array($quoteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select quotes to delete.')
            );
        } else {
            try {
                foreach ($quoteIds as $quoteId) {
                    $quote = Mage::getModel('mfb_myflyingbox/quote');
                    $quote->setId($quoteId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d quotes were successfully deleted.', count($quoteIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting quotes.')
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
        $quoteIds = $this->getRequest()->getParam('quote');
        if (!is_array($quoteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select quotes.')
            );
        } else {
            try {
                foreach ($quoteIds as $quoteId) {
                $quote = Mage::getSingleton('mfb_myflyingbox/quote')->load($quoteId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d quotes were successfully updated.', count($quoteIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating quotes.')
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
        $quoteIds = $this->getRequest()->getParam('quote');
        if (!is_array($quoteIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select quotes.')
            );
        } else {
            try {
                foreach ($quoteIds as $quoteId) {
                $quote = Mage::getSingleton('mfb_myflyingbox/quote')->load($quoteId)
                    ->setShipmentId($this->getRequest()->getParam('flag_shipment_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d quotes were successfully updated.', count($quoteIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating quotes.')
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
        $fileName   = 'quote.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_quote_grid')
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
        $fileName   = 'quote.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_quote_grid')
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
        $fileName   = 'quote.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_quote_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/quote');
    }
}
