<?php
 
namespace Logic\CustomMenu\Model;
 
use Magento\Framework\Model\AbstractModel;
use Logic\CustomMenu\Api\Data\MenuInterface;
use Magento\Framework\DataObject\IdentityInterface;
 
class Menu extends AbstractModel implements MenuInterface, IdentityInterface
{
    const CACHE_TAG = 'logic_model_menu';
    /**
     * Define resource model
     */
    protected function _construct(){

        $this->_init('Logic\CustomMenu\Model\Resource\Menu');
    }

    public function getIdentities(){

        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getMenuId(){

    	return $this->getData(self::MENU_ID);
    }

    public function getIdentifier(){

        return $this->getData(self::IDENTIFIER);

    }

    public function getOrder(){

    	return $this->getData(self::ORDER);

    }

    public function getColumn(){

    	return $this->getData(self::COLUMN);

    }
    public function getLayer(){

    	return $this->getData(self::LAYER);

    }
    public function getTitle(){

        return $this->getData(self::TITLE);
    }
    public function getParent(){

        return $this->getData(self::MENU_PARENT);

    }
    public function getCreationTime(){

        return $this->getData(self::CREATION_TIME);

    }
    public function getUpdateTime(){

        return $this->getData(self::UPDATE_TIME);
  
    }
    public function isActive()
    {
        return (bool) $this->getData(self::IS_ACTIVE);
    }
    
    public function setMenuId($menu_id){

    	return $this->setData(self::MENU_ID, $menu_id);

    }

    public function setIdentifier($identifier){

    	return $this->setData(self::IDENTIFIER, $identifier);

    }

    public function setOrder($order){

    	return $this->setData(self::ORDER, $order);

    }

    public function setColumn($column){

		return $this->setData(self::COLUMN, $column);

    }

    public function setLayer($layer){

    	return $this->setData(self::LAYER, $layer);

    }
    public function setTitle($title){

        return $this->setData(self::TITLE, $title);

    }
    public function setParent($parent){

        return $this->setData(self::MENU_PARENT, $parent);

    }
    public function setCreationTime($creationTime){

        return $this->setData(self::CREATION_TIME, $creationTime);

    }
    public function setUpdateTime($updateTime){

        return $this->setData(self::UPDATE_TIME, $updateTime);

    }
    public function setIsActive($is_active)
    {
        
        return $this->setData(self::IS_ACTIVE, $is_active);
        
    }
    
}