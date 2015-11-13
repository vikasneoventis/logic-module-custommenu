<?php
 
namespace Logic\CustomMenu\Controller\Adminhtml\Menu;

use Logic\CustomMenu\Controller\Adminhtml\Menu;
 
class Edit extends Menu
{

   public function execute()
   {
        $menuId = $this->getRequest()->getParam('id');
        /** @var \Logic\CustomMenu\Model\MenuFactory $model */
        $model = $this->menuFactory->create();
        //
        if ($menuId) {
            $model->load($menuId);
            if (!$model->getId()) {
                $this->messageManager->addError(__('This menu no longer exists.'));
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
        $this->coreRegistry->register('logic_menu_admin', $model);
        //
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultPageFactory->create();
        $resultPage->setActiveMenu('Logic_CustomMenu::menu_block')->addBreadcrumb(
            $menuId ? __('Edit Menu') : __('New Menu'),
            $menuId ? __('Edit Menu') : __('New Menu'));
        $resultPage->getConfig()->getTitle()->prepend($model->getId() ? $model->getTitle() : __('New Menu'));
 
        return $resultPage;
   }
}