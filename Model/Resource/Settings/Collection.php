<?php
 
namespace Logic\CustomMenu\Model\Resource\Settings;
 
use Magento\Framework\Model\Resource\Db\Collection\AbstractCollection;
 
class Collection extends AbstractCollection
{
    /**
     * Define model & resource model
     */
    protected function _construct()
    {
        $this->_init(
            'Logic\CustomMenu\Model\Settings',
            'Logic\CustomMenu\Model\Resource\Settings'
        );
    }
}