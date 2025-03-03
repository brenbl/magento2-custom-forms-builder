<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord;

use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Helper\SecureHtmlRenderer;

/**
 * Class Edit
 * @package Alekseon\CustomFormsBuilder\Block\Adminhtml\FormRecord
 */
class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = 'Alekseon_CustomFormsBuilder';
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;

    /**
     * Edit constructor.
     * @param Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        array $data = []
    ) {
        $this->coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
    }

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_controller = 'adminhtml_formRecord';

        parent::_construct();

        $this->addButton(
            'save_and_continue',
            [
                'label' => __('Save and Continue'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ]
        );

        if (!$this->isSaveRecordAllowed()) {
            $this->removeButton('save');
            $this->removeButton('save_and_continue');
        }

        if (!$this->isDeleteRecordAllowed()) {
            $this->removeButton('delete');
        }
    }

    /**
     * Retrieve URL for save
     *
     * @return string
     */
    public function getSaveUrl()
    {
        return $this->getUrl(
            '*/*/save',
            ['_current' => true]
        );
    }

    /**
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('*/*/index', ['id' => $this->getRequest()->getParam('form_id')]);
    }

    /**
     * @return string
     */
    public function getDeleteUrl()
    {
        return $this->getUrl('*/*/delete',
            [
                'id' => $this->getRequest()->getParam('id'),
                'form_id' => $this->getRequest()->getParam('form_id')
            ]
        );
    }

    /**
     * @return mixed
     */
    protected function getCurrentForm()
    {
        return $this->coreRegistry->registry('current_form');
    }

    /**
     *
     */
    protected function isSaveRecordAllowed()
    {
        $manageResource = 'Alekseon_CustomFormsBuilder::manage_custom_forms';
        if ($this->_authorization->isAllowed($manageResource)) {
            return true;
        }

        $resource = 'Alekseon_CustomFormsBuilder::custom_form_' . $this->getCurrentForm()->getId() . '_save';
        return $this->_authorization->isAllowed($resource);
    }

    /**
     *
     */
    protected function isDeleteRecordAllowed()
    {
        return $this->isSaveRecordAllowed();
    }
}
