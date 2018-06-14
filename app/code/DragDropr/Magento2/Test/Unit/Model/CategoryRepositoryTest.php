<?php
/**
 * @category    DragDropr
 * @package     DragDropr_Magento2
 * @author      Ricards Zalitis with THANKS to atipso GmbH <hello@dragdropr.com>
 * @copyright   2018 atipso GmbH
 * @license     http://license.mageshops.com/  Unlimited Commercial License
 */

namespace DragDropr\Magento2\Test\Unit\Model;

class CategoryRepositoryTest extends \DragDropr\Magento2\Test\Unit\TestCase
{
    /**
     * @var \Magento\Framework\TestFramework\Unit\Helper\ObjectManager
     */
    protected $objectManager;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Model\ResourceModel\Category\Collection
     */
    protected $collectionMock;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|\Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $collectionFactoryMock;

    /**
     * @var \DragDropr\Magento2\Api\CategoryRepositoryInterface
     */
    protected $categoryRepository;

    protected function setUp()
    {
        $this->objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);
        $this->collectionMock = $this->createMock(\Magento\Catalog\Model\ResourceModel\Category\Collection::class);
        $this->collectionFactoryMock = $this->createPartialMock(
            \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory::class,
            ['create']
        );
        $this->categoryRepository = $this->objectManager->getObject(
            \DragDropr\Magento2\Model\CategoryRepository::class,
            [
                'categoryCollectionFactory' => $this->collectionFactoryMock
            ]
        );
    }

    public function testGetRootCategories()
    {
        $this->collectionFactoryMock
            ->expects($this->once())
            ->method('create')
            ->willReturn($this->collectionMock);

        $this->collectionMock
            ->expects($this->once())
            ->method('addAttributeToSelect')
            ->with('name')
            ->willReturn($this->collectionMock);
        $this->collectionMock
            ->expects($this->once())
            ->method('addRootLevelFilter');
        $this->collectionMock
            ->expects($this->once())
            ->method('getItems')
            ->willReturn($this->collectionMock);

        $this->assertSame(
            $this->collectionMock,
            $this->categoryRepository->getRootCategories()
        );
    }
}
