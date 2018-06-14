<?php

namespace WeltPixel\LazyLoading\Helper;

/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper {

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var array
     */
    protected $_lazyLoadOptions;

    /**
     * @var \Magento\Framework\View\Asset\Repository
     */
    protected $_assetRepo;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\View\Asset\Repository
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\View\Asset\Repository $assetRepo
    ) {
        parent::__construct($context);

        $this->_storeManager = $storeManager;
        $this->_assetRepo = $assetRepo;
        $this->_lazyLoadOptions = $this->scopeConfig->getValue('weltpixel_lazy_loading', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param int $storeId
     * @return mixed
     */
    public function isEnabled($storeId = 0) {
        if ($storeId) {
            return $this->scopeConfig->getValue('weltpixel_lazy_loading/general/enable', \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $storeId);
        } else {
            return $this->_lazyLoadOptions['general']['enable'];
        }
    }

    /**
     * @return string
     */
    public function getImageLoader() {
        return $this->_assetRepo->getUrlWithParams('WeltPixel_LazyLoading::images/Loader.gif', []);
    }
}