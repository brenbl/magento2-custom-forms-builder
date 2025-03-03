<?php
/**
 * Copyright © Alekseon sp. z o.o.
 * http://www.alekseon.com/
 */
namespace Alekseon\CustomFormsBuilder\Setup;

use Alekseon\AlekseonEav\Model\Adminhtml\System\Config\Source\Scopes;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\UpgradeDataInterface;

/**
 * Class UpgradeData
 * @package Alekseon\CustomFormsBuilder\Setup
 */
class UpgradeData implements UpgradeDataInterface
{
    /**
     * @var EavDataSetupFactory
     */
    protected $eavSetupFactory;
    /**
     * @var \Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository
     */
    protected $formAttributeRepository;

    /**
     * InstallData constructor.
     * @param \Alekseon\AlekseonEav\Setup\EavDataSetupFactory $eavSetupFactory
     * @param \Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository $formAttributeRepository
     */
    public function __construct(
        \Alekseon\AlekseonEav\Setup\EavDataSetupFactory $eavSetupFactory,
        \Alekseon\CustomFormsBuilder\Model\Form\AttributeRepository $formAttributeRepository
    ) {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->formAttributeRepository = $formAttributeRepository;
    }

    /**
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     */
    public function upgrade(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        if (version_compare($context->getVersion(), '1.0.1', '<')) {
            $this->addEnableEmailNotificationAttribute($setup);
        }

        if (version_compare($context->getVersion(), '1.0.3', '<')) {
            $this->addShowInAdminMenuAttribute($setup);
        }
    }

    /**
     * @param $setup
     */
    protected function addEnableEmailNotificationAttribute($setup)
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->createAttribute(
            'enable_email_notification',
            [
                'frontend_input' => 'boolean',
                'frontend_label' => 'Notify By Email about new entities',
                'visible_in_grid' => true,
                'sort_order' => 100,
                'scope' => Scopes::SCOPE_GLOBAL,
            ]
        );
    }

    /**
     *
     */
    protected function addShowInAdminMenuAttribute()
    {
        $eavSetup = $this->eavSetupFactory->create();
        $eavSetup->setAttributeRepository($this->formAttributeRepository);

        $eavSetup->createAttribute(
            'show_in_menu',
                [
                    'frontend_input' => 'boolean',
                    'frontend_label' => 'Show in adminhtml menu',
                    'visible_in_grid' => false,
                    'is_required' => false,
                    'sort_order' => 40,
                    'scope' => Scopes::SCOPE_GLOBAL,
                    'note' => __('Menu -> Marketing -> Custom Forms'),
                ]
        );
    }
}
