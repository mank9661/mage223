<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model\Wysiwyg\Plugin;

class DragDroprTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface
     */
    protected $configMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\View\Asset\Repository
     */
    protected $assetRepositoryMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Backend\Model\UrlInterface
     */
    protected $urlMock;

    /**
     * @var \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDroprInterface
     */
    protected $dragDropr;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->configMock = $this->createMock(\DragDropr\Magento2\Model\Wysiwyg\Plugin\DragDropr\Config::class);
        $this->assetRepositoryMock = $this->createMock(\Magento\Framework\View\Asset\Repository::class);
        $this->urlMock = $this->createMock(\Magento\Backend\Model\UrlInterface::class);
        $this->dragDropr = $this->objectManager->getObject(
            \DragDropr\Magento2\Model\Wysiwyg\Plugin\DragDropr::class,
            [
                'config' => $this->configMock,
                'assetRepository' => $this->assetRepositoryMock,
                'url' => $this->urlMock
            ]
        );
    }

    public function dataForIsEnabled()
    {
        return [
            [false],
            [true]
        ];
    }

    /**
     * @dataProvider dataForIsEnabled
     */
    public function testGetPluginConfig($isEnabled)
    {
        $this->assetRepositoryMock
            ->expects($this->any())
            ->method('getUrl')
            ->with('DragDropr_Magento2::wysiwyg/tiny_mce/plugins/dragdropr/editor_plugin.js')
            ->willReturn('assetRepositoryMock::getMock');

        $this->configMock
            ->expects($this->once())
            ->method('isEnabled')
            ->willReturn($isEnabled);

        $this->assertSame(
            \DragDropr\Magento2\Model\Wysiwyg\Plugin\DragDropr::PLUGIN_NAME,
            $this->dragDropr->getName()
        );
        $this->assertSame(
            'assetRepositoryMock::getMock',
            $this->dragDropr->getWysiwygJsPluginSrc()
        );
        $this->assertSame($this->configMock, $this->dragDropr->getConfig());
        $this->assertEquals(
            [
                'name' => $this->dragDropr->getName(),
                'src' => $this->dragDropr->getWysiwygJsPluginSrc(),
                'options' => [
                    'title'   => __('DragDropr'),
                    'id'      => [
                        'search'  => ['html_id'],
                        'subject' => $this->dragDropr->getName() . '-{{html_id}}'
                    ],
                    'onclick' => [
                        'search'  => ['html_id'],
                        'subject' => 'window.DragDropr.execute(\'{{html_id}}\');'
                    ],
                    'class' => 'open-dragdropr PluginInterface',
                    'enabled' => $isEnabled
                ]
            ],
            $this->dragDropr->getPluginConfig()
        );
    }
}
