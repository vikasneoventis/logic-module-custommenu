<?php 
namespace Logic\CustomMenu\Block\Adminhtml\Menu\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Magento\Framework\Data\FormFactory;
use Logic\CustomMenu\Model\System\Config\Status;
use Logic\CustomMenu\Model\System\Config\MenuIdOptions;
 
class Info extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;

    protected $_menuStatus;

    protected $_menuIdOptions;
 
   /**
     * @param Context $context
     * @param Registry $registry
     * @param FormFactory $formFactory
     * @param Status $menuStatus
     * @param array $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        FormFactory $formFactory,
        Status $menuStatus,
        MenuIdOptions $menuIdOptions,
        array $data = []
    ) {
        $this->_menuStatus = $menuStatus;
        $this->_menuIdOptions = $menuIdOptions;
        parent::__construct($context, $registry, $formFactory, $data);
    }
 
    /**
     * Prepare form fields
     *
     * @return \Magento\Backend\Block\Widget\Form
     */
    protected function _prepareForm()
    {
       /** @var $model \Logic\CustomMenu\Model\Menu */
        $model = $this->_coreRegistry->registry('logic_menu_admin');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('menu_');
        $form->setFieldNameSuffix('menu');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('General')]
        );
 
        if ($model->getId()) {
            $fieldset->addField(
                'id',
                'hidden',
                ['name' => 'id']
            );
        }
        
        $fieldset->addField(
            'menu_id',
            'select',
            [
                'name'      => 'menu_id',
                'label'     => __('Menu ID'),
                'options'   => $this->_menuIdOptions->toOptionArray()
            ]
        );
        $fieldset->addField(
            'menu_title',
            'text',
            [
                'name'        => 'menu_title',
                'label'    => __('Title'),
                'required'     => true
            ]
        );
        // $fieldset->addField(
        //     'menu_identifier',
        //     'text',
        //     [
        //         'name'        => 'menu_identifier',
        //         'label'    => __('Identifier'),
        //         'required'     => true
        //     ]
        // );
        // $fieldset->addField(
        //     'menu_parent',
        //     'text',
        //     [
        //         'name'        => 'menu_parent',
        //         'label'    => __('Parent'),
        //         'required'     => true
        //     ]
        // );
        // $fieldset->addField(
        //     'menu_layer',
        //     'text',
        //     [
        //         'name'        => 'menu_layer',
        //         'label'    => __('Layer'),
        //         'required'     => true
        //     ]
        // );
        // $fieldset->addField(
        //     'menu_col',
        //     'text',
        //     [
        //         'name'        => 'menu_col',
        //         'label'    => __('Column'),
        //         'required'     => true
        //     ]
        // );
        // $fieldset->addField(
        //     'menu_order',
        //     'text',
        //     [
        //         'name'        => 'menu_order',
        //         'label'    => __('Order'),
        //         'required'     => true
        //     ]
        // );
        $fieldset->addField(
            'is_active',
            'select',
            [
                'name'      => 'is_active',
                'label'     => __('Status'),
                'options'   => $this->_menuStatus->toOptionArray()
            ]
        );
        $data = $model->getData();
        $form->setValues($data);
        $this->setForm($form);
 
        return parent::_prepareForm();
    }
 
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return __('Menu Info');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Menu Info');
    }
 
    /**
     * {@inheritdoc}
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * {@inheritdoc}
     */
    public function isHidden()
    {
        return false;
    }
}
 