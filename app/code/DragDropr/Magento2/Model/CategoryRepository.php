<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Model;

use \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory;
use \DragDropr\Magento2\Api\CategoryRepositoryInterface;

/**
 * Class CategoryRepository
 *
 * @package DragDropr\Magento2\Model
 */
class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    private $categoryCollectionFactory;

    /**
     * CategoryRepository constructor
     *
     * @param CollectionFactory $categoryCollectionFactory
     */
    public function __construct(CollectionFactory $categoryCollectionFactory)
    {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getRootCategories()
    {
        $collection = $this->categoryCollectionFactory->create();
        $collection->addAttributeToSelect('name')->addRootLevelFilter();
        return $collection->getItems();
    }
}
