<?php
 
namespace Logic\CustomMenu\Block\Adminhtml;
 
use Magento\Backend\Block\Widget\Grid\Container;
 
class Settings extends Container
{
   /**
     * Constructor
     *
     * @return void
     */
   protected function _construct()
    {
        $this->_controller = 'adminhtml_settings';
        $this->_blockGroup = 'Logic_CustomMenu';
        $this->_headerText = __('Logic Custom Menu Settings');
        $this->_addButtonLabel = __('Add Settings');
        parent::_construct();
    }
}