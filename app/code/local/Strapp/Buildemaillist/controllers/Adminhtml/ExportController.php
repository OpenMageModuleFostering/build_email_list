<?php
/**
* Strapp Build email list Module
*
* @category    Strapp
* @package     Strapp_Buildemaillist
*/
class Strapp_Buildemaillist_Adminhtml_ExportController extends Mage_Adminhtml_Controller_Action
{
  public function indexAction()
  {
    $file = Mage::getModel('strapp_buildemaillist/export_csv')->exportSubscribers();
    $this->_prepareDownloadResponse($file, file_get_contents(Mage::getBaseDir('var').'/export/'.$file));
  }
}