<?php
 
namespace Logic\CustomMenu\Controller\Adminhtml\Menu;
 
use Logic\CustomMenu\Controller\Adminhtml\Menu;

class NewAction extends Menu
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
 