<?php
 
namespace Logic\CustomMenu\Model\Resource;
 
use \Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Settings extends AbstractDb
{
    protected $_date;
    public function __construct(
        \Magento\Framework\Model\Resource\Db\Context $context,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        $resourcePrefix = null
    ) {
        parent::__construct($context, $resourcePrefix);
        $this->_date = $date;
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