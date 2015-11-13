<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Controller\Adminhtml\Menu;
use Logic\CustomMenu\Controller\Adminhtml\Menu\Edit;
class EditTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \\Logic\CustomMenu\Controller\Adminhtml\Menu\Edit
     */
    protected $editController;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectFactoryMock;

    /**
     * @var \Magento\Backend\Model\View\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \\Logic\CustomMenu\Model\Menu|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $menuMock;

    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $coreRegistryMock;

    /**
     * @var \Magento\Framework\View\Result\PageFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultPageFactoryMock;

    protected $menuFactoryMock;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->messageManagerMock = $this->getMock('Magento\Framework\Message\ManagerInterface', [], [], '', false);
        $this->coreRegistryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);

        $this->menuMock = $this->getMockBuilder('Logic\CustomMenu\Model\Menu')
            ->disableOriginalConstructor()
            ->getMock();

        $this->menuFactoryMock = $this->getMockBuilder('Logic\CustomMenu\Model\MenuFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();    

        $this->objectManagerMock = $this->getMockBuilder('Magento\Framework\ObjectManager\ObjectManager')
            ->setMethods(['get'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->menuFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($this->menuMock); 

        $this->resultRedirectMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\Redirect')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultRedirectFactoryMock = $this->getMockBuilder('Magento\Backend\Model\View\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->resultPageFactoryMock = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockForAbstractClass(
            'Magento\Framework\App\RequestInterface',
            [],
            '',
            false,
            true,
            true,
            []
        );

        $this->contextMock = $this->getMock(
            '\Magento\Backend\App\Action\Context',
            [],
            [],
            '',
            false
        );
        $this->contextMock->expects($this->once())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->once())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->once())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->once())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactoryMock);

        $this->editController = new Edit(
            $this->contextMock,
            $this->coreRegistryMock,
            $this->resultPageFactoryMock,
            $this->menuFactoryMock
        );
    }

    public function testEditActionBlockNoExists()
    {
        $id = 1;

        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($id);

        $this->menuMock->expects($this->once())
            ->method('load')
            ->with($id);
        $this->menuMock->expects($this->once())
            ->method('getId')
            ->willReturn(null);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with(__('This menu no longer exists.'));

        $this->resultRedirectFactoryMock->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->resultRedirectMock);

        $this->resultRedirectMock->expects($this->once())
            ->method('setPath')
            ->with('*/*/')
            ->willReturnSelf();

        $this->assertSame($this->resultRedirectMock, $this->editController->execute());
    }

    /**
     * @param int $id
     * @param string $label
     * @param string $title
     * @dataProvider editActionData
     */
    public function testEditAction($id, $label, $title)
    {
        $this->requestMock->expects($this->once())
            ->method('getParam')
            ->with('id')
            ->willReturn($id);

        $this->menuMock->expects($this->any())
            ->method('load')
            ->with($id);
        $this->menuMock->expects($this->any())
            ->method('getId')
            ->willReturn($id);
        $this->menuMock->expects($this->any())
            ->method('getTitle')
            ->willReturn('Test title');

        $sessionManagerMock = $this->getMock('Magento\Backend\Model\Session', ['getFormData'], [], '', false);
        $this->objectManagerMock->expects($this->once())
            ->method('get')
            ->with('Magento\Backend\Model\Session')
            ->willReturn($sessionManagerMock);

        $sessionManagerMock->expects($this->once())
            ->method('getFormData')
            ->with(true);

        $this->coreRegistryMock->expects($this->once())
            ->method('register')
            ->with('logic_menu_admin', $this->menuMock);

        $resultPageMock = $this->getMock('Magento\Backend\Model\View\Result\Page', [], [], '', false);

        $this->resultPageFactoryMock->expects($this->once())
            ->method('create')
            ->willReturn($resultPageMock);

        $titleMock = $this->getMock('Magento\Framework\View\Page\Title', [], [], '', false);
        $titleMock->expects($this->once())->method('prepend')->with($this->getTitle());
        $pageConfigMock = $this->getMock('Magento\Framework\View\Page\Config', [], [], '', false);
        $pageConfigMock->expects($this->any())->method('getTitle')->willReturn($titleMock);
        $resultPageMock->expects($this->once())
            ->method('setActiveMenu')
            ->willReturnSelf();
        $resultPageMock->expects($this->once())
            ->method('addBreadcrumb')
            ->willReturnSelf();
        $resultPageMock->expects($this->once())
            ->method('getConfig')
            ->willReturn($pageConfigMock);

        $this->assertSame($resultPageMock, $this->editController->execute());
    }

    /**
     * @return \Magento\Framework\Phrase|string
     */
    protected function getTitle()
    {
        return $this->menuMock->getId() ? $this->menuMock->getTitle() : __('New Menu');
    }

    /**
     * @return array
     */
    public function editActionData()
    {
        return [
            [null, 'New Menu', 'New Menu'],
            [2, 'Edit Menu', 'Edit Menu']
        ];
    }
}
