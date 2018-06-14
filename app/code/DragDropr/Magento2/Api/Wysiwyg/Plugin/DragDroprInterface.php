<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Wysiwyg\Plugin;

interface DragDroprInterface extends \DragDropr\Magento2\Api\Wysiwyg\PluginInterface
{
    /**
     * Get plugin config
     *
     * @return \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface
     */
    public function getConfig();
}
