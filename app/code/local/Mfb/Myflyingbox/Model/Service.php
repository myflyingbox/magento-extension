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
    
    // Storing flat rate pricelist
    public $flat_rates = array();
    
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
        if ($this->getId() > 0) {
          $this->loadDestinationRestrictions();
        }
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
      $included_postcodes = $this->getIncludedPostcodes();
      if(!empty($included_postcodes))
      {
        foreach( explode(',', $included_postcodes) as $part1) {
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
      $excluded_postcodes = $this->getExcludedPostcodes();
      if(!empty($excluded_postcodes))
      {
        foreach( explode(',', $excluded_postcodes) as $part1) {
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
    
    public function flatratePriceForWeight( $weight, $country = null ) {
      $this->loadFlatrates( $country );
      foreach( $this->flat_rates as $rate ) {
        if ( $weight > $rate[0] && $weight < $rate[1] ) return $rate[2];
      }
      return false;
    }
    
    private function loadFlatrates( $country ) {
      $this->flat_rates = array();
      $rates = array();
      $pricelist = $this->getFlatratePricelist();
      if( !empty($pricelist) )
      {
        foreach( explode(',', $pricelist) as $part1) {
          foreach( explode(PHP_EOL, $part1) as $part2 ) {
            $rates[] = $part2;
          }
        }
      }
      $previous_weight = 0;
      
      $valid_rates = array(); // Storing applicable rates (for current country)
      foreach( $rates as $rate ) {
        $split = explode('|', $rate);
        
        // No country specific rate
        if ( count($split) == 2) {
          $weight = trim($split[0]);
          $price = trim($split[1]);
          $valid_rates[] = array( (float)$weight, (float)$price );
        // Rate for specific country
        } else if ( count($split) == 3 && $country != null ) {
          $rate_country = trim($split[0]);
          $weight = trim($split[1]);
          $price = trim($split[2]);
          if ( $rate_country == $country ) {
            $valid_rates[] = array( (float)$weight, (float)$price );
          }
        }
      }
      // Removing duplicates, keeping only latest defined rate
      $weights = array();
      foreach ( array_reverse($valid_rates) as $rate ) {
        if ( !in_array( $rate[0], $weights ) ) {
          $this->flat_rates[] = $rate;
        }
        $weights[] = $rate[0];
      }
      
      // Sorting by weight
      $weights = array();
      foreach($this->flat_rates as $key => $value){
        $weights[$key] = $value[1];
      }
      array_multisort($weights, SORT_ASC, SORT_NUMERIC, $this->flat_rates);
      
      // And finally, setting 'previous_weight' values to ease extraction
      $previous_weight = 0;
      foreach($this->flat_rates as $key => $value) {
        $this->flat_rates[$key] = array($previous_weight, $value[0], $value[1]);
        $previous_weight = $value[0];
      }
    }
}
