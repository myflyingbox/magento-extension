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
 * Service model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Service extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_service';
    const CACHE_TAG = 'mfb_myflyingbox_service';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_service';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'service';


    // Storing destination restrictions
    public $included_destinations = array();
    public $excluded_destinations = array();
    
    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Ultimate Module Creator
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('mfb_myflyingbox/service');
        
        if ($this->getId() > 0)
          $this->loadDestinationRestrictions();
    }

    /**
     * before save service
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Service
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * save service relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Service
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Ultimate Module Creator
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }

    public function loadByCode($code)
    {
      $service = $this->getCollection()
                  ->addFieldToFilter("code", $code)
                  ->setPageSize(1)
                  ->loadData()
                  ->getData();
                  
      if (empty($service)) {
        Mage::log('Cound not find service with code '.$code);
        return false;
      }
      return $this->load($service[0]['entity_id']);
    }


    public function isEnabled() {
      return ($this->getStatus() == 1);
    }
    
    public function destinationSupported($postal_code, $country) {
      // Result tag; by default, all destinations are supported.
      $supported = true;
      
      // First, checking if inclusion restrictions apply
      if ( count($this->included_destinations) > 0 ) {
        if ( ! array_key_exists($country, $this->included_destinations) ) {
          // The country is not included, we can get out now.
          $supported = false;
        } else {
          // Country is included, let's check the postcodes, if necessary
          if (count($this->included_destinations[$country]) > 0 ) {
            $included = false;
            foreach( $this->included_destinations[$country] as $included_postcode ) {
              if( strrpos($postal_code, $included_postcode, -strlen($postal_code)) !== FALSE ) { // This code checks whether postal code starts with the characters from excluded_postcode
                $included = true;
              }
            }
            if ( ! $included ) $supported = false; // The postal code was not included in any way, so we just get out.
          }
        }
      }
      
      // If arrive here, it means that either there is no inclusion restriction, or we have passed
      // all inclusion rules.
      // We now check that there is no applicable exclusion rule.
      // If any exclusion rule matches, we return false.
      if ( count($this->excluded_destinations) > 0 ) {
        if ( array_key_exists($country, $this->excluded_destinations) ) {
          // The country has some exclusion rules, let's check the postcodes, if necessary
          if (count($this->excluded_destinations[$country]) > 0 ) {
            foreach( $this->excluded_destinations[$country] as $excluded_postcode ) {
              // Checks whether postal code starts with the characters from excluded_postcode
              // If any match, we return false.
              if( strrpos($postal_code, $excluded_postcode, -strlen($postal_code)) !== FALSE ) {
                $supported = false;
              }
            }
          } else {
            // There are no postcode exclusion rules, so the whole country is excluded.
            $supported = false;
          }
        }
      }
      
      return $supported;
    }
    
    
    private function loadDestinationRestrictions() {
      // Loading included destinations first
      $rules = array();
      
      if(!empty($this->getIncludedPostcodes()))
      {
        foreach( explode(',', $this->getIncludedPostcodes()) as $part1) {
          foreach( explode('\r\n', $part1) as $part2 ) {
            $rules[] = $part2;
          }
        }
      }
      foreach( $rules as $rule ) {
        $split = explode('-', $rule);
        $country = trim($split[0]);
        if ( count($split) == 2) {
          $postcode = trim($split[1]);
        } else {
          $postcode = false;
        }
        // Now we have the country and the (optional) postcode for this rule
        if ( ! array_key_exists($country, $this->included_destinations) && !empty( $country ) ) {
          $this->included_destinations[$country] = array();
        }
        if ( $postcode ) {
          $this->included_destinations[$country][] = $postcode;
        }
      }
      
      
      // Loading excluded destinations
      $rules = array();
      if(!empty($this->getExcludedPostcodes()))
      {
        foreach( explode(',', $this->getExcludedPostcodes()) as $part1) {
          foreach( explode("\n", $part1) as $part2 ) {
            $rules[] = $part2;
          }
        }
      }
      foreach( $rules as $rule ) {
        $split = explode('-', $rule);
        $country = trim($split[0]);
        if ( count($split) == 2) {
          $postcode = trim($split[1]);
        } else {
          $postcode = false;
        }
        // Now we have the country and the (optional) postcode for this rule
        if ( ! array_key_exists($country, $this->excluded_destinations) && !empty( $country )  ) {
          $this->excluded_destinations[$country] = array();
        }
        if ( $postcode ) {
          $this->excluded_destinations[$country][] = $postcode;
        }
      }
    }
}
