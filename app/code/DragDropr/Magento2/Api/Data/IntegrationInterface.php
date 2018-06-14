<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Api\Data;

interface IntegrationInterface
{
    /**
     * Integration status values
     */
    const STATUS_INACTIVE = \Magento\Integration\Model\Integration::STATUS_INACTIVE;

    const STATUS_ACTIVE = \Magento\Integration\Model\Integration::STATUS_ACTIVE;

    const STATUS_RECREATED = 2;

    const INTEGRATION_TYPE = 'MAGENTO_2_INTEGRATION';

    /**
     * Get integration identifier
     *
     * @return integer
     */
    public function getId();

    /**
     * Get integration name
     *
     * @return string
     */
    public function getName();

    /**
     * Get integration email
     *
     * @return string
     */
    public function getEmail();

    /**
     * Get integration endpoint
     *
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     * @return string
     */
    public function getEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null);

    /**
     * Get integration status
     *
     * @return int
     */
    public function getStatus();

    /**
     * Get identity link url
     *
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     * @return mixed
     */
    public function getIdentityLinkUrl(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null);

    /**
     * Get created at
     *
     * @return string
     */
    public function getCreatedAt();

    /**
     * Get updated at
     *
     * @return string
     */
    public function getUpdatedAt();

    /**
     * Get integration environment
     *
     * @return \DragDropr\Magento2\Api\Data\EnvironmentInterface|null
     */
    public function getEnvironment();

    /**
     * Disables integration
     *
     * @return $this
     */
    public function disable();

    /**
     * Applies environment to integration
     *
     * @return $this
     */
    public function applyEnvironment(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment);
}
