<?php
class Mfb_Myflyingbox_Model_Adminhtml_Fieldtype_Environment
{
 
  protected $_api_hosts = array(
    "staging" => array("title" => "TEST", "url" => "https://test.myflyingbox.com/"),
    "production" => array("title" => "PRODUCTION", "url" => "https://api.myflyingbox.com/")
  );

  public function toOptionArray() 
  {
    $hosts = array();
    foreach($this->_api_hosts as $h => $host)
    {
      $hosts[$h] = $host['title'];
    }
    return $hosts;
  }

  public function getHost($key)
  {
    return $this->_api_hosts[$key]['url'];
  }
}
?>
