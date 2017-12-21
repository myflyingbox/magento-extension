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
 * Myflyingbox default helper
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * convert array to options
     *
     * @access public
     * @param $options
     * @return array
     * @author Ultimate Module Creator
     */
    public function convertOptions($options)
    {
        $converted = array();
        foreach ($options as $option) {
            if (isset($option['value']) && !is_array($option['value']) &&
                isset($option['label']) && !is_array($option['label'])) {
                $converted[$option['value']] = $option['label'];
            }
        }
        return $converted;
    }


    public function getApiInstance()
    {
      $carrier = Mage::getModel('mfb_myflyingbox/carrier');
      require_once(Mage::getBaseDir('lib') . '/Lce/bootstrap.php');

      // Check is php-curl is available
      if(!extension_loaded('curl')) Mage::log("php-curl does not seem te be installed on your system. Please contact your hosting provider. This extension is required for the module to work properly.");

      // API Environment
      $env = $carrier->getConfigData('api_env');
      if ($env != 'staging' && $env != 'production') $env = 'staging';

      // Initializing API lib
      $api = Lce\Lce::configure($carrier->getConfigData('api_login'), $carrier->getConfigData('api_password'), $env, '2');
      $api->application = "magento-mfb";
      $api->application_version = Mage::getConfig()->getNode()->modules->Mfb_Myflyingbox->version . " (Magento ". Mage::getVersion() .")";

      return $api;
    }
}
