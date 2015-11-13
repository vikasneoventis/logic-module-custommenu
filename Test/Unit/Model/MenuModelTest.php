<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Modelss;

class MenuModelTest extends \PHPUnit_Framework_TestCase
{
    protected $model;
    protected $date;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->model = $objectManager->getObject('Logic\CustomMenu\Model\Menu');
        $this->date = $objectManager->getObject('Magento\Framework\Stdlib\DateTime\DateTime');
    }

    protected function tearDown()
    {
        // file_put_contents('php://stderr', print_r(PHP_EOL.'--Block:tearDown()--'.date("Y-m-d H:i:s").PHP_EOL, TRUE));
        $this->model = null;
        $this->date = null;
    }

    public function testMenuId(){
        $id = 1;
        $this->model->setMenuId($id);
        $this->assertSame( $id,$this->model->getMenuId());
    }
    public function testIdentifier(){
        $identifier = 'testIdentifier';
        $this->model->setIdentifier($identifier);
        $this->assertSame( $identifier,$this->model->getIdentifier());
    }
    public function testOrder(){
        $Order = 1;
        $this->model->setOrder($Order);
        $this->assertSame( $Order,$this->model->getOrder());
    }
    public function testColumn(){
        $Column = 1;
        $this->model->setColumn($Column);
        $this->assertSame( $Column,$this->model->getColumn());
    }
    public function testLayer(){
        $Layer = 1;
        $this->model->setLayer($Layer);
        $this->assertSame( $Layer,$this->model->getLayer());
    }
    public function testTitle(){
        $Title = 'testTitle';
        $this->model->setTitle($Title);
        $this->assertSame( $Title,$this->model->getTitle());
    }
    public function testParent(){
        $Parent = 1;
        $this->model->setParent($Parent);
        $this->assertSame( $Parent,$this->model->getParent());
    }
    public function testCreationTime(){
        $CreationTime = $this->date->gmtDate();
        $this->model->setCreationTime($CreationTime);
        $this->assertSame( $CreationTime,$this->model->getCreationTime());
    }
    public function testUpdateTime(){
        $UpdateTime = $this->date->gmtDate();
        $this->model->setUpdateTime($UpdateTime);
        $this->assertSame( $UpdateTime,$this->model->getUpdateTime());
    }
    public function testIsActive(){
        $isActive = (bool)1;
        $this->model->setIsActive($isActive);
        $this->assertSame( $isActive, $this->model->isActive());
    }


}
