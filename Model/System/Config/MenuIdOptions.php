<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Model\System\Config;

use Magento\Cms\Model\ResourceModel\Block\CollectionFactory;
use Magento\Framework\Option\ArrayInterface;
 
/**
 * Catalog category landing page attribute source
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 */
class MenuIdOptions implements ArrayInterface
{
    /**
     * Block collection factory
     *
     * @var CollectionFactory
     */
    protected $_blockCollectionFactory;

    /**
     * Construct
     *
     * @param CollectionFactory $blockCollectionFactory
     */
    public function __construct(CollectionFactory $blockCollectionFactory)
    {
        $this->_blockCollectionFactory = $blockCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        
        $_options = $this->_blockCollectionFactory->create()->toOptionArray();
        $options =array();
        foreach ($_options as $key => $option_array) {
            // file_put_contents('php://stderr', print_r($option_array, TRUE));
            $options[$option_array['value']] = $option_array['label'];
        }
        return $options;
    }
}
