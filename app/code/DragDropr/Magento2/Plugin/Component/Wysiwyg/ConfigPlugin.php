<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Plugin\Component\Wysiwyg;

use \Magento\Framework\DataObject;
use \DragDropr\Magento2\Api\Wysiwyg\PluginInterface;

/**
 * Class ConfigPlugin
 *
 * @package DragDropr\Magento2\Plugin\Component\Wysiwyg
 */
class ConfigPlugin
{
    /**
     * @var PluginInterface[]
     */
    private $additionalPlugins;

    /**
     * ConfigPlugin constructor
     *
     * @param PluginInterface[] $additionalPlugins
     */
    public function __construct(array $additionalPlugins = [])
    {
        $this->additionalPlugins = $additionalPlugins;
    }

    /**
     * Append additional plugin settings to WYSIWYG configuration
     *
     * @param $subject
     * @param DataObject $result
     * @return DataObject
     */
    public function afterGetConfig($subject, DataObject $result)
    {
        $plugins = $result->getData('plugins');

        if (! $result->hasData(static::class)) {
            $additionalPlugins = [];

            foreach ($this->additionalPlugins as $plugin) {
                if (! $plugin->getConfig()->isEnabled()) {
                    continue;
                }

                $additionalPlugins[] = $plugin->getPluginConfig();
            }

            if ($additionalPlugins) {
                $plugins = array_merge($plugins, $additionalPlugins);
                $result->setData('plugins', $plugins);
            }

            $result->setData(static::class, true);
        }

        return $result;
    }
}
