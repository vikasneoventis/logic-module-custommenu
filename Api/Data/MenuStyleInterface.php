<?php
namespace Logic\CustomMenu\Api\Data;


interface MenuStyleInterface{

	const CLR_BACK      = 'background_color';
    const CLR_BACK_HOV  = 'background_color_hover';
    const CLR_TXT       = 'text_color';
    const CLR_TXT_HOV   = 'text_color_hover';
    const CREATION_TIME = 'creation_time';
    const UPDATE_TIME   = 'update_time';
    const IS_ACTIVE     = 'is_active';
    /**********/
    /* Getter */
    /**********/
    function getBackGroundColor();
    function getBackGroundColorHover();
    function getTextColor();
    function getTextColorHover();
    function getCreationTime();
    function getUpdateTime();
    function isActive();
    /**********/
    /* Setter */
    /**********/
    function setBackGroundColor($color);
    function setBackGroundColorHover($color);
    function setTextColor($color);
    function setTextColorHover($color);
    function setCreationTime($creationTime);
    function setUpdateTime($updateTime);
    function setIsActive($is_active);
}