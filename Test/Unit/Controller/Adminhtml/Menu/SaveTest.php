<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Controller\Adminhtml\Menu;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
use Logic\CustomMenu\Controller\Adminhtml\Menu\Save;

class SaveTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Framework\App\RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Backend\Model\View\Result\RedirectFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirectFactory;

    /**
     * @var \Magento\Backend\Model\View\Result\Redirect|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultRedirect;

    /**
     * @var \Magento\Backend\App\Action\Context|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $contextMock;

    /**
     * @var \Magento\Framework\ObjectManager\ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManagerMock;

    /**
     * @var \Magento\Backend\Model\Session|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $sessionMock;

    /**
     * @var \Logic\CustomMenu\Model\Menu|\PHPUnit_Framework_MockObject_MockObject $menuMock
     */
    protected $menuMock;

    /**
     * @var \Magento\Framework\Message\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $messageManagerMock;

    /**
     * @var \Magento\Framework\Event\ManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManagerMock;

    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \Logic\CustomMenu\Controller\Adminhtml\Menu\Save
     */
    protected $saveController;

    /**
     * @var int
     */
    protected $id = 1;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->contextMock = $this->getMock('Magento\Backend\App\Action\Context', [], [], '', false);

        $this->resultRedirectFactory = $this->getMockBuilder('Magento\Backend\Model\View\Result\RedirectFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->resultRedirect = $this->getMockBuilder('Magento\Backend\Model\View\Result\Redirect')
            ->disableOriginalConstructor()
            ->getMock();
        $this->resultRedirectFactory->expects($this->atLeastOnce())
            ->method('create')
            ->willReturn($this->resultRedirect);

        $this->sessionMock = $this->getMock(
            'Magento\Backend\Model\Session',
            ['setFormData'],
            [],
            '',
            false
        );

        $this->requestMock = $this->getMockForAbstractClass(
            'Magento\Framework\App\RequestInterface',
            [],
            '',
            false,
            true,
            true,
            ['getParam', 'getPostValue']
        );

        $this->menuMock = $this->getMockBuilder('Logic\CustomMenu\Model\Menu')->disableOriginalConstructor()->getMock();
        $this->menuFactoryMock = $this->getMockBuilder('Logic\CustomMenu\Model\MenuFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->menuFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->menuMock); 

        $this->messageManagerMock = $this->getMock('Magento\Framework\Message\ManagerInterface', [], [], '', false);

        $this->eventManagerMock = $this->getMockForAbstractClass(
            'Magento\Framework\Event\ManagerInterface',
            [],
            '',
            false,
            true,
            true,
            ['dispatch']
        );

        $this->objectManagerMock = $this->getMockBuilder('Magento\Framework\ObjectManager\ObjectManager')
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();

        $this->contextMock->expects($this->any())->method('getRequest')->willReturn($this->requestMock);
        $this->contextMock->expects($this->any())->method('getObjectManager')->willReturn($this->objectManagerMock);
        $this->contextMock->expects($this->any())->method('getMessageManager')->willReturn($this->messageManagerMock);
        $this->contextMock->expects($this->any())->method('getEventManager')->willReturn($this->eventManagerMock);
        $this->contextMock->expects($this->any())
            ->method('getResultRedirectFactory')
            ->willReturn($this->resultRedirectFactory);

        $coreRegistryMock = $this->getMock('\Magento\Framework\Registry', [], [], '', false);
        $resultPageFactoryMock = $this->getMockBuilder('Magento\Framework\View\Result\PageFactory')
            ->disableOriginalConstructor()
            ->getMock();

        $this->saveController = new Save(
            $this->contextMock,
            $coreRegistryMock,
            $resultPageFactoryMock,
            $this->menuFactoryMock
        );
    }

    public function testSaveAction()
    {
        $postData = [
            'title' => '"><img src=y onerror=prompt(document.domain)>;',
            'identifier' => 'unique_title_123',
            'is_active' => '1'
        ];

        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['menu',null, $postData],
                    ['id', null, 1],
                    ['back', null, false]
                ]
            );
        $this->objectManagerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnMap(
                [
                    ['Magento\Backend\Model\Session', $this->sessionMock],
                ]
            );

        $this->menuMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->menuMock->expects($this->any())
            ->method('getId')
            ->willReturn(true);
        $this->menuMock->expects($this->once())->method('setData');
        $this->menuMock->expects($this->once())->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->with(__('You saved the menu.'));

        $this->sessionMock->expects($this->atLeastOnce())->method('setFormData')->with(false);

        $this->resultRedirect->expects($this->atLeastOnce())->method('setPath')->with('*/*/') ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->saveController->execute());
    }

    public function testSaveActionWithoutData()
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['menu',null, false]
                ]
            );
        $this->resultRedirect->expects($this->atLeastOnce())->method('setPath')->with('*/*/') ->willReturnSelf();
        $this->assertSame($this->resultRedirect, $this->saveController->execute());
    }

    public function testSaveActionNoId()
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['menu',null, true],
                    ['id', null, 1],
                    ['back', null, false],
                ]
            );
        $this->menuMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->menuMock->expects($this->any())
            ->method('getId')
            ->willReturn(false);

        $this->messageManagerMock->expects($this->once())
            ->method('addError')
            ->with(__('This menu no longer exists.'));

        $this->resultRedirect->expects($this->atLeastOnce())->method('setPath')->with('*/*/') ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->saveController->execute());
    }

    public function testSaveAndContinue()
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['menu',null, true],
                    ['id', null, 1],
                    ['back', null, true]
                ]
            );

        $this->objectManagerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnMap(
                [
                    ['Magento\Backend\Model\Session', $this->sessionMock],
                ]
            );

        $this->menuMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->menuMock->expects($this->any())
            ->method('getId')
            ->willReturn(true);
        $this->menuMock->expects($this->once())->method('setData');
        $this->menuMock->expects($this->once())->method('save');

        $this->messageManagerMock->expects($this->once())
            ->method('addSuccess')
            ->with(__('You saved the menu.'));

        $this->sessionMock->expects($this->atLeastOnce())->method('setFormData')->with(false);

        $this->resultRedirect->expects($this->atLeastOnce())
            ->method('setPath')
            ->with('*/*/edit', ['id' => $this->id])
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->saveController->execute());
    }

    public function testSaveActionThrowsException()
    {
        $this->requestMock->expects($this->atLeastOnce())
            ->method('getParam')
            ->willReturnMap(
                [
                    ['menu',null, true],
                    ['id', null, 1],
                    ['back', null, true]
                ]
            );

        $this->objectManagerMock->expects($this->atLeastOnce())
            ->method('get')
            ->willReturnMap(
                [
                    ['Magento\Backend\Model\Session', $this->sessionMock],
                ]
            );

        $this->menuMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->menuMock->expects($this->any())
            ->method('getId')
            ->willReturn(true);
        $this->menuMock->expects($this->once())->method('setData');
        $this->menuMock->expects($this->once())->method('save')->willThrowException(new \Exception('Error message.'));

        $this->messageManagerMock->expects($this->any())
            ->method('addSuccess')
            ->with(__('You saved the menu.'));
        $this->messageManagerMock->expects($this->once())
            ->method('addError');

        $this->sessionMock->expects($this->atLeastOnce())->method('setFormData')->with(true);

        $this->resultRedirect->expects($this->atLeastOnce())
            ->method('setPath')
            ->with('*/*/edit', ['id' => $this->id])
            ->willReturnSelf();

        $this->assertSame($this->resultRedirect, $this->saveController->execute());
    }
}
