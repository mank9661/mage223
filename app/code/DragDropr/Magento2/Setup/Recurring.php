<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DragDropr\Magento2\Setup;

use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Integration\Model\ConfigBasedIntegrationManager;
use Magento\Framework\Setup\InstallSchemaInterface;

/**
 * Class Recurring
 *
 */
class Recurring implements InstallSchemaInterface
{
    /**
     * @var ConfigBasedIntegrationManager
     */
    private $integrationManager;

    /**
     * Initialize dependencies
     *
     * @param ConfigBasedIntegrationManager $integrationManager
     */
    public function __construct(
        ConfigBasedIntegrationManager $integrationManager
    ) {
        $this->integrationManager = $integrationManager;
    }

    /**
     * {@inheritdoc}
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $this->installDragDroprIntegration();
    }

    /**
     * Installs DragDropr integration
     *
     * @return $this
     */
    private function installDragDroprIntegration()
    {
        if (! class_exists('Magento\Integration\Model\ConsolidatedConfig')) {
            $this->integrationManager->processIntegrationConfig(['DragDropr']);
        }

        return $this;
    }
}
