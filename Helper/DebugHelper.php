<?php namespace Logic\CustomMenu\Helper;

use Logic\CustomMenu\Api\Data\DebugHelperInterface;

class DebugHelper extends AbstractHelper implements DebugHelperInterface
{

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
    )
    { 
        parent::__construct($context);
    }

}
