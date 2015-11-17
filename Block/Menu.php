<?php
namespace Logic\CustomMenu\Block;

use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Framework\Data\TreeFactory;
use Magento\Framework\Data\Tree\Node;
use Magento\Framework\Data\Tree\NodeFactory;
use Magento\Cms\Model\Resource\Block\CollectionFactory as blockCollectionFactory;
use Logic\CustomMenu\Model\Resource\Menu\CollectionFactory as menuCollectionFactory;
use Logic\CustomMenu\Api\Data\MenuInterface;
use Logic\CustomMenu\Api\Data\MenuHelperInterface;

class Menu extends Template implements IdentityInterface
{
	
    protected $_blockCollectionFactory;
    protected $_menuCollectionFactory;
    protected $_menuHelper;
    //
    protected $identities = [];
    protected $_menu;
    //
    const CACHE_TAG = 'logic_block';
    //
	public function __construct(
		Context $context,
		blockCollectionFactory $blockCollectionFactory,
        menuCollectionFactory $menuCollectionFactory,
        NodeFactory $nodeFactory,
        TreeFactory $treeFactory,
        MenuHelperInterface $menuHelper,
		array $data = []

	) {
		parent::__construct($context, $data);
		$this->_blockCollectionFactory = $blockCollectionFactory;
        $this->_menuCollectionFactory = $menuCollectionFactory;
        $this->_menuHelper = $menuHelper;
        $this->_menu = $nodeFactory->create(
            [
                'data' => [],
                'idField' => 'root',
                'tree' => $treeFactory->create()
            ]
        );
	}
    public function getColorSettings(){
        return $this->_menuHelper->getColorSettings();
    }
    public function getMenuId(){
        $this->_menuHelper->execute();
        return $this->_menuHelper->getMatchedIDs();
    }
    public function getBlockMenuIdArray($blocks_key = 'menu', $delimiter = '-'){
        //
        $blockCollection = $this->_blockCollectionFactory->create();
        //
        $block_id = $this->getMenuId();
        $menu_title = [];
        $menu_order = [];
        //
        foreach ($block_id as $id) {
            $title = $this->_menuHelper->getMenuData($id,MenuInterface::TITLE);
            $order = $this->_menuHelper->getMenuData($id,MenuInterface::ORDER);
            array_push($menu_title,$title);
            array_push($menu_order, $order);         
        }
        //
        $_return=array(
            'title'=>$menu_title,
            'block_id'=>$block_id
        );
        foreach ($_return as $key=>$item){
            $order = $menu_order;
            array_multisort($order,$_return[$key]);
        }
        return $_return;
    }
    /**
     * Get top menu html
     *
     * @param string $outermostClass
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string
     */
    public function getHtml($outermostClass = '', $childrenWrapClass = '', $limit = 0)
    {
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_before',
            ['menu' => $this->_menu, 'block' => $this]
        );

        $this->_menu->setOutermostClass($outermostClass);
        $this->_menu->setChildrenWrapClass($childrenWrapClass);

        $html = $this->_getHtml($this->_menu, $childrenWrapClass, $limit);

        $transportObject = new \Magento\Framework\DataObject(['html' => $html]);
        $this->_eventManager->dispatch(
            'page_block_html_topmenu_gethtml_after',
            ['menu' => $this->_menu, 'transportObject' => $transportObject]
        );
        $html = $transportObject->getHtml();

