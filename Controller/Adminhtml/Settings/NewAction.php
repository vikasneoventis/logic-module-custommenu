<?php
 
namespace Logic\CustomMenu\Controller\Adminhtml\Settings;
 
use Logic\CustomMenu\Controller\Adminhtml\Settings;

class NewAction extends Settings
{
   /**
     * Create new menu action
     *
     * @return void
     */
   public function execute()
   {
      $this->_forward('edit');
   }
}
 