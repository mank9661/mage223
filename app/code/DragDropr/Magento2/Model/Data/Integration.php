<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model\Data;

use \DragDropr\Magento2\Api\Data\IntegrationInterface;

/**
 * Class Integration
 *
 * @package DragDropr\Magento2\Model
 */
class Integration implements IntegrationInterface
{
    /**
     * @var \Magento\Integration\Api\IntegrationServiceInterface
     */
    private $integrationService;

    /**
     * @var \Magento\Integration\Api\OauthServiceInterface
     */
    private $oauthService;

    /**
     * @var \Magento\Integration\Model\Integration
     */
    private $integration;

    /**
     * @var \DragDropr\Magento2\Api\IntegrationConfigInterface
     */
    private $integrationConfig;

    /**
     * @var \DragDropr\Magento2\Api\Data\EnvironmentInterface|null
     */
    private $environment;

    /**
     * @var array|null|false
     */
    private $defaultConfig;

    /**
     * Integration constructor
     *
     * @param \Magento\Integration\Api\IntegrationServiceInterface $integrationService
     * @param \Magento\Integration\Api\OauthServiceInterface $oauthService
     * @param \Magento\Integration\Model\Integration $integration
     * @param \DragDropr\Magento2\Api\IntegrationConfigInterface $integrationConfig
     * @param \DragDropr\Magento2\Api\Data\EnvironmentInterface|null $environment
     */
    public function __construct(
        \Magento\Integration\Api\IntegrationServiceInterface $integrationService,
        \Magento\Integration\Api\OauthServiceInterface $oauthService,
        \Magento\Integration\Model\Integration $integration,
        \DragDropr\Magento2\Api\IntegrationConfigInterface $integrationConfig,
        \DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null
    ) {
        $this->integrationService = $integrationService;
        $this->oauthService = $oauthService;
        $this->integration = $integration;
        $this->integrationConfig = $integrationConfig;
        $this->environment = $environment;
    }

    /**
     * Get integration default config if possible
     *
     * @param string|null $key
     * @return array|false
     */
    public function getDefaultConfig($key = null)
    {
        if ($this->defaultConfig === null) {
            $this->defaultConfig = $this->integrationConfig->getIntegration($this->integration->getName());

            if (! $this->defaultConfig) {
                $this->defaultConfig = false;
            }
        }

        if ($key) {
            return ! empty($this->defaultConfig[$key]) ? $this->defaultConfig[$key] : false;
        }

        return $this->defaultConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->integration->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->integration->getName();
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail()
    {
        return $this->integration->getEmail();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null)
    {
        if ($environment) {
            // Fallback to default
            if ($environment->getEndpoint() === false) {
                return $this->getDefaultConfig('endpoint_url');
            }

            return $environment->getEndpoint();
        }

        return $this->integration->getEndpoint();
    }

    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return ! $this->getId() ? static::STATUS_INACTIVE :  $this->integration->getStatus();
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentityLinkUrl(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null)
    {
        if ($environment) {
            // Fallback to default
            if ($environment->getIdentityLinkUrl() === false) {
                return $this->getDefaultConfig('identity_link_url');
            }

            return $environment->getIdentityLinkUrl();
        }

        return $this->integration->getIdentityLinkUrl();
    }

    /**
     * {@inheritdoc}
     */
    public function getCreatedAt()
    {
        return $this->integration->getCreatedAt();
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdatedAt()
    {
        return $this->integration->getUpdatedAt();
    }

    /**
     * {@inheritdoc}
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function disable()
    {
        $currentIntegration = $this->integration;
        $this->oauthService->deleteIntegrationToken($currentIntegration->getConsumerId());
        $this->integration = $this->integrationService->update([
            'integration_id' => $currentIntegration->getId(),
            'name' => $currentIntegration->getName(),
            'status' => IntegrationInterface::STATUS_INACTIVE,
            'resource' => $this->getDefaultConfig('resource')
        ]);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function applyEnvironment(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment)
    {
        $currentIntegration = $this->integration;
        $environmentData = [
            'integration_id' => $currentIntegration->getId(),
            'name' => $currentIntegration->getName(),
            'endpoint' => $this->getEndpoint($environment),
            'identity_link_url' => $this->getIdentityLinkUrl($environment),
            'status' => IntegrationInterface::STATUS_INACTIVE,// can be changed to STATUS_RECREATED starting with 2.1.x
            'resource' => $this->getDefaultConfig('resource')
        ];
        $this->oauthService->deleteIntegrationToken($currentIntegration->getConsumerId());
        $this->integration = $this->integrationService->update($environmentData);
        return $this;
    }
}
