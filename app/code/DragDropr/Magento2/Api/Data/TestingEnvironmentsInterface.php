<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Data;

interface TestingEnvironmentsInterface
{
    /**
     * Internal testing environment
     */
    const MODE_INTERNAL = 'internal';

    /**
     * External testing environment
     */
    const MODE_EXTERNAL = 'external';

    /**
     * {@inheritdoc}
     */
    public function getEnvironments();
}
