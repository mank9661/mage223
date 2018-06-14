<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api;

interface CategoryRepositoryInterface
{
    /**
     * Get all available root categories
     *
     * @return \DragDropr\Magento2\Api\Data\RootCategoryInterface[]
     */
    public function getRootCategories();
}
