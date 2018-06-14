<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api;

interface IntegrationConfigInterface
{
    /**
     * Return list of config based integrations
     *
     * @return array
     */
    public function getIntegrations();

    /**
     * Get integration configuration by its name
     *
     * @param $integrationName
     * @return array|null
     */
    public function getIntegration($integrationName);
}
