<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Block\Adminhtml\Menu;

/**
 * @covers \Logic\CustomMenu\Block\Adminhtml\Menu\Edit
 */
class EditTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Logic\CustomMenu\Block\Adminhtml\Menu\Edit
     */
    protected $edit;

    /**
     * @var \Magento\Framework\Registry|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $registryMock;

    /**
     * @var \Magento\Framework\Escaper|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $escaperMock;

    /**
     * @var \Logic\CustomMenu\Model\Block|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $modelBlockMock;

    protected function setUp()
    {
        $this->registryMock = $this->getMockBuilder('Magento\Framework\Registry')
            ->disableOriginalConstructor()
            ->getMock();
        $this->escaperMock = $this->getMockBuilder('Magento\Framework\Escaper')
            ->disableOriginalConstructor()
            ->getMock();
        $this->modelBlockMock = $this->getMockBuilder('Logic\CustomMenu\Model\Menu')
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getId',
                    'getTitle',
                ]
            )
            ->getMock();

        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->edit = $objectManager->getObject(
            'Logic\CustomMenu\Block\Adminhtml\Menu\Edit',
            [
                'registry' => $this->registryMock,
                'escaper' => $this->escaperMock,
            ]
        );
    }

    /**
     * @covers \Logic\CustomMenu\Block\Adminhtml\Menu\Edit::getHeaderText
     * @param integer|null $modelBlockId
     *
     * @dataProvider getHeaderTextDataProvider
     */
    public function testGetHeaderText($modelBlockId)
    {
        $title = 'some title';
        $escapedTitle = 'escaped title';

        $this->registryMock->expects($this->atLeastOnce())
            ->method('registry')
            ->with('logic_menu_admin')
            ->willReturn($this->modelBlockMock);
        $this->modelBlockMock->expects($this->atLeastOnce())
            ->method('getId')
            ->willReturn($modelBlockId);
        $this->modelBlockMock->expects($this->any())
            ->method('getTitle')
            ->willReturn($title);
        $this->escaperMock->expects($this->any())
            ->method('escapeHtml')
            ->with($title)
            ->willReturn($escapedTitle);

        $this->assertInstanceOf('Magento\Framework\Phrase', $this->edit->getHeaderText());

    }

    public function getHeaderTextDataProvider()
    {
        return [
            'modelBlockId NOT EMPTY' => ['modelBlockId' => 1],
            'modelBlockId IS EMPTY' => ['modelBlockId' => null],
        ];
    }
    
}