        return $html;
    }

    /**
     * Count All Subnavigation Items
     *
     * @param \Magento\Backend\Model\Menu $items
     * @return int
     */
    protected function _countItems($items)
    {
        $total = $items->count();
        foreach ($items as $item) {
            /** @var $item \Magento\Backend\Model\Menu\Item */
            if ($item->hasChildren()) {
                $total += $this->_countItems($item->getChildren());
            }
        }
        return $total;
    }

    /**
     * Building Array with Column Brake Stops
     *
     * @param \Magento\Backend\Model\Menu $items
     * @param int $limit
     * @return array|void
     *
     * @todo: Add Depth Level limit, and better logic for columns
     */
    protected function _columnBrake($items, $limit)
    {
        $total = $this->_countItems($items);
        if ($total <= $limit) {
            return;
        }

        $result[] = ['total' => $total, 'max' => (int)ceil($total / ceil($total / $limit))];

        $count = 0;
        $firstCol = true;

        foreach ($items as $item) {
            $place = $this->_countItems($item->getChildren()) + 1;
            $count += $place;

            if ($place >= $limit) {
                $colbrake = !$firstCol;
                $count = 0;
            } elseif ($count >= $limit) {
                $colbrake = !$firstCol;
                $count = $place;
            } else {
                $colbrake = false;
            }

            $result[] = ['place' => $place, 'colbrake' => $colbrake];

            $firstCol = false;
        }

        return $result;
    }

    /**
     * Add sub menu HTML code for current menu item
     *
     * @param \Magento\Framework\Data\Tree\Node $child
     * @param string $childLevel
     * @param string $childrenWrapClass
     * @param int $limit
     * @return string HTML code
     */
    protected function _addSubMenu($child, $childLevel, $childrenWrapClass, $limit)
    {
        $html = '';
        if (!$child->hasChildren()) {
            return $html;
        }

        $colStops = null;
        if ($childLevel == 0 && $limit) {
            $colStops = $this->_columnBrake($child->getChildren(), $limit);
        }

        // $html .= '<ul class="level' . $childLevel . ' submenu">';
        $html .= '<ul>';
        $html .= $this->_getHtml($child, $childrenWrapClass, $limit, $colStops);
        $html .= '</ul>';

        return $html;
    }

    /**
     * Recursively generates top menu html from data that is specified in $menuTree
     *
     * @param \Magento\Framework\Data\Tree\Node $menuTree
     * @param string $childrenWrapClass
     * @param int $limit
     * @param array $colBrakes
     * @return string
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function _getHtml(
        \Magento\Framework\Data\Tree\Node $menuTree,
        $childrenWrapClass,
        $limit,
        $colBrakes = []
    ) {
        $html = '';

        $children = $menuTree->getChildren();
        $parentLevel = $menuTree->getLevel();
        $childLevel = $parentLevel === null ? 0 : $parentLevel + 1;

        $counter = 1;
        $itemPosition = 1;
        $childrenCount = $children->count();

        $parentPositionClass = $menuTree->getPositionClass();
        $itemPositionClassPrefix = $parentPositionClass ? $parentPositionClass . '-' : 'nav-';

        foreach ($children as $child) {
            $child->setLevel($childLevel);
            $child->setIsFirst($counter == 1);
            $child->setIsLast($counter == $childrenCount);
            $child->setPositionClass($itemPositionClassPrefix . $counter);

            $outermostClassCode = '';
            $outermostClass = $menuTree->getOutermostClass();

            if ($childLevel == 0 && $outermostClass) {
                $outermostClassCode = ' class="' . $outermostClass . '" ';
                $child->setClass($outermostClass);
            }

            if (count($colBrakes) && $colBrakes[$counter]['colbrake']) {
                $html .= '</ul></li><li class="column"><ul>';
            }

            // $html .= '<li ' . $this->_getRenderedMenuItemAttributes($child) . '>';
            $html .= '<li>';
            $html .= '<a href="' . $child->getUrl() . '" ' . $outermostClassCode . '><span>' . $this->escapeHtml(
                $child->getName()
            ) . '</span></a>' . $this->_addSubMenu(
                $child,
                $childLevel,
                $childrenWrapClass,
                $limit
            ) . '</li>';
            $itemPosition++;
            $counter++;
        }

        if (count($colBrakes) && $limit) {
            $html = '<li class="column"><ul>' . $html . '</ul></li>';
        }

        return $html;
    }

    /**
     * Generates string with all attributes that should be present in menu item element
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return string
     */
    protected function _getRenderedMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
    {
        $html = '';
        $attributes = $this->_getMenuItemAttributes($item);
        foreach ($attributes as $attributeName => $attributeValue) {
            $html .= ' ' . $attributeName . '="' . str_replace('"', '\"', $attributeValue) . '"';
        }
        return $html;
    }

    /**
     * Returns array of menu item's attributes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemAttributes(\Magento\Framework\Data\Tree\Node $item)
    {
        $menuItemClasses = $this->_getMenuItemClasses($item);
        return ['class' => implode(' ', $menuItemClasses)];
    }

    /**
     * Returns array of menu item's classes
     *
     * @param \Magento\Framework\Data\Tree\Node $item
     * @return array
     */
    protected function _getMenuItemClasses(\Magento\Framework\Data\Tree\Node $item)
    {
        $classes = [];

        $classes[] = 'level' . $item->getLevel();
        $classes[] = $item->getPositionClass();

        if ($item->getIsFirst()) {
            $classes[] = 'first';
        }

        if ($item->getIsActive()) {
            $classes[] = 'active';
        } elseif ($item->getHasActive()) {
            $classes[] = 'has-active';
        }

        if ($item->getIsLast()) {
            $classes[] = 'last';
        }

        if ($item->getClass()) {
            $classes[] = $item->getClass();
        }

        if ($item->hasChildren()) {
            $classes[] = 'parent';
        }

        return $classes;
    }
    
    public function addIdentity($identity)
    {
        $this->identities[] = $identity;
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG.'_test'];
    }

}
