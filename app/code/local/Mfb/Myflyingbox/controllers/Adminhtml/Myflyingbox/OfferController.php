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
 * Offer admin controller
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Adminhtml_Myflyingbox_OfferController extends Mfb_Myflyingbox_Controller_Adminhtml_Myflyingbox
{
    /**
     * init the offer
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Offer
     */
    protected function _initOffer()
    {
        $offerId  = (int) $this->getRequest()->getParam('id');
        $offer    = Mage::getModel('mfb_myflyingbox/offer');
        if ($offerId) {
            $offer->load($offerId);
        }
        Mage::register('current_offer', $offer);
        return $offer;
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
             ->_title(Mage::helper('mfb_myflyingbox')->__('Offers'));
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
     * edit offer - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function editAction()
    {
        $offerId    = $this->getRequest()->getParam('id');
        $offer      = $this->_initOffer();
        if ($offerId && !$offer->getId()) {
            $this->_getSession()->addError(
                Mage::helper('mfb_myflyingbox')->__('This offer no longer exists.')
            );
            $this->_redirect('*/*/');
            return;
        }
        $data = Mage::getSingleton('adminhtml/session')->getOfferData(true);
        if (!empty($data)) {
            $offer->setData($data);
        }
        Mage::register('offer_data', $offer);
        $this->loadLayout();
        $this->_title(Mage::helper('mfb_myflyingbox')->__('MyFlyingBox'))
             ->_title(Mage::helper('mfb_myflyingbox')->__('Offers'));
        if ($offer->getId()) {
            $this->_title($offer->getApiOfferUuid());
        } else {
            $this->_title(Mage::helper('mfb_myflyingbox')->__('Add offer'));
        }
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
        $this->renderLayout();
    }

    /**
     * new offer action
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
     * save offer - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function saveAction()
    {
        if ($data = $this->getRequest()->getPost('offer')) {
            try {
                $offer = $this->_initOffer();
                $offer->addData($data);
                $offer->save();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Offer was successfully saved')
                );
                Mage::getSingleton('adminhtml/session')->setFormData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $offer->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                Mage::getSingleton('adminhtml/session')->setOfferData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was a problem saving the offer.')
                );
                Mage::getSingleton('adminhtml/session')->setOfferData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Unable to find offer to save.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * delete offer - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function deleteAction()
    {
        if ( $this->getRequest()->getParam('id') > 0) {
            try {
                $offer = Mage::getModel('mfb_myflyingbox/offer');
                $offer->setId($this->getRequest()->getParam('id'))->delete();
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Offer was successfully deleted.')
                );
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting offer.')
                );
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('id')));
                Mage::logException($e);
                return;
            }
        }
        Mage::getSingleton('adminhtml/session')->addError(
            Mage::helper('mfb_myflyingbox')->__('Could not find offer to delete.')
        );
        $this->_redirect('*/*/');
    }

    /**
     * mass delete offer - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massDeleteAction()
    {
        $offerIds = $this->getRequest()->getParam('offer');
        if (!is_array($offerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select offers to delete.')
            );
        } else {
            try {
                foreach ($offerIds as $offerId) {
                    $offer = Mage::getModel('mfb_myflyingbox/offer');
                    $offer->setId($offerId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('mfb_myflyingbox')->__('Total of %d offers were successfully deleted.', count($offerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error deleting offers.')
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
        $offerIds = $this->getRequest()->getParam('offer');
        if (!is_array($offerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select offers.')
            );
        } else {
            try {
                foreach ($offerIds as $offerId) {
                $offer = Mage::getSingleton('mfb_myflyingbox/offer')->load($offerId)
                            ->setStatus($this->getRequest()->getParam('status'))
                            ->setIsMassupdate(true)
                            ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d offers were successfully updated.', count($offerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating offers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Pickup supported change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massPickupAction()
    {
        $offerIds = $this->getRequest()->getParam('offer');
        if (!is_array($offerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select offers.')
            );
        } else {
            try {
                foreach ($offerIds as $offerId) {
                $offer = Mage::getSingleton('mfb_myflyingbox/offer')->load($offerId)
                    ->setPickup($this->getRequest()->getParam('flag_pickup'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d offers were successfully updated.', count($offerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating offers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass Relay delivery supported change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massRelayAction()
    {
        $offerIds = $this->getRequest()->getParam('offer');
        if (!is_array($offerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select offers.')
            );
        } else {
            try {
                foreach ($offerIds as $offerId) {
                $offer = Mage::getSingleton('mfb_myflyingbox/offer')->load($offerId)
                    ->setRelay($this->getRequest()->getParam('flag_relay'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d offers were successfully updated.', count($offerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating offers.')
                );
                Mage::logException($e);
            }
        }
        $this->_redirect('*/*/index');
    }

    /**
     * mass quote change - action
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function massQuoteIdAction()
    {
        $offerIds = $this->getRequest()->getParam('offer');
        if (!is_array($offerIds)) {
            Mage::getSingleton('adminhtml/session')->addError(
                Mage::helper('mfb_myflyingbox')->__('Please select offers.')
            );
        } else {
            try {
                foreach ($offerIds as $offerId) {
                $offer = Mage::getSingleton('mfb_myflyingbox/offer')->load($offerId)
                    ->setQuoteId($this->getRequest()->getParam('flag_quote_id'))
                    ->setIsMassupdate(true)
                    ->save();
                }
                $this->_getSession()->addSuccess(
                    $this->__('Total of %d offers were successfully updated.', count($offerIds))
                );
            } catch (Mage_Core_Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('mfb_myflyingbox')->__('There was an error updating offers.')
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
        $fileName   = 'offer.csv';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_offer_grid')
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
        $fileName   = 'offer.xls';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_offer_grid')
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
        $fileName   = 'offer.xml';
        $content    = $this->getLayout()->createBlock('mfb_myflyingbox/adminhtml_offer_grid')
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
        return Mage::getSingleton('admin/session')->isAllowed('sales/mfb_myflyingbox/offer');
    }
}
