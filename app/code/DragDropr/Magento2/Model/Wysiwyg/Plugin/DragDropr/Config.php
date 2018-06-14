<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model\Wysiwyg\Plugin\DragDropr;

use \Magento\Framework\App\Cache\TypeListInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;
use \Magento\Framework\App\Config\Storage\WriterInterface;
use \Magento\Integration\Api\IntegrationServiceInterface;
use \DragDropr\Magento2\Api\Wysiwyg\Plugin\DragDropr\ConfigInterface;
use \DragDropr\Magento2\Api\Data\EnvironmentInterface;
use \DragDropr\Magento2\Api\Data\IntegrationInterface;
use \DragDropr\Magento2\Api\Data\IntegrationInterfaceFactory;

class Config implements ConfigInterface
{
    /**
     * @var IntegrationServiceInterface
     */
    private $integrationService;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var WriterInterface
     */
    private $writer;

    /**
     * @var TypeListInterface
     */
    private $cacheTypeList;

    /**
     * @var IntegrationInterface
     */
    private $integration;

    /**
     * @var EnvironmentInterface
     */
    private $environment;

    /**
     * @var IntegrationInterfaceFactory
     */
    private $integrationInterfaceFactory;

    /**
     * Config constructor
     *
     * @param IntegrationServiceInterface $integrationService
     * @param ScopeConfigInterface $scopeConfig
     * @param WriterInterface $writer
     * @param TypeListInterface $cacheTypeList
     * @param EnvironmentInterface $environment
     * @param IntegrationInterfaceFactory $integrationInterfaceFactory
     */
    public function __construct(
        IntegrationServiceInterface $integrationService,
        ScopeConfigInterface $scopeConfig,
        WriterInterface $writer,
        TypeListInterface $cacheTypeList,
        EnvironmentInterface $environment,
        IntegrationInterfaceFactory $integrationInterfaceFactory
    ) {
        $this->integrationService = $integrationService;
        $this->scopeConfig = $scopeConfig;
        $this->writer = $writer;
        $this->cacheTypeList = $cacheTypeList;
        $this->environment = $environment;
        $this->integrationInterfaceFactory = $integrationInterfaceFactory;
    }

    /**
     * Cleans config
     *
     * @return $this
     */
    public function cleanConfig()
    {
        if (method_exists($this->scopeConfig, 'clean')) {
            $this->scopeConfig->clean();
        } else {
            $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        return $this->scopeConfig->getValue(static::IS_ENABLED, static::SCOPE_STORE);
    }

    /**
     * {@inheritdoc}
     */
    public function setApiKey($apiKey)
    {
        $currentApiKey = $this->getApiKey();

        if ($currentApiKey !== $apiKey) {
            $this->writer->save(static::API_KEY, $apiKey, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);

            if (method_exists($this->scopeConfig, 'clean')) {
                $this->scopeConfig->clean();
            } else {
                $this->cacheTypeList->cleanType(\Magento\Framework\App\Cache\Type\Config::TYPE_IDENTIFIER);
            }

            $currentApiKey = $this->getApiKey();
        }

        return $currentApiKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getApiKey()
    {
        return (string) $this->scopeConfig->getValue(static::API_KEY, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    public function getIntegration()
    {
        if ($this->integration === null) {
            $this->integration = $this->integrationInterfaceFactory->create([
                'integration' => $this->integrationService->findByName(static::INTEGRATION_NAME),
                'environment' => $this->environment
            ]);

            $currentDataHash = $this->scopeConfig->getValue(
                EnvironmentInterface::DATA_HASH,
                ScopeConfigInterface::SCOPE_TYPE_DEFAULT
            );

            if ($this->integration->getEnvironment()
                &&
                $this->integration->getEnvironment()->getDataHash() !== $currentDataHash
            ) {
                $this->integration->applyEnvironment($this->integration->getEnvironment());
                $this->writer->save(
                    EnvironmentInterface::DATA_HASH,
                    $this->environment->getDataHash(),
                    ScopeConfigInterface::SCOPE_TYPE_DEFAULT
                );
                $this->cleanConfig();
            }
        }

        return $this->integration;
    }

    /**
     * {@inheritdoc}
     */
    public function disableIntegration()
    {
        return $this->getIntegration()->disable();
    }

    /**
     * {@inheritdoc}
     */
    public function getPageEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null)
    {
        if ($environment && $environment->getPageEndpoint()) {
            return $environment->getPageEndpoint();
        }

        return (string) $this->scopeConfig->getValue(static::PAGE_ENDPOINT, ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null)
    {
        if ($environment && $environment->getCategoryEndpoint()) {
            return $environment->getCategoryEndpoint();
        }

        return (string) $this->scopeConfig->getValue(
            static::CATEGORY_ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultEndpoint(\DragDropr\Magento2\Api\Data\EnvironmentInterface $environment = null)
    {
        if ($environment && $environment->getDefaultEndpoint()) {
            return $environment->getDefaultEndpoint();
        }

        return (string) $this->scopeConfig->getValue(
            static::DEFAULT_ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }
}
