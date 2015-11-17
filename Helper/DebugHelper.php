<?php namespace Logic\CustomMenu\Helper;

use Logic\CustomMenu\Api\Data\DebugHelperInterface;
use Magento\Framework\App\Helper\AbstractHelper;

class DebugHelper extends AbstractHelper implements DebugHelperInterface
{

    public function __construct(
        \Magento\Framework\App\Helper\Context $context
    )
    { 
        parent::__construct($context);
    }
    
    function _logMsg($tag='empty',$msg='empty',$active=1){
        if(!$active) return;
        $startLine = '----- Log Msg : '.$tag.' -----';
        $endLine = '--';
        $time = '----- '.date("Y-m-d H:i:s").' -----';
        file_put_contents('php://stderr', print_r(PHP_EOL.$startLine.PHP_EOL, TRUE));
        file_put_contents('php://stderr', print_r($time.PHP_EOL, TRUE));
        file_put_contents('php://stderr', print_r($msg, TRUE));
        file_put_contents('php://stderr', print_r(PHP_EOL.$endLine.PHP_EOL, TRUE)); 
    }

}
