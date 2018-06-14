<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Block;

class DataSourceTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Backend\Block\Template\Context
     */
    private $contextMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface
     */
    protected $configMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Data\IntegrationInterface
     */
    protected $integrationMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Data\EnvironmentInterface
     */
    protected $environmentMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\UrlInterface
     */
    protected $urlBuilderMock;

    /**
     * @var \DragDropr\Magento2\Block\DataSource
     */
    protected $dataSource;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->contextMock = $this->createMock(\Magento\Backend\Block\Template\Context::class);
        $this->configMock = $this->createMock(\DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface::class);
        $this->integrationMock = $this->createMock(\DragDropr\Magento2\Api\Data\IntegrationInterface::class);
        $this->environmentMock = $this->createMock(\DragDropr\Magento2\Api\Data\EnvironmentInterface::class);
        $this->urlBuilderMock = $this->createMock(\Magento\Framework\UrlInterface::class);
        $this->contextMock->method('getUrlBuilder')
            ->willReturn($this->urlBuilderMock);

        $this->dataSource = $this->objectManager->getObject(
            \DragDropr\Magento2\Block\DataSource::class,
            [
                'context' => $this->contextMock,
                'config' => $this->configMock
            ]
        );
    }

    public function dataForStoreIntegrationStatus()
    {
        return [
            [\DragDropr\Magento2\Api\Data\IntegrationInterface::STATUS_INACTIVE, false],
            [\DragDropr\Magento2\Api\Data\IntegrationInterface::STATUS_RECREATED, false],
            [\DragDropr\Magento2\Api\Data\IntegrationInterface::STATUS_ACTIVE, true]
        ];
    }

    /**
     * @dataProvider dataForStoreIntegrationStatus
     */
    public function testGetConfig($integrationStatus, $integrationActive)
    {
        $this->configMock
            ->expects($this->once())
            ->method('getIntegration')
            ->willReturn($this->integrationMock);
        $this->configMock
            ->expects($this->once())
            ->method('getApiKey')
            ->willReturn('integrationMock::getApiKey');
        $this->configMock
            ->expects($this->once())
            ->method('getPageEndpoint')
            ->with($this->environmentMock)
            ->willReturn('integrationMock::getPageEndpoint');
        $this->configMock
            ->expects($this->once())
            ->method('getCategoryEndpoint')
            ->with($this->environmentMock)
            ->willReturn('integrationMock::getCategoryEndpoint');
        $this->configMock
            ->expects($this->once())
            ->method('getDefaultEndpoint')
            ->with($this->environmentMock)
            ->willReturn('integrationMock::getDefaultEndpoint');

        $this->integrationMock
            ->expects($this->once())
            ->method('getStatus')
            ->willReturn($integrationStatus);
        $this->integrationMock
            ->expects($this->exactly(3))
            ->method('getEnvironment')
            ->willReturn($this->environmentMock);

        $this->urlBuilderMock
            ->expects($this->at(0))
            ->method('getUrl')
            ->with('adminhtml/integration')
            ->willReturn('dataSourceMock::getUrl');
        $this->urlBuilderMock
            ->expects($this->at(1))
            ->method('getBaseUrl')
            ->willReturn('dataSourceMock::getBaseUrl');
        $this->urlBuilderMock
            ->expects($this->at(2))
            ->method('getUrl')
            ->with(
                'cms/page/edit',
                [
                    'page_id' => '${pageId}'
                ]
            )
            ->willReturn('dataSourceMock::getUrl(cms/page/edit)');
        $this->urlBuilderMock
            ->expects($this->at(3))
            ->method('getUrl')
            ->with(
                'catalog/category/edit',
                [
                    'id' => '${categoryId}',
                    'store' => '${storeId}'
                ]
            )
            ->willReturn('dataSourceMock::getUrl(catalog/category/edit)');

        $this->assertEquals(
            [
                'integration' => [
                    'active' => $integrationActive,
                    'url' => 'dataSourceMock::getUrl'
                ],
                'storeUrl' => 'dataSourceMock::getBaseUrl',
                'apiKey' => 'integrationMock::getApiKey',
                'cms_page' => [
                    'endpoint' => 'integrationMock::getPageEndpoint',
                    'new_entity' => 'dataSourceMock::getUrl(cms/page/edit)'
                ],
                'catalog_category' => [
                    'endpoint' => 'integrationMock::getCategoryEndpoint',
                    'new_entity' => 'dataSourceMock::getUrl(catalog/category/edit)'
                ],
                'default' => [
                    'endpoint' => 'integrationMock::getDefaultEndpoint'
                ],
                'type' => \DragDropr\Magento2\Api\Data\IntegrationInterface::INTEGRATION_TYPE
            ],
            $this->dataSource->getConfig()
        );
    }
}
