<?php
 
namespace Logic\CustomMenu\Controller\Adminhtml\Settings;

use Logic\CustomMenu\Controller\Adminhtml\Settings;
 
class Edit extends Settings
{

   public function execute()
   {
        $settingsId = $this->getRequest()->getParam('id');
        /** @var \Logic\CustomMenu\Model\MenuFactory $model */
        $model = $this->settingsFactory->create();
        //
        if ($settingsId) {
            $model->load($settingsId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This setting no longer exists.'));
                /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
        }
        //
        $data = $this->_objectManager->get('Magento\Backend\Model\Session')->getFormData(true);
        if (!empty($data)) {
            $model->setData($data);
        }
        //
        $this->coreRegistry->register('logic_menu_settings_admin', $model);
        //
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logic_CustomMenu::menu_settings')->addBreadcrumb(__('Edit Settings'),__('Edit Settings'));
        $resultPage->getConfig()->getTitle()->prepend(__('Menu Settings'));
 
        return $resultPage;
   }

}