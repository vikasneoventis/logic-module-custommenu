<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Block;

class MenuBlockTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Magento\Cms\menu\menu
     */
    protected $menu;

    protected function setUp()
    {
        // file_put_contents('php://stderr', print_r(PHP_EOL.'--Block:setUp()--'.date("Y-m-d H:i:s").PHP_EOL, TRUE));
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->menu = $objectManager->getObject('Logic\CustomMenu\Block\Menu');
    }

    protected function tearDown()
    {
        // file_put_contents('php://stderr', print_r(PHP_EOL.'--Block:tearDown()--'.date("Y-m-d H:i:s").PHP_EOL, TRUE));
        $this->menu = null;
    }

    public function testGetIdentities()
    {   
        // file_put_contents('php://stderr', print_r(PHP_EOL.'--Block:testGetIdentities()--'.date("Y-m-d H:i:s").PHP_EOL, TRUE));
        $this->assertEquals([\Logic\CustomMenu\Block\Menu::CACHE_TAG . '_test'], $this->menu->getIdentities());
    }
}
