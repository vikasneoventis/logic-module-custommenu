<?php 
namespace Logic\CustomMenu\Block\Adminhtml\Settings\Edit\Tab;
 
use Magento\Backend\Block\Widget\Form\Generic;
use Magento\Backend\Block\Widget\Tab\TabInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\Registry;
use Logic\CustomMenu\Model\System\Config\Status;
use Magento\Framework\Data\FormFactory;
 
class Color extends Generic implements TabInterface
{
    /**
     * @var \Magento\Cms\Model\Wysiwyg\Config
     */
    protected $_wysiwygConfig;
 
    protected $_menuStatus;
 
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
        array $data = []
    ) {
        $this->_menuStatus = $menuStatus;
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
        $model = $this->_coreRegistry->registry('logic_menu_settings_admin');
 
        /** @var \Magento\Framework\Data\Form $form */
        $form = $this->_formFactory->create();
        $form->setHtmlIdPrefix('settings_');
        $form->setFieldNameSuffix('settings');
 
        $fieldset = $form->addFieldset(
            'base_fieldset',
            ['legend' => __('Color Settings')]
        );
        
        $fieldset->addField(
            'background_color',
            'text',
            [
                'name'      => 'background_color',
                'label'     => __('Background Color'),
            ]
        );
        $fieldset->addField(
            'background_color_hover',
            'text',
            [
                'name'        => 'background_color_hover',
                'label'    => __('Background Color(Hover)'),
            ]
        );
        $fieldset->addField(
            'text_color',
            'text',
            [
                'name'      => 'text_color',
                'label'     => __('Text Color'),
            ]
        );
        $fieldset->addField(
            'text_color_hover',
            'text',
            [
                'name'        => 'text_color_hover',
                'label'    => __('Text Color(Hover)'),
            ]
        );
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
        return __('Menu Settings');
    }
 
    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return __('Menu Settings');
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
 