<?php
 
namespace Logic\CustomMenu\Block\Adminhtml\Settings\Edit;
 
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
        $this->setId('settings_edit_tabs');
        $this->setDestElementId('settings_form');
        $this->setTitle(__('Menu Settings'));
    }
 
    /**
     * @return $this
     */
    protected function _beforeToHtml()
    {
        $this->addTab(
            'settings_color',
            [
                'label' => __('Settings'),
                'title' => __('Settings'),
                'content' => $this->getLayout()->createBlock(
                    'Logic\CustomMenu\Block\Adminhtml\Settings\Edit\Tab\Color'
                )->toHtml(),
                'active' => true
            ]
        );
        return parent::_beforeToHtml();
    }

}