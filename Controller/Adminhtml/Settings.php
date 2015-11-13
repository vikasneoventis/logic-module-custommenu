<?php
namespace Logic\CustomMenu\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Logic\CustomMenu\Model\SettingsFactory;
use Magento\Framework\Registry;
use Magento\Backend\Model\View\Result\ForwardFactory;

class Settings extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $resultForwardFactory;
    protected $resultPageFactory;
    protected $settingsFactory;
    protected $coreRegistry;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $_coreRegistry,
        PageFactory $_resultPageFactory,
        SettingsFactory $_settingsFactory,
        ForwardFactory $_resultForwardFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $_resultPageFactory;
        $this->coreRegistry = $_coreRegistry;
        $this->settingsFactory = $_settingsFactory;
        $this->resultForwardFactory = $_resultForwardFactory;
    }
}
