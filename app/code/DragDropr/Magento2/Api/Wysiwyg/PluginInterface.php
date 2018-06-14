<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Wysiwyg;

interface PluginInterface
{
    /**
     * Get plugin internal name
     *
     * @return string
     */
    public function getName();

    /**
     * Get plugin config
     *
     * @return \DragDropr\Magento2\Api\Wysiwyg\Plugin\ConfigInterface
     */
    public function getConfig();

    /**
     * Get path to wysiwyg plugin source
     *
     * @return string
     */
    public function getWysiwygJsPluginSrc();

    /**
     * Get plugin configuration settings
     *
     * @return array
     */
    public function getPluginConfig();
}
