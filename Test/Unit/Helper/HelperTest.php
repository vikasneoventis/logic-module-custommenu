<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Helper;
use Logic\CustomMenu\Helper\MenuHelper;
use Logic\CustomMenu\Api\Data\MenuInterface;

class HelperTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Logic\CustomMenu\Helper\MenuHelper
     */
    protected $menuHelper;
    protected $menuHelperMock;

    protected $menuMock;
    protected $menuDataMock;
    protected $menuCollectionMock;

    protected $blockMock;
    protected $blockDataMock;
    protected $blockCollectionMock;

    protected function setUp()
    {
        // menu model mock 
        $menuFactoryMock = $this->getMockBuilder('logic\CustomMenu\Model\MenuFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $menuDataFactoryMock = $this->getMockBuilder('logic\CustomMenu\Api\Data\MenuInterfaceFactory')->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $menuCollectionFactoryMock = $this->getMockBuilder('logic\CustomMenu\Model\ResourceModel\Menu\CollectionFactory')->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $this->menuMock = $this->getMockBuilder('logic\CustomMenu\Model\Menu')->disableOriginalConstructor()->getMock();

        $this->menuDataMock = $this->getMockBuilder('logic\CustomMenu\Api\Data\MenuInterface')
            ->getMock();

        $this->menuCollectionMock = $this->getMockBuilder('logic\CustomMenu\Model\ResourceModel\Menu\Collection')
            ->disableOriginalConstructor()
            ->setMethods(['addFieldToFilter', 'getSize', 'setCurPage', 'setPageSize', 'load', 'addOrder'])
            ->getMock();

        $menuFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->menuMock);

        $menuDataFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->menuDataMock);

        $menuCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->menuCollectionMock);
        
        // cms block model mock
        $blockFactoryMock = $this->getMockBuilder('Magento\Cms\Model\BlockFactory')
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();

        $blockDataFactoryMock = $this->getMockBuilder('Magento\Cms\Api\Data\BlockInterfaceFactory')->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $blockCollectionFactoryMock = $this->getMockBuilder('Magento\Cms\Model\ResourceModel\Block\CollectionFactory')->disableOriginalConstructor()->setMethods(['create'])->getMock();

        $this->blockMock = $this->getMockBuilder('Magento\Cms\Model\Block')->disableOriginalConstructor()->getMock();

        $this->blockDataMock = $this->getMockBuilder('Magento\Cms\Api\Data\BlockInterface')
            ->getMock();

        $this->blockCollectionMock = $this->getMockBuilder('Magento\Cms\Model\ResourceModel\Block\Collection')
            ->disableOriginalConstructor()
            ->setMethods(['addFieldToFilter', 'getSize', 'setCurPage', 'setPageSize', 'load', 'addOrder'])
            ->getMock();
        //
        $blockFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->blockMock);

        $blockDataFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->blockDataMock);

        $blockCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->blockCollectionMock);

        // MenuHelper  
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $context = $objectManager->getObject('\Magento\Framework\App\Helper\Context'); 
        // $this->menuHelper = $objectManager->getObject(
        //     'Logic\CustomMenu\Helper\MenuHelper',
        //     [
        //         'context' => $context,
        //         'blockCollectionFactory' => $blockCollectionFactoryMock,
        //         'menuCollectionFactory' => $menuCollectionFactoryMock
        //     ]
        // );
        $this->menuHelperMock = $this->getMockBuilder('logic\CustomMenu\Helper\MenuHelper')
            ->disableOriginalConstructor()
            ->getMock();

        $this->menuHelper = new MenuHelper(
            $context ,
            $menuCollectionFactoryMock,
            $blockCollectionFactoryMock
        );

    }

    public function testExecute(){
        $blockIds=array(1,2,3,4,5);
        $menuIds=array(2,4,6);
        $this->menuHelper->execute($blockIds,$menuIds);
        $this->assertSame(array(2,4),$this->menuHelper->getMatchedIDs());

    }

    public function testGetMenuData(){
        
        $menu_id = 1;
        $identifier = 'someIdentifier';
        $order = 2;
        $column = 3;
        $layer = 4;
        $title = 'someTitle';
        $parent = 5;
        $active = true;

        $this->menuMock->setMenuId($menu_id);
        $this->menuMock->getIdentifier($identifier);
        $this->menuMock->getOrder($order);
        $this->menuMock->getColumn($column);
        $this->menuMock->getLayer($layer);
        $this->menuMock->getTitle($title);
        $this->menuMock->getParent($parent);
        $this->menuMock->getActive($active);
        //
        $this->menuMock->expects($this->any())->method('getMenuId')->willReturn($menu_id);
        $this->menuMock->expects($this->any())->method('getIdentifier')->willReturn($identifier);
        $this->menuMock->expects($this->any())->method('getOrder')->willReturn($order);
        $this->menuMock->expects($this->any())->method('getColumn')->willReturn($column);
        $this->menuMock->expects($this->any())->method('getLayer')->willReturn($layer);
        $this->menuMock->expects($this->any())->method('getTitle')->willReturn($title);
        $this->menuMock->expects($this->any())->method('getParent')->willReturn($parent);
        $this->menuMock->expects($this->any())->method('getActive')->willReturn($active);
        //
        $this->menuCollectionMock->addItem($this->menuMock);
        $this->menuCollectionMock->expects($this->any())
            ->method('addFieldToFilter')
            ->with(MenuInterface::MENU_ID, ['eq' => $menu_id])
            ->willReturnSelf();  
        $data = $this->menuHelper->getMenuData($menu_id,MenuInterface::TITLE,$this->menuCollectionMock);
        $this->assertSame($title,$data);

    }

}
