<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Plugin;

use Magento\Framework\DataObject;
use Techvoot\Retailer\Api\Data\RetailerExtensionInterface;
use Techvoot\Retailer\Api\Data\RetailerInterface;
use Techvoot\Seller\Ui\Component\Seller\Form\DataProvider;
use Techvoot\StoreLocator\Api\Data\RetailerAddressInterface;

/**
 * Retailer form data provider plugin.
 */
class RetailerEditFormPlugin
{
    /**
     * Add latitude and longitude to address data.
     */
    public function afterGetData(DataProvider $subject, array $result): array
    {
        $retailer = $this->getRetailer($subject);
        /** @var RetailerExtensionInterface|null $retailerExtensionAttr */
        $retailerExtensionAttr = null;

        if ($retailer !== null) {
            $retailerExtensionAttr = $retailer->getExtensionAttributes();
        }

        if ($retailer !== null && $retailerExtensionAttr && $retailerExtensionAttr->getAddress()) {
            /** @var DataObject|RetailerAddressInterface $address */
            $address = $retailerExtensionAttr->getAddress();
            $result[$retailer->getId()]['address'] = $address->getData();

            if ($address->getCoordinates()) {
                $result[$retailer->getId()]['address']['coordinates'] = [
                    'latitude'  => $address->getCoordinates()->getLatitude(),
                    'longitude' => $address->getCoordinates()->getLongitude(),
                ];
            }
        }

        return $result;
    }

    /**
     * Return the currently edited retailer.
     */
    private function getRetailer(DataProvider $dataProvider): ?RetailerInterface
    {
        $retailer = $dataProvider->getCollection()->getFirstItem();

        return $retailer instanceof RetailerInterface ? $retailer : null;
    }
}
