<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace DragDropr\Magento2\Test\Unit\Setup;

class Recurring extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Model\ConfigBasedIntegrationManager
     */
    protected $integrationManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Setup\SchemaSetupInterface
     */
    protected $setupMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\Setup\ModuleContextInterface
     */
    protected $contextMock;

    /**
     * @var \DragDropr\Magento2\Setup\Recurring
     */
    protected $recurring;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->integrationManagerMock = $this->createMock(\Magento\Integration\Model\ConfigBasedIntegrationManager::class);
        $this->setupMock = $this->createMock(\Magento\Framework\Setup\SchemaSetupInterface::class);
        $this->contextMock = $this->createMock(\Magento\Framework\Setup\ModuleContextInterface::class);
        $this->recurring = $this->objectManager->getObject(
            \DragDropr\Magento2\Setup\Recurring::class,
            [
                'integrationManager' => $this->integrationManagerMock
            ]
        );
    }

    public function testInstall()
    {
        if (class_exists('Magento\Integration\Model\ConsolidatedConfig')) {
            $this->integrationManagerMock
                ->expects($this->never())
                ->method('processIntegrationConfig');
        } else {
            $this->integrationManagerMock
                ->expects($this->once())
                ->method('processIntegrationConfig')
                ->with(['DragDropr']);
        }

        $this->assertSame(
            null,
            $this->recurring->install($this->setupMock, $this->contextMock)
        );
    }
}
