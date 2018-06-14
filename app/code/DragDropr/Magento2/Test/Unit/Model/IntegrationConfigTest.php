<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model;

class IntegrationConfigTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\ObjectManagerInterface
     */
    protected $objectManagerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Model\ConsolidatedConfig|\Magento\Integration\Model\Config
     */
    protected $integrationConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Model\IntegrationConfig
     */
    protected $integrationAclConfigMock;

    /**
     * @var \DragDropr\Magento2\Api\IntegrationConfigInterface
     */
    protected $integrationConfig;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->objectManagerMock = $this->createMock(\Magento\Framework\ObjectManagerInterface::class);
        \Magento\Framework\App\ObjectManager::setInstance($this->objectManagerMock);
    }

    protected function getIntegrationConfigClass()
    {
        if (class_exists('Magento\Integration\Model\ConsolidatedConfig')) {
            return 'Magento\Integration\Model\ConsolidatedConfig';
        }

        return 'Magento\Integration\Model\Config';
    }

    protected function getIntegrationAclConfigClass()
    {
        if (class_exists('Magento\Integration\Model\ConsolidatedConfig')) {
            return null;
        }

        return 'Magento\Integration\Model\IntegrationConfig';
    }

    protected function getIntegrations()
    {
        return [
            'one' => [
                'name' => 'one'
            ],
            'two' => [
                'name' => 'two'
            ],
            'three' => [
                'name' => 'three'
            ]
        ];
    }

    public function dataForIntegrationConfig()
    {
        return [
            [$this->getIntegrations(), $this->getIntegrationConfigClass(), $this->getIntegrationAclConfigClass()]
        ];
    }

    /**
     * @dataProvider dataForIntegrationConfig
     */
    public function testGetIntegrations($integrations, $integrationConfigClass, $integrationAclConfigClass = null)
    {
        $this->integrationConfigMock = $this->createMock($integrationConfigClass);
        $this->integrationConfigMock
            ->expects($this->once())
            ->method('getIntegrations')
            ->willReturn($integrations);

        $this->objectManagerMock
            ->expects($this->at(0))
            ->method('get')
            ->with($integrationConfigClass)
            ->willReturn($this->integrationConfigMock);

        if ($integrationAclConfigClass) {
            $this->integrationAclConfigMock = $this->createMock($integrationAclConfigClass);
            $this->objectManagerMock
                ->expects($this->at(1))
                ->method('get')
                ->with($integrationAclConfigClass)
                ->willReturn($this->integrationAclConfigMock);

            $aclConfig = [];

            foreach ($integrations as $name => $config) {
                $aclConfig[$name]['resources'] = $name;
                $integrations[$name]['resource'] = $aclConfig[$name]['resources'];
            }

            $this->integrationAclConfigMock
                ->expects($this->once())
                ->method('getIntegrations')
                ->willReturn($aclConfig);
        } else {
            foreach ($integrations as $name => $config) {
                $integrations[$name]['resource'] = [];
            }
        }

        $this->integrationConfig = $this->objectManager->getObject(\DragDropr\Magento2\Model\IntegrationConfig::class);
        $this->assertSame(
            $integrations,
            $this->integrationConfig->getIntegrations()
        );
    }

    public function testGetIntegration()
    {
        /**
         * @var $integrationConfig \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\IntegrationConfigInterface
         */
        $integrationConfig = $this->getMockBuilder(\DragDropr\Magento2\Model\IntegrationConfig::class)
            ->setMethods(['getIntegrations'])
            ->disableOriginalConstructor()
            ->getMock();

        $integrationConfig
            ->expects($this->exactly(2))
            ->method('getIntegrations')
            ->willReturn([
                'DragDropr' => 'integrationConfig::getIntegrations'
            ]);

        $this->assertSame(
            null,
            $integrationConfig->getIntegration('nonExisting')
        );
        $this->assertSame(
            'integrationConfig::getIntegrations',
            $integrationConfig->getIntegration('DragDropr')
        );
    }
}
