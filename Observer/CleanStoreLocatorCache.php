<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Observer;

use Magento\Framework\App\CacheInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Indexer\CacheContext;
use Magento\Framework\Indexer\CacheContextFactory;
use Techvoot\Seller\Model\Seller;
use Techvoot\StoreLocator\Block\Search;

/**
 * Clean store locator cache observer.
 */
class CleanStoreLocatorCache implements ObserverInterface
{
    public function __construct(
        protected CacheContextFactory $cacheContextFactory,
        protected ManagerInterface $eventManager,
        protected CacheInterface $cache
    ) {
    }

    /**
     * @inheritdoc
     */
    public function execute(Observer $observer)
    {
        /** @var Seller $seller */
        $seller = $observer->getEvent()->getSeller();

        if ($seller->hasDataChanges()) {
            /** @var CacheContext $cacheContext */
            $cacheContext = $this->cacheContextFactory->create();
            $cacheContext->registerTags([Search::CACHE_TAG]);
            $this->eventManager->dispatch('clean_cache_by_tags', ['object' => $cacheContext]);
            $this->cache->clean([Search::CACHE_TAG]);
        }
    }
}
