<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Model\Retailer;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Techvoot\StoreLocator\Api\Data\RetailerTimeSlotInterface;
use Techvoot\StoreLocator\Model\Data\RetailerTimeSlotConverter;
use Techvoot\StoreLocator\Model\ResourceModel\RetailerTimeSlot as TimeSlotResource;

/**
 * Read Handler for Retailer Special Opening Hours.
 */
class SpecialOpeningHoursReadHandler implements ExtensionInterface
{
    public function __construct(
        private RetailerTimeSlotConverter $converter,
        private TimeSlotResource $resource
    ) {
    }

    /**
     * @inheritdoc
     */
    public function execute($entity, $arguments = [])
    {
        $timeSlots = $this->resource->getTimeSlots((int) $entity->getId(), 'special_opening_hours');

        $openingHours = $this->converter->toEntity($timeSlots, RetailerTimeSlotInterface::DATE_FIELD);

        ksort($openingHours);
        $entity->getExtensionAttributes()->setSpecialOpeningHours($openingHours);
        $entity->setSpecialOpeningHours($openingHours);

        return $entity;
    }
}
