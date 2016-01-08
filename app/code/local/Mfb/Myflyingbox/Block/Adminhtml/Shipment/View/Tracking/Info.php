<?php

class Mfb_Myflyingbox_Block_Adminhtml_Shipment_View_Tracking_Info extends Mage_Adminhtml_Block_Template
{
    protected function _prepareLayout()
    {        
        return parent::_prepareLayout();
    }

    public function getParcel()
    {
        return Mage::registry('current_parcel');
    }
    
    public function trackingData(){
      $parcel = $this->getParcel();
      $tracking_data = $parcel->getApiTrackingData();
      return $tracking_data;
    }

    public function formatTime($time) {
      return $time;
      $date = date_create_from_format(DateTime::RFC3339, $time);
      return date_format($date, "Y-m-d H:i");
    }

    public function formatTrackingLocation($location)
    {
      $res = '';
      if (!empty($location->name))
        $res .= $location->name;
      
      // We consider that state and postal codes are only used if a city is actually specified
      if (!empty($location->city)) {
        $city = '';
        $city .= $location->postal_code;
        if (!empty($location->postal_code))
          $city .= ' ';
          
        $city .= $location->city;
        
        if (!empty($location->state))
          $city .= ", ".$location->state;
      }
      if (!empty($city)) {
        if (!empty($res))
          $res .= ' ('.$city.')';
        else
          $res .= $city;
      }
      
      if (!empty($location->country)) {
        $res .= ' - '.$location->country;
      }
      return $res;
    }

    function getShipment() {
      return $this->getParcel()->getParentShipment();
    }

}
