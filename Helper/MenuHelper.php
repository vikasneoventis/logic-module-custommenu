<?php namespace Logic\CustomMenu\Helper;

use Logic\CustomMenu\Api\Data\MenuInterface;
use Logic\CustomMenu\Api\Data\MenuHelperInterface;
use Logic\CustomMenu\Model\Resource\Menu\CollectionFactory as menuCollectionFactory;
use Magento\Cms\Model\Resource\Block\CollectionFactory as blockCollectionFactory;
use Magento\Framework\App\Helper\AbstractHelper;

class MenuHelper extends AbstractHelper implements MenuHelperInterface
{
    protected $_blockCollectionFactory;
    protected $_menuCollectionFactory;
    protected $_matchedIDs;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        menuCollectionFactory $menuCollectionFactory,
        blockCollectionFactory $blockCollectionFactory
    )
    { 
        $this->_menuCollectionFactory = $menuCollectionFactory;
        $this->_blockCollectionFactory = $blockCollectionFactory;
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
    public function getColorSettings(){
         
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
                $this->_debug('return false','getMenuData');
                return false;
        }
    }
    /* Debug */
    private function _debug($debug_message = 'debug message',$id = '0'){

        file_put_contents('php://stderr', print_r(PHP_EOL.'--id:'.$id.'--'.date("Y-m-d H:i:s").PHP_EOL, TRUE));
        if(is_array($debug_message)){
            file_put_contents('php://stderr', print_r($debug_message, TRUE));
            file_put_contents('php://stderr', print_r(PHP_EOL, TRUE));
        }else{
            file_put_contents('php://stderr', print_r($debug_message.PHP_EOL, TRUE));
        }
    }

}
