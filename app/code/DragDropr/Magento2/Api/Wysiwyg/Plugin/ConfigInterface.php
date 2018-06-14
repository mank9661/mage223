<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Wysiwyg\Plugin;

interface ConfigInterface extends \Magento\Store\Model\ScopeInterface
{
    /**
     * Whether plugin is enabled
     *
     * @return boolean
     */
    public function isEnabled();
}
