<?php
 
namespace Logic\CustomMenu\Model\Resource;
 
use Magento\Framework\Model\Resource\Db\AbstractDb;
 
class Settings extends AbstractDb
{
    public function __construct(
        \Magento\Framework\Model\Resource\Db\Context $context,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
    }
    /**
     * Define main table
     */
    protected function _construct()
    {
        $this->_init('logic_menu_style', 'id');
    }
    
    protected function _beforeSave(\Magento\Framework\Model\AbstractModel $object)
    {

        if (!$object->getId()) {           
            $object->setCreationTime($this->_date->gmtDate());
        }
        $object->setUpdateTime($this->_date->gmtDate());
        return $this;
    }

}