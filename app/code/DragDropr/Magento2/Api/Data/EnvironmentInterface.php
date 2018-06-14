<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Data;

use \DragDropr\Magento2\Api\DataHashInterface;

interface EnvironmentInterface extends DataHashInterface
{
    const MODE_PRODUCTION = 'production';

    const TESTING_ENABLED = 'dragdropr_config/integration_test/is_enabled';

    const TESTING_MODE = 'dragdropr_config/integration_test/testing_mode';

    const IDENTITY_LINK = 'dragdropr_config/integration_test/identity_link';

    const ENDPOINT = 'dragdropr_config/integration_test/endpoint';

    const PAGE_ENDPOINT = 'dragdropr_config/integration_test/page_endpoint';

    const CATEGORY_ENDPOINT = 'dragdropr_config/integration_test/category_endpoint';

    const DEFAULT_ENDPOINT = 'dragdropr_config/integration_test/default_endpoint';

    const DATA_HASH = 'dragdropr_config/integration_test/data_hash';

    /**
     * Check whether development mode is enabled
     *
     * @return boolean
     */
    public function isDevelopment();

    /**
     * Get environment mode - one of DragDropr_Magento1_Model_System_Config_Source_TestingEnvironments::MODE_ constants
     *
     * @return string
     */
    public function getMode();

    /**
     * Get identity link url
     *
     * @return string|false|null False if the endpoint is not defined, null if internal mode
     */
    public function getIdentityLinkUrl();

    /**
     * Get integration endpoint
     *
     * @return string|false|null False if the endpoint is not defined, null if internal mode
     */
    public function getEndpoint();

    /**
     * Get page entity endpoint
     *
     * @return string|null
     */
    public function getPageEndpoint();

    /**
     * Get category entity endpoint
     *
     * @return string|null
     */
    public function getCategoryEndpoint();

    /**
     * Get default entity endpoint
     *
     * @return string|null
     */
    public function getDefaultEndpoint();
}
