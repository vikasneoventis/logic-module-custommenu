<?php
/**
 *
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Controller\Adminhtml\Menu;

use Logic\CustomMenu\Controller\Adminhtml\AbstractMassDelete;

/**
 * Class MassDelete
 */
class MassDelete extends AbstractMassDelete
{
    /**
     * Field id
     */
    const ID_FIELD = 'id';

    /**
     * Resource collection
     *
     * @var string
     */
    protected $collection = 'Logic\CustomMenu\Model\Resource\Menu\Collection';

    /**
     * Block model
     *
     * @var string
     */
    protected $model = 'Logic\CustomMenu\Model\Menu';
}
