<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Controller\Index;

class IndexTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Logic\CustomMenu\Controller\Index\Index
     */
    protected $controller;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $requestMock;

    /**
     * @var \Magento\Framework\View\Result\Page|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $resultPageMock;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $responseMock = $this->getMock('Magento\Framework\App\Response\Http', [], [], '', false);
        $this->requestMock = $this->getMock('Magento\Framework\App\Request\Http', [], [], '', false);
        $context = $objectManager->getObject('\Magento\Framework\App\Action\Context');
        $this->resultPageFactory = $this->getMock('Magento\Framework\View\Result\PageFactory', [], [], '', false);
        $this->controller = $objectManager->getObject(
            'Logic\CustomMenu\Controller\Index\Index',
            [
                'context' => $context,
                'pageFactory' => $this->resultPageFactory,
                'response' => $responseMock,
                'request' => $this->requestMock
            ]
        );
    }

    public function testExecuteResultPage(){
        
        $this->assertSame($this->resultPageFactory->create(), $this->controller->execute());
    }

}
