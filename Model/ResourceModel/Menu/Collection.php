<?php
 
namespace Logic\CustomMenu\Model\ResourceModel\Menu;
 
use \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Logic\CustomMenu\Model\Menu',
            'Logic\CustomMenu\Model\ResourceModel\Menu'
        );
    }
}