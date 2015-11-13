<?php
 
namespace Logic\CustomMenu\Block\Adminhtml\Menu\Edit;
 
use Magento\Backend\Block\Widget\Tabs as WidgetTabs;
 
class Tabs extends WidgetTabs
{
    /**
     * Class constructor
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('menu_edit_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Menu Information'));
    }
 
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'menu_info',
            [
                'label' => __('General'),
                'title' => __('General'),
                'content' => $this->getLayout()->createBlock(
                    'Logic\CustomMenu\Block\Adminhtml\Menu\Edit\Tab\Info'
                )->toHtml(),
                'active' => true
            ]
        );
        return parent::_beforeToHtml();
    }
}