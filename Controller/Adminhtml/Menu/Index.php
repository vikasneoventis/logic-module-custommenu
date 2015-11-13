<?php
namespace Logic\CustomMenu\Controller\Adminhtml\Menu;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Logic\CustomMenu\Controller\Adminhtml\Menu;

class Index extends Menu
{
    /**
     * Index action
     *
     * @return void
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logic_CustomMenu::menu_block');
        $resultPage->addBreadcrumb(__('Logic'), __('Logic'));
        $resultPage->addBreadcrumb(__('Manage Menu'), __('Manage Menu'));
        $resultPage->getConfig()->getTitle()->prepend(__('Logic Menu'));

        return $resultPage;
    }
}
