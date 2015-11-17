<?php
namespace Logic\CustomMenu\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Logic\CustomMenu\Model\MenuFactory;
use Magento\Framework\Registry;

abstract class Menu extends \Magento\Backend\App\Action
{
    /**
     * @var PageFactory
     */
    protected $_objectManager;
    protected $resultPageFactory;
    protected $menuFactory;
    protected $coreRegistry;
    /**
     * @param Context $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        Registry $_coreRegistry,
        PageFactory $_resultPageFactory,
        MenuFactory $_menuFactory
    ){
        parent::__construct($context);
        $this->resultPageFactory = $_resultPageFactory;
        $this->coreRegistry = $_coreRegistry;
        $this->menuFactory = $_menuFactory;
    }
}
