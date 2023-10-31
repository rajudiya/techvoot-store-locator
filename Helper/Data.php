<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Techvoot\Retailer\Api\Data\RetailerInterface;
use Techvoot\StoreLocator\Model\Url;

/**
 * Store locator helper.
 */
class Data extends AbstractHelper
{
    public function __construct(
        Context $context,
        private Url $urlModel
    ) {
        parent::__construct($context);
    }

    /**
     * Store locator home URL.
     */
    public function getHomeUrl(?int $storeId = null): string
    {
        return $this->urlModel->getHomeUrl($storeId);
    }

    /**
     * Retailer URL.
     */
    public function getRetailerUrl(RetailerInterface $retailer): string
    {
        return $this->urlModel->getUrl($retailer);
    }
}
