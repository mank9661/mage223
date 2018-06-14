<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Data;

interface RootCategoryInterface
{
    const ENTITY_ID = 'entity_id';

    const NAME = 'name';

    const LEVEL = 'level';

    /**
     * Get root category id
     *
     * @return integer
     */
    public function getId();

    /**
     * Get root category name
     *
     * @return string
     */
    public function getName();

    /**
     * Get category level
     *
     * @return integer
     */
    public function getLevel();
}
