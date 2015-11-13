<?php
 
namespace Logic\CustomMenu\Controller\Adminhtml\Settings;
 
use Logic\CustomMenu\Controller\Adminhtml\Settings;
 
class Delete extends Settings
{
   /**
    * @return void
    */
   public function execute()
   {
      /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->menuFactory->create();
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccess(__('You deleted the setting.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('We can\'t find a setting to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}