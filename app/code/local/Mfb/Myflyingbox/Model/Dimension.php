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
 * Dimension model
 *
 * @category    Mfb
 * @package     Mfb_Myflyingbox
 * @author      Ultimate Module Creator
 */
class Mfb_Myflyingbox_Model_Dimension extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'mfb_myflyingbox_dimension';
    const CACHE_TAG = 'mfb_myflyingbox_dimension';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'mfb_myflyingbox_dimension';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'dimension';


    public static $defaults = array(
      1 => array(1, 15), // = Up to 1kg: 15x15x15cm
      2 => array(2, 18),
      3 => array(3, 20),
      4 => array(4, 22),
      5 => array(5, 25),
      6 => array(6, 28),
      7 => array(7, 30),
      8 => array(8, 32),
      9 => array(9, 35),
      10 => array(10, 38),
      11 => array(15, 45),
      12 => array(20, 50),
      13 => array(30, 55),
      14 => array(40, 59),
      15 => array(50, 63)
    );

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
        $this->_init('mfb_myflyingbox/dimension');
    }

    /**
     * before save dimension
     *
     * @access protected
     * @return Mfb_Myflyingbox_Model_Dimension
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
     * save dimension relation
     *
     * @access public
     * @return Mfb_Myflyingbox_Model_Dimension
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

    public function get_all() 
    {
      return $this->getCollection()
                         ->loadData()
                         ->setOrder("weight_to", "ASC")
                         ->getData();
    }
    
    public function getForWeight($weight)
    {
      $dim = $this->getCollection()
                  ->addFieldToFilter("weight_to", array('gteq' => $weight))
                  ->addFieldToFilter("weight_from", array('lt' => $weight))
                  ->setOrder("weight_to", "ASC")
                  ->setPageSize(1)
                  ->loadData()
                  ->getData();
                  
      return $this->load($dim[0]['entity_id']);
    }
    
    public function load_defaults() {
      $table_name = Mage::getSingleton('core/resource')->getTableName('mfb_myflyingbox/dimension');
      $write = Mage::getSingleton('core/resource')->getConnection('core_write'); 
      
      $write->query("TRUNCATE TABLE $table_name");
      
      $from = 0;
      foreach($this::$defaults as $dimension)
      {
        $data = array(
          "weight_from" => $from,
          "weight_to" => $dimension[0], 
          "length" => $dimension[1],
          "width" => $dimension[1],
          "height" => $dimension[1]
        );
        $new_dim = Mage::getModel("mfb_myflyingbox/dimension");
        
        $new_dim->addData($data)
                ->save();
              
        $from = $dimension[0];
      }
    }

}
