<?php
namespace Logic\CustomMenu\Controller\Adminhtml\Settings;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Logic\CustomMenu\Controller\Adminhtml\Settings;

class Index extends Settings
{
     public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Forward $resultForward */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logic_CustomMenu::menu_settings');
        $resultPage->addBreadcrumb(__('Logic'), __('Logic'));
        $resultPage->addBreadcrumb(__('Manage Menu'), __('Manage Menu'));
        $resultPage->getConfig()->getTitle()->prepend(__('Logic Menu Settings'));

        return $resultPage;
    }
}
