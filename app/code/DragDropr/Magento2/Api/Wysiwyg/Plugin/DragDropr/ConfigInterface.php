<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr;

interface ConfigInterface extends \DragDropr\Magento2\Api\Wysiwyg\Plugin\ConfigInterface
{
    /**
     * Get integration name
     */
    const INTEGRATION_NAME = 'DragDropr';

    /**
     * Path to "Enable DragDropr" configuration setting
     */
    const IS_ENABLED = 'dragdropr_config/general/is_enabled';

    /**
     * Path to DragDropr Api key
     */
    const API_KEY = 'dragdropr_config/general/apy_key';

    /**
     * Path to page entity endpoint
     */
    const PAGE_ENDPOINT = 'dragdropr_config/general/page_endpoint';

    /**
     * Path to category entity endpoint
     */
    const CATEGORY_ENDPOINT = 'dragdropr_config/general/category_endpoint';

    /**
     * Path to default entity endpoint
     */
    const DEFAULT_ENDPOINT = 'dragdropr_config/general/default_endpoint';

    /**
     * Set DragDropr API key
     *
     * @param string $apiKey
     * @return string
     */
    public function setApiKey($apiKey);

    /**
     * Get DragDropr Api key
     *
     * @return string
     */
    public function getApiKey();

    /**
     * Get DragDropr integration
     *
     * @return \DragDropr\Magento2\Api\Data\IntegrationInterface
     */
    public function getIntegration();

    /**
     * Disables DragDropr integration
     *
     * @return \DragDropr\Magento2\Api\Data\IntegrationInterface
     */
    public function disableIntegration();

    /**
     * Get page entity endpoint
     *
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     * @return string
     */
    public function getPageEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null);

    /**
     * Get category entity endpoint
     *
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     * @return string
     */
    public function getCategoryEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null);

    /**
     * Get default entity endpoint
     *
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     * @return string
     */
    public function getDefaultEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null);
}
