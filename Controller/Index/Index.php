<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Techvoot\StoreLocator\Helper\Data as StoreLocatorHelper;

/**
 * Index action (redirect to the search/index action).
 */
class Index extends Action implements HttpGetActionInterface
{
    public function __construct(Context $context, private StoreLocatorHelper $storeLocatorHelper)
    {
        parent::__construct($context);
    }

    /**
     * @inheritdoc
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $redirectUrl    = $this->storeLocatorHelper->getHomeUrl();
        $resultRedirect->setPath($redirectUrl);

        return $resultRedirect;
    }
}
