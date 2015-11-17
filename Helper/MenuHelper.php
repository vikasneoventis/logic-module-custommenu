<?php namespace Logic\CustomMenu\Helper;

use Logic\CustomMenu\Api\Data\MenuInterface;
use Logic\CustomMenu\Api\Data\MenuStyleInterface;
use Logic\CustomMenu\Api\Data\MenuHelperInterface;
use Logic\CustomMenu\Model\ResourceModel\Menu\CollectionFactory as menuCollectionFactory;
use Logic\CustomMenu\Model\ResourceModel\Settings\CollectionFactory as settingsCollectionFactory;
use Magento\Cms\Model\ResourceModel\Block\CollectionFactory as blockCollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper implements MenuHelperInterface
{
    protected $_blockCollectionFactory;
    protected $_menuCollectionFactory;
    protected $_settingsCollectionFactory;
    protected $_matchedIDs;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        menuCollectionFactory $menuCollectionFactory,
        settingsCollectionFactory $settingsCollectionFactory,
        blockCollectionFactory $blockCollectionFactory
    )
    { 
        $this->_menuCollectionFactory = $menuCollectionFactory;
        $this->_blockCollectionFactory = $blockCollectionFactory;
        $this->_settingsCollectionFactory = $settingsCollectionFactory;
        parent::__construct($context);
    }

    private function clearMatchedIDs(){
        $this->_matchedIDs = array();
    }

    private function getBlockIDs(){
        $blockIDs=[];
        $blockCollection = $this->_blockCollectionFactory->create();
        foreach ($blockCollection as $key=>$blockModel) {
            if( $blockModel->isActive()){
                $blockId = $blockModel->getId();            
                array_push($blockIDs,$blockId);              
            }
        }
        return $blockIDs;
    }

    private function getMenuIDs(){
        $menuIDs=[];
        $menuCollection = $this->_menuCollectionFactory->create();
        foreach ($menuCollection as $key=>$menuModel) {
            if( $menuModel->isActive()){
                $menuId = $menuModel->getMenuId();            
                array_push($menuIDs,$menuId);              
            }
        }
        return $menuIDs;
    }
    private function searchMatchedIDs($inputBlockIDs=null,$inputMenuIDs=null){
        $blockIDs = $inputBlockIDs ? $inputBlockIDs : $this->getBlockIDs();
        $menuIDs = $inputMenuIDs ? $inputMenuIDs : $this->getMenuIDs();
        $IDs_count = count($blockIDs);
        foreach ($menuIDs as $index=>$id) {
            $found = $this->binarySearch(0,$IDs_count-1,$blockIDs,$id);
            if($found !== -1){          
                array_push($this->_matchedIDs,$blockIDs[$found]);
            }
        }
    }

    private function binarySearch($left, $right, $array, $target){
        $mid = floor(($right + $left)/2);
        if($right<$left){
            return -1;
        }
        if($array[$mid]>$target){
            return $this->binarySearch($left, $mid-1, $array, $target);
        }else if($array[$mid]<$target){
            return $this->binarySearch($mid+1, $right, $array, $target);
        }else{
            return $mid;
        }
    }

    public function execute($inputBlockIDs=null,$inputMenuIDs=null){
        $this->clearMatchedIDs();
        $this->searchMatchedIDs($inputBlockIDs,$inputMenuIDs);
    }

    public function getMatchedIDs(){
        return $this->_matchedIDs;
    }

    public function getMenuData($request_id, $fieldname, $menuCollection=null){
        $menuCollection = $menuCollection ? $menuCollection:$this->_menuCollectionFactory->create();
        $menuCollection -> addFieldToFilter('menu_id',["eq" => $request_id]);            
        foreach ($menuCollection as $key=>$menuModel) {
            if( $menuModel->getMenuId() === $request_id ){
                return $this->MenuDataFilter($menuModel,$fieldname);        
            }
        }
        return false;
    }
    /*Menu Data Filter*/
    private function MenuDataFilter($model,$fieldname){
        switch ($fieldname) {
            case MenuInterface::MENU_ID:
                return $model->getMenuId();
            case MenuInterface::IDENTIFIER:
                return $model->getIdentifier();
            case MenuInterface::ORDER:
                return $model->getOrder();
            case MenuInterface::COLUMN:
                return $model->getColumn();
            case MenuInterface::LAYER:
                return $model->getLayer();
            case MenuInterface::TITLE:
                return $model->getTitle();
            case MenuInterface::MENU_PARENT:
                return $model->getParent();
            case MenuInterface::IS_ACTIVE:
                return $model->isActive();
            default:
                return false;
        }
    }
    public function getColorSettings(){
        $settings=array();
        $settomgsCollection = $this->_settingsCollectionFactory->create();
        $backGroundColor = '#ffffff';
        $backGroundColorHov = '#ffffff';
        $textColor = '#333';
        $textColorHov = '#333';
        foreach ($settomgsCollection as $key=>$settingsModel) {
            if( $settingsModel->isActive()){
                $backGroundColor = $this->MenuStyleDataFilter($settingsModel,MenuStyleInterface::CLR_BACK)?:$backGroundColor;
                $backGroundColorHov = $this->MenuStyleDataFilter($settingsModel,MenuStyleInterface::CLR_BACK_HOV)?:$backGroundColorHov;
                $textColor = $this->MenuStyleDataFilter($settingsModel,MenuStyleInterface::CLR_TXT)?:$textColor;
                $textColorHov = $this->MenuStyleDataFilter($settingsModel,MenuStyleInterface::CLR_TXT_HOV)?:$textColorHov;
                break;
            }
        }
        $settings=array(
                    'backGroundColor' => $backGroundColor,
                    'backGroundColorHov' => $backGroundColorHov,
                    'textColor' => $textColor,
                    'textColorHov' => $textColorHov
                );
        return $settings;
    }
    /*Menu Style Data Filter*/
    private function MenuStyleDataFilter($model,$fieldname){
        switch ($fieldname) {
            case MenuStyleInterface::CLR_BACK:
                return $model->getBackGroundColor();
            case MenuStyleInterface::CLR_BACK_HOV:
                return $model->getBackGroundColorHover();
            case MenuStyleInterface::CLR_TXT:
                return $model->getTextColor();
            case MenuStyleInterface::CLR_TXT_HOV:
                return $model->getTextColorHover();
            default:
                return false;
        }
    }
}
