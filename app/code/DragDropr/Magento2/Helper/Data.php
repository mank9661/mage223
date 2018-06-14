<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Helper;

/**
 * Class Data
 *
 * @package DragDropr\Magento2\Helper
 */
class Data
{
    /**
     * Generate hash from given data
     *
     * @param array $data
     * @return string
     */
    public function generateDataHash(array $data)
    {
        return hash('SHA384', implode('|', $data));
    }
}
