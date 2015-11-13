<?php
 
namespace Logic\CustomMenu\Model;
 
use Magento\Framework\Model\AbstractModel;
use Logic\CustomMenu\Api\Data\MenuStyleInterface;
use Magento\Framework\Object\IdentityInterface;
 
class Settings extends AbstractModel implements MenuStyleInterface, IdentityInterface
{
    const CACHE_TAG = 'logic_model_menu_setting';
    /**
     * Define resource model
     */
    protected function _construct(){

        $this->_init('Logic\CustomMenu\Model\Resource\Settings');
    }

    public function getIdentities(){

        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getBackGroundColor(){
        $data = $this->getData(self::CLR_BACK);
        if(!empty($data))
            return $data;
        else
            return '#ffffff';
    }   
    public function getBackGroundColorHover(){
        $data = $this->getData(self::CLR_BACK_HOV);
        if(!empty($data))
            return $data;
        else
            return '#ffffff';
    }
    public function getTextColor(){
        $data = $this->getData(self::CLR_TXT);
        if(!empty($data))
            return $data;
        else
            return '#333';
    }
    public function getTextColorHover(){
        $data = $this->getData(self::CLR_TXT_HOV);
        if(!empty($data))
            return $data;
        else
            return '#333';
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

    public function setBackGroundColor($color){
        return $this->setData(self::CLR_BACK, $is_active);
    }

    public function setBackGroundColorHover($color){
        return $this->setData(self::CLR_BACK_HOV, $is_active);
    }

    public function setTextColor($color){
        return $this->setData(self::CLR_TXT, $is_active);
    }
    
    public function setTextColorHover($color){
        return $this->setData(self::CLR_TXT_HOV, $is_active);
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