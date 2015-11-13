<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Logic\CustomMenu\Test\Unit\Block\Adminhtml\Menu\Edit;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class FormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $registry;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $form;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $formFactory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fieldSet;
    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $eventManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $scopeConfig;

    /**
     * @var string
     */
    protected $action = 'test';

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $urlBuilder;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $appState;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $rootDirectory;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $logger;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $fileSystem;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $context;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $layout;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    protected $model;

    /**
     * @var \Logic\CustomMenu\Block\Adminhtml\Menu\Edit\Form
     */
    protected $block;

    /**
     * @var \Magento\Framework\View\Element\Template\File\Resolver|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_resolver;

    /**
     * @var \Magento\Framework\View\Element\Template\File\Validator|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $_validator;

    protected $status;
    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @return void
     */
    public function setUp()
    {
        $this->_resolver = $this->getMock(
            'Magento\Framework\View\Element\Template\File\Resolver',
            [],
            [],
            '',
            false
        );

        $this->_validator = $this->getMock(
            'Magento\Framework\View\Element\Template\File\Validator',
            [],
            [],
            '',
            false
        );

        $this->model = $this->getMockBuilder('Logic\CustomMenu\Model\Menu')->disableOriginalConstructor()->getMock();
        $this->menuFactoryMock = $this->getMockBuilder('Logic\CustomMenu\Model\MenuFactory')
            ->setMethods(['create'])
            ->disableOriginalConstructor()
            ->getMock();
        $this->menuFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->model);
        $this->status = $this->getMockBuilder('Logic\CustomMenu\Model\System\Config\Status')->disableOriginalConstructor()->getMock();
        
        $this->registry = $this->getMock('Magento\Framework\Registry', [], [], '', false);
        $this->registry->expects($this->once())->method('registry')->with('logic_menu_admin')->willReturn($this->model);

        $this->fieldSet = $this->getMock('Magento\Framework\Data\Form\Element\Fieldset', [], [], '', false);
        $this->eventManager = $this->getMock('Magento\Framework\Event\ManagerInterface', [], [], '', false);
        $this->scopeConfig = $this->getMock('Magento\Framework\App\Config\ScopeConfigInterface', [], [], '', false);
        $this->urlBuilder = $this->getMock('Magento\Framework\UrlInterface', [], [], '', false);
        $this->appState = $this->getMock('Magento\Framework\App\State', [], [], '', false);
        $this->logger = $this->getMock('Psr\Log\LoggerInterface', [], [], '', false);
        $this->rootDirectory = $this->getMock(
            'Magento\Framework\Filesystem\Directory\ReadInterface',
            [],
            [],
            '',
            false
        );
        $this->layout = $this->getMock('Magento\Framework\View\LayoutInterface', [], [], '', false);

        $this->fileSystem = $this->getMock('Magento\Framework\Filesystem', [], [], '', false);
        $this->fileSystem->expects($this->atLeastOnce())->method('getDirectoryRead')->willReturn($this->rootDirectory);

        $this->form = $this->getMock('Magento\Framework\Data\Form', [], [], '', false);
        $this->form->expects($this->once())->method('addFieldset')->with(
            'base_fieldset',
            ['legend' => __('General')]
        )->willReturn($this->fieldSet);

        $this->formFactory = $this->getMock('\Magento\Framework\Data\FormFactory', [], [], '', false);
        $this->formFactory->expects($this->once())
            ->method('create')
            ->willReturn($this->form);

        $this->context = $this->getMock('Magento\Backend\Block\Template\Context', [], [], '', false);
        $this->context->expects($this->once())->method('getEventManager')->willReturn($this->eventManager);
        $this->context->expects($this->once())->method('getScopeConfig')->willReturn($this->scopeConfig);
        $this->context->expects($this->once())->method('getUrlBuilder')->willReturn($this->urlBuilder);
        $this->context->expects($this->once())->method('getAppState')->willReturn($this->appState);
        $this->context->expects($this->once())->method('getFilesystem')->willReturn($this->fileSystem);
        $this->context->expects($this->once())->method('getLogger')->willReturn($this->logger);
        $this->context->expects($this->once())->method('getLayout')->willReturn($this->layout);
        $this->context->expects($this->once())->method('getResolver')->willReturn($this->_resolver);
        $this->context->expects($this->once())->method('getValidator')->willReturn($this->_validator);

        /** @var \Logic\CustomMenu\Block\Adminhtml\Menu\Edit\Form $block */
        $this->block = (new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this))
            ->getObject(
                'Logic\CustomMenu\Block\Adminhtml\Menu\Edit\Tab\Info',
                [
                    'context' => $this->context,
                    'registry' => $this->registry,
                    'formFactory' => $this->formFactory,
                ]
            );
        $this->block->setData('action', $this->action);
    }

    /**
     * Test prepare form model has no block id and single store mode is on
     *
     * @return void
     */
    public function testPrepareFormModelHasNoBlockId()
    {
        $menuId = null;

        $this->model->expects($this->once())->method('getId')->willReturn($menuId);

        $this->block->toHtml();
    }

    /**
     * Test prepare form model has block id and signle store mode is off
     *
     * @return void
     */
    // TODO: add status test
    public function testPrepareFormModelHasBlockId()
    {
        $menuId = 'id';

        $renderer = $this->getMock(
            'Magento\Backend\Block\Store\Switcher\Form\Renderer\Fieldset\Element',
            [],
            [],
            '',
            false
        );  
        // $this->status->expects($this->once())->method('toOptionArray')->willReturn(
        //         [
        //             \Logic\CustomMenu\Model\System\Config\Status::ENABLED => __('Enabled'),
        //             \Logic\CustomMenu\Model\System\Config\Status::DISABLED => __('Disabled')
        //         ]
        //     );

        $this->model->expects($this->once())->method('getId')->willReturn($menuId);

        $this->fieldSet->expects($this->at(0))->method('addField')->with('id', 'hidden', ['name' => 'id']);

        $this->fieldSet->expects($this->at(1))->method('addField')->with(
            'menu_id',
            'text',
            [
                'name'        => 'menu_id',
                'label'    => __('Menu ID'),
                'required'     => true
            ]);

        $this->fieldSet->expects($this->at(2))->method('addField')->with(
            'menu_title',
            'text',
            [
                'name'        => 'menu_title',
                'label'    => __('Title'),
                'required'     => true
            ]);
        $this->fieldSet->expects($this->at(3))->method('addField')->with(
            'menu_identifier',
            'text',
            [
                'name'        => 'menu_identifier',
                'label'    => __('Identifier'),
                'required'     => true
            ]);
        $this->fieldSet->expects($this->at(4))->method('addField')->with(
            'menu_parent',
            'text',
            [
                'name'        => 'menu_parent',
                'label'    => __('Parent'),
                'required'     => true
            ]);
        $this->fieldSet->expects($this->at(5))->method('addField')->with(
            'menu_layer',
            'text',
            [
                'name'        => 'menu_layer',
                'label'    => __('Layer'),
                'required'     => true
            ]);
        $this->fieldSet->expects($this->at(6))->method('addField')->with(
            'menu_col',
            'text',
            [
                'name'        => 'menu_col',
                'label'    => __('Column'),
                'required'     => true
            ]);
        $this->fieldSet->expects($this->at(7))->method('addField')->with(
            'menu_order',
            'text',
            [
                'name'        => 'menu_order',
                'label'    => __('Order'),
                'required'     => true
            ]);
        // $this->fieldSet->expects($this->at(8))->method('addField')->with(
        //     'is_active',
        //     'select',
        //     [
        //         'name'      => 'is_active',
        //         'label'     => __('Status'),
        //         'options'   => $this->status->toOptionArray()
        //     ]);

        $this->block->toHtml();
    }
    public function testCanShowTab(){
        $this->block->toHtml();
        $this->assertSame(true,$this->block->canShowTab());

    }
    public function testIsHidden(){
        $this->block->toHtml();
        $this->assertSame(false,$this->block->isHidden());
    }
    public function testGetTabLabel()
    {
        $this->block->toHtml();
        $this->assertInstanceOf('Magento\Framework\Phrase', $this->block->getTabLabel());
    }
    public function testGetTabTitle()
    {
        $this->block->toHtml();
        $this->assertInstanceOf('Magento\Framework\Phrase', $this->block->getTabTitle());
    }
}
