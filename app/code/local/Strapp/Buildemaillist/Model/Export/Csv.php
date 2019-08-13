<?php
/**
* Strapp Build email list Module
*
* @category    Strapp
* @package     Strapp_Buildemaillist
*/
class Strapp_Buildemaillist_Model_Export_Csv extends Mage_Core_Model_Abstract
{
  const ENCLOSURE = '"';
  const DELIMITER = ",";
            
  public function exportSubscribers()
  {	
    $fileName = 'email_list_'.date("Ymd_His").'.csv';        
    $fp = fopen(Mage::getBaseDir('var').'/export/'.$fileName, 'w');                       
    $this->writeHeadRow($fp);   
    $customerArray = array();
    $customerCollection = Mage::getModel('customer/customer')->getCollection()
	->addAttributeToSelect('firstname')
	->addAttributeToSelect('lastname')
	->addAttributeToSelect('email');
	
    //For getting customers collection	
    foreach($customerCollection as $customer)
    {
      array_push($customerArray,$customer->getEmail());
      $record = array($customer->getFirstname(),$customer->getLastname(),$customer->getEmail());
      fputcsv($fp,$record, self::DELIMITER, self::ENCLOSURE);
    }

    //For getting subscriber collection as a guest 
    $subscriberArray = array();
    $newsletterSubscribers = Mage::getModel('newsletter/subscriber')->getCollection()->addFieldToFilter('customer_id','0')->addFieldToSelect('subscriber_email');
    foreach($newsletterSubscribers as $subscriber)
    {	 
      array_push($subscriberArray,$subscriber->getEmail());
    }
        
    //For removing duplicate entry from customer array    
    $finalSubsciberArray = array_diff($subscriberArray,$customerArray);
	
    foreach($finalSubsciberArray as $key => $value)
    {
      $record = array('','',$value);
      fputcsv($fp,$record, self::DELIMITER, self::ENCLOSURE);
    }
    fclose($fp);	
    return $fileName;
  }
 
  protected function writeHeadRow($fp)
  {
    fputcsv($fp, $this->getHeadRowValues(), self::DELIMITER, self::ENCLOSURE);
  }
    
  protected function getHeadRowValues()
  {
    return array('First Name','Last Name','Email Id');
  }         
}
?>