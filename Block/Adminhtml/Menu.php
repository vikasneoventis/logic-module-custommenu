<?php
 
namespace Logic\CustomMenu\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Menu extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_menu';
        $this->_blockGroup = 'Logic_CustomMenu';
        $this->_headerText = __('Logic Custom Menu');
        $this->_addButtonLabel = __('Add Menu');
        parent::_construct();
    }
}