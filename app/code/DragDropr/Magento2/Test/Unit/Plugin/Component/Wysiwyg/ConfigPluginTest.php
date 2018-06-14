<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Plugin\Component\Wysiwyg;

class ConfigPluginTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Framework\DataObject
     */
    protected $dataObjectMock;

    /**
     * @var \DragDropr\Magento2\Plugin\Component\Wysiwyg\ConfigPlugin
     */
    protected $plugin;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->dataObjectMock = $this->createMock(\Magento\Framework\DataObject::class);
    }

    public function dataForAfterGetConfig()
    {
        return [
            [[false], false],
            [[true], false],
            [[false], true],
            [[true], true],
            [[true, false], true],
            [[true, true], true]
        ];
    }

    /**
     * @dataProvider dataForAfterGetConfig
     */
    public function testAfterGetConfig(array $plugins, $beenApplied)
    {
        /**
         * @var $additionalPlugins \PHPUnit_Framework_MockObject_MockObject[]|\DragDropr\Magento2\Api\Wysiwyg\PluginInterface[]
         */
        $additionalPlugins = [];
        $expectedResult = [];

        $this->dataObjectMock
            ->expects($this->once())
            ->method('getData')
            ->with('plugins')
            ->willReturn([]);

        $this->dataObjectMock
            ->expects($this->once())
            ->method('hasData')
            ->with(\DragDropr\Magento2\Plugin\Component\Wysiwyg\ConfigPlugin::class)
            ->willReturn($beenApplied);

        if ($beenApplied) {
            $this->dataObjectMock
                ->expects($this->never())
                ->method('setData');
        } else {
            foreach ($plugins as $key => $enabled) {
                $pluginMock = $this->createMock(\DragDropr\Magento2\Api\Wysiwyg\PluginInterface::class);
                $configMock = $this->createMock(\DragDropr\Magento2\Api\Wysiwyg\Plugin\ConfigInterface::class);

                $pluginMock
                    ->expects($this->once())
                    ->method('getConfig')
                    ->willReturn($configMock);

                $configMock
                    ->expects($this->once())
                    ->method('isEnabled')
                    ->willReturn($enabled);

                if ($enabled) {
                    $pluginMock
                        ->expects($this->once())
                        ->method('getPluginConfig')
                        ->willReturn('configMock[' . $key . ']getPluginConfig');
                    $expectedResult[] = 'configMock[' . $key . ']getPluginConfig';
                } else {
                    $pluginMock
                        ->expects($this->never())
                        ->method('getPluginConfig');
                }

                $additionalPlugins[] = $pluginMock;
            }

            if ($expectedResult) {
                $this->dataObjectMock
                    ->expects($this->at(2))
                    ->method('setData')
                    ->with('plugins', $expectedResult)
                    ->willReturn($this->dataObjectMock);
                $this->dataObjectMock
                    ->expects($this->at(3))
                    ->method('setData')
                    ->with(\DragDropr\Magento2\Plugin\Component\Wysiwyg\ConfigPlugin::class, true)
                    ->willReturn($this->dataObjectMock);
            } else {
                $this->dataObjectMock
                    ->expects($this->once())
                    ->method('setData')
                    ->with(\DragDropr\Magento2\Plugin\Component\Wysiwyg\ConfigPlugin::class, true)
                    ->willReturn($this->dataObjectMock);
            }
        }

        $this->plugin = $this->objectManager->getObject(
            \DragDropr\Magento2\Plugin\Component\Wysiwyg\ConfigPlugin::class,
            [
                'additionalPlugins' => $additionalPlugins
            ]
        );

        $this->assertEquals(
            $this->dataObjectMock,
            $this->plugin->afterGetConfig(null, $this->dataObjectMock)
        );
    }
}
