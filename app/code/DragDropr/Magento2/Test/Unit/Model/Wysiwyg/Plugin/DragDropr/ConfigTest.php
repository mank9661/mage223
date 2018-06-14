<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model\Wysiwyg\Plugin\DragDropr;

class ConfigTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Integration\Api\IntegrationServiceInterface
     */
    protected $integrationServiceMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfigMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Config\Storage\WriterInterface
     */
    protected $writerMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\App\Cache\TypeListInterface
     */
    protected $cacheTypeListMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Data\EnvironmentInterface
     */
    protected $environmentMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Data\IntegrationInterfaceFactory
     */
    protected $integrationFactoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\\DragDropr\Magento2\Api\Data\IntegrationInterface
     */
    protected $integrationMock;

    /**
     * @var \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface
     */
    protected $config;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->integrationServiceMock = $this->createMock(\Magento\Integration\Api\IntegrationServiceInterface::class);
        $this->scopeConfigMock = $this->createMock(\Magento\Framework\App\Config\ScopeConfigInterface::class);
        $this->writerMock = $this->createMock(\Magento\Framework\App\Config\Storage\WriterInterface::class);
        $this->cacheTypeListMock = $this->createMock(\Magento\Framework\App\Cache\TypeListInterface::class);
        $this->environmentMock = $this->createMock(\DragDropr\Magento2\Api\Data\EnvironmentInterface::class);
        $this->integrationFactoryMock = $this->createPartialMock(
            \DragDropr\Magento2\Api\Data\IntegrationInterfaceFactory::class,
            ['create']
        );
        $this->integrationMock = $this->createMock(\DragDropr\Magento2\Api\Data\IntegrationInterface::class);
        $this->config = $this->objectManager->getObject(
            \DragDropr\Magento2\Model\Wysiwyg\Plugin\DragDropr\Config::class,
            [
                'integrationService' => $this->integrationServiceMock,
                'scopeConfig' => $this->scopeConfigMock,
                'writer' => $this->writerMock,
                'cacheTypeList' => $this->cacheTypeListMock,
                'environment' => $this->environmentMock,
                'integrationInterfaceFactory' => $this->integrationFactoryMock
            ]
        );
    }

    public function dataForSetApiKey()
    {
        return [
            ['false', 'false'],
            ['false', 'true'],
            ['true', 'false'],
            ['true', 'true']
        ];
    }

    /**
     * @dataProvider dataForSetApiKey
     */
    public function testSetApiKey($currentApiKey, $newApiKey)
    {
        $this->scopeConfigMock
            ->expects($this->at(0))
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::API_KEY,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($currentApiKey);

        if ($currentApiKey !== $newApiKey) {
            $this->writerMock
                ->expects($this->once())
                ->method('save')
                ->with(
                    \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::API_KEY,
                    $newApiKey,
                    \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );

            $this->scopeConfigMock
                ->expects($this->at(1))
                ->method('getValue')
                ->with(
                    \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::API_KEY,
                    \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                )
                ->willReturn($newApiKey);

            $this->cacheTypeListMock
                ->expects($this->once())
                ->method('cleanType')
                ->with(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        }

        $this->assertSame(
            $newApiKey,
            $this->config->setApiKey($newApiKey)
        );
    }

    public function dataForGetApiKey()
    {
        return [
            [null],
            [false],
            [true],
            ['string']
        ];
    }

    /**
     * @dataProvider dataForGetApiKey
     */
    public function testGetApiKey($apiKey)
    {
        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::API_KEY,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($apiKey);

        $this->assertSame(
            (string) $apiKey,
            $this->config->getApiKey()
        );
    }

    public function dataForGetIntegration()
    {
        return [
            [false, false],
            [true, false],
            [false, true],
            [true, true]
        ];
    }

    /**
     * @dataProvider dataForGetIntegration
     */
    public function testGetIntegration($currentDataHash, $dataHash)
    {
        $this->integrationServiceMock
            ->expects($this->once())
            ->method('findByName')
            ->with(\DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::INTEGRATION_NAME)
            ->willReturn('integrationServiceMock::findByName');

        $this->integrationFactoryMock
            ->expects($this->once())
            ->method('create')
            ->with([
                'integration' => 'integrationServiceMock::findByName',
                'environment' => $this->environmentMock
            ])
            ->willReturn($this->integrationMock);

        $this->scopeConfigMock
            ->expects($this->once())
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Data\EnvironmentInterface::DATA_HASH,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn($currentDataHash);

        $this->integrationMock
            ->expects($this->atMost(3))
            ->method('getEnvironment')
            ->willReturn($this->environmentMock);

        $this->environmentMock
            ->expects($this->at(0))
            ->method('getDataHash')
            ->willReturn($dataHash);

        if ($currentDataHash !== $dataHash) {
            $this->integrationMock
                ->expects($this->once())
                ->method('applyEnvironment')
                ->with($this->environmentMock)
                ->willReturn($this->integrationMock);

            $this->environmentMock
                ->expects($this->at(1))
                ->method('getDataHash')
                ->willReturn($currentDataHash);

            $this->writerMock
                ->expects($this->once())
                ->method('save')
                ->with(
                    \DragDropr\Magento2\Api\Data\EnvironmentInterface::DATA_HASH,
                    $currentDataHash,
                    \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );

            $this->cacheTypeListMock
                ->expects($this->once())
                ->method('cleanType')
                ->with(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        }

        $this->assertSame(
            $this->integrationMock,
            $this->config->getIntegration()
        );
    }

    public function testDisableIntegration()
    {
        $this->testGetIntegration(true, true);
        $this->integrationMock
            ->expects($this->once())
            ->method('disable');
        $this->config->disableIntegration();
    }

    public function dataForEndpoints()
    {
        return [
            [],
            [false, false],
            [false, true],
            [true, false],
            [true, true]
        ];
    }

    /**
     * @dataProvider dataForEndpoints
     */
    public function testGetPageEndpoint($hasEnvironment = null, $hasEnvironmentValue = false)
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::PAGE_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::getValue');

        $this->environmentMock
            ->expects($this->atMost(2))
            ->method('getPageEndpoint')
            ->willReturn($hasEnvironmentValue ? 'environmentMock::getPageEndpoint' : null);

        $this->assertSame(
            $hasEnvironment && $hasEnvironmentValue ? 'environmentMock::getPageEndpoint' : 'scopeConfigMock::getValue',
            $this->config->getPageEndpoint(
                $hasEnvironment ? $this->environmentMock : null
            )
        );
    }

    /**
     * @dataProvider dataForEndpoints
     */
    public function testGetCategoryEndpoint($hasEnvironment = null, $hasEnvironmentValue = false)
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::CATEGORY_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::getValue');

        $this->environmentMock
            ->expects($this->atMost(2))
            ->method('getCategoryEndpoint')
            ->willReturn($hasEnvironmentValue ? 'environmentMock::getCategoryEndpoint' : null);

        $this->assertSame(
            $hasEnvironment && $hasEnvironmentValue ? 'environmentMock::getCategoryEndpoint' : 'scopeConfigMock::getValue',
            $this->config->getCategoryEndpoint(
                $hasEnvironment ? $this->environmentMock : null
            )
        );
    }

    /**
     * @dataProvider dataForEndpoints
     */
    public function testGetDefaultEndpoint($hasEnvironment = null, $hasEnvironmentValue = false)
    {
        $this->scopeConfigMock
            ->method('getValue')
            ->with(
                \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::DEFAULT_ENDPOINT,
                \Magento\Framework\App\Config\ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            )
            ->willReturn('scopeConfigMock::getValue');

        $this->environmentMock
            ->expects($this->atMost(2))
            ->method('getDefaultEndpoint')
            ->willReturn($hasEnvironmentValue ? 'environmentMock::getDefaultEndpoint' : null);

        $this->assertSame(
            $hasEnvironment && $hasEnvironmentValue ? 'environmentMock::getDefaultEndpoint' : 'scopeConfigMock::getValue',
            $this->config->getDefaultEndpoint(
                $hasEnvironment ? $this->environmentMock : null
            )
        );
    }
}
