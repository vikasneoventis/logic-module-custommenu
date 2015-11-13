<?php
namespace Logic\CustomMenu\Api\Data;


interface MenuInterface
{
    /**
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const MENU_ID       = 'menu_id';
    const IDENTIFIER    = 'menu_identifier';
    const ORDER         = 'menu_order';
    const COLUMN        = 'menu_col';
    const LAYER         = 'menu_layer';
    const TITLE         = 'menu_title';
    const MENU_PARENT   = 'menu_parent';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';

    /**********/
    /* Getter */
    /**********/
    function getMenuId();
    function getIdentifier();
    function getOrder();
    function getColumn();
    function getLayer();
    function getTitle();
    function getParent();
    function getCreationTime();
    function getUpdateTime();
    function isActive();
    /**********/
    /* Setter */
    /**********/
    function setMenuId($menu_id);
    function setIdentifier($identifier);
    function setOrder($order);
    function setColumn($column);
    function setLayer($layer);
    function setTitle($title);
    function setParent($parent);
    function setCreationTime($creationTime);
    function setUpdateTime($updateTime);
    function setIsActive($is_active);
}
