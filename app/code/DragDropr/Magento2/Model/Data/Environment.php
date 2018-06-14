<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model\Data;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use \DragDropr\Magento2\Api\Data\EnvironmentInterface;
use \DragDropr\Magento2\Api\Data\TestingEnvironmentsInterface;
use \DragDropr\Magento2\Helper\Data;

/**
 * Class Environment
 *
 * @package DragDropr\Magento2\Model
 */
class Environment implements EnvironmentInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var Data
     */
    private $dataHelper;

    private $mode;
    private $identityLink;
    private $endpoint;
    private $pageEndpoint;
    private $categoryEndpoint;
    private $testingEnabled;
    private $defaultEndpoint;

    /**
     * Environment constructor
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param Data $dataHelper
     */
    public function __construct(ScopeConfigInterface $scopeConfig, Data $dataHelper)
    {
        $this->scopeConfig = $scopeConfig;
        $this->dataHelper = $dataHelper;
        $this->mode = $this->scopeConfig->getValue(
            static::TESTING_MODE,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->identityLink = $this->scopeConfig->getValue(
            static::IDENTITY_LINK,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->endpoint = $this->scopeConfig->getValue(
            static::ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->pageEndpoint = $this->scopeConfig->getValue(
            static::PAGE_ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->categoryEndpoint = $this->scopeConfig->getValue(
            static::CATEGORY_ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->testingEnabled = (bool) $this->scopeConfig->getValue(
            static::TESTING_ENABLED,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
        $this->defaultEndpoint = $this->scopeConfig->getValue(
            static::DEFAULT_ENDPOINT,
            ScopeConfigInterface::SCOPE_TYPE_DEFAULT
        );
    }

    /**
     * {@inheritdoc}
     */
    public function isDevelopment()
    {
        return $this->testingEnabled;
    }

    /**
     * {@inheritdoc}
     */
    public function getMode()
    {
        return $this->isDevelopment() ? $this->mode : static::MODE_PRODUCTION;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentityLinkUrl()
    {
        switch ($this->getMode()) {
            case TestingEnvironmentsInterface::MODE_INTERNAL:
                return null;
            case TestingEnvironmentsInterface::MODE_EXTERNAL:
                if (! empty($this->identityLink) && filter_var($this->identityLink, FILTER_VALIDATE_URL)) {
                    return $this->identityLink;
                }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getEndpoint()
    {
        switch ($this->getMode()) {
            case TestingEnvironmentsInterface::MODE_INTERNAL:
                return null;
            case TestingEnvironmentsInterface::MODE_EXTERNAL:
                if (! empty($this->endpoint) && filter_var($this->endpoint, FILTER_VALIDATE_URL)) {
                    return $this->endpoint;
                }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getPageEndpoint()
    {
        switch ($this->getMode()) {
            case TestingEnvironmentsInterface::MODE_INTERNAL:
            case TestingEnvironmentsInterface::MODE_EXTERNAL:
                if (! empty($this->pageEndpoint)) {
                    return $this->pageEndpoint;
                }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryEndpoint()
    {
        switch ($this->getMode()) {
            case TestingEnvironmentsInterface::MODE_INTERNAL:
            case TestingEnvironmentsInterface::MODE_EXTERNAL:
                if (! empty($this->categoryEndpoint)) {
                    return $this->categoryEndpoint;
                }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultEndpoint()
    {
        switch ($this->getMode()) {
            case TestingEnvironmentsInterface::MODE_INTERNAL:
            case TestingEnvironmentsInterface::MODE_EXTERNAL:
                if (! empty($this->defaultEndpoint)) {
                    return $this->defaultEndpoint;
                }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getDataHash()
    {
        $data = [$this->getMode()];

        if ($this->isDevelopment()) {
            if ($this->getMode() == TestingEnvironmentsInterface::MODE_EXTERNAL) {
                $data[] = $this->getIdentityLinkUrl();
                $data[] = $this->getEndpoint();
            }
        }

        return $this->dataHelper->generateDataHash($data);
    }
}
