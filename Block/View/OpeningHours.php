<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Block\View;

use Magento\Framework\Locale\ListsInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template\Context;
use Techvoot\StoreLocator\Api\Data\RetailerTimeSlotInterface;
use Techvoot\StoreLocator\Block\AbstractView;
use Techvoot\StoreLocator\Helper\Schedule;
use Techvoot\StoreLocator\Model\Retailer\ScheduleManagement;

/**
 * Opening Hours display block.
 */
class OpeningHours extends AbstractView
{
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        private ScheduleManagement $scheduleManager,
        private Schedule $scheduleHelper,
        private ListsInterface $localeList,
        array $data = []
    ) {
        parent::__construct($context, $coreRegistry, $data);
    }

    /**
     * @inheritdoc
     */
    public function getJsLayout()
    {
        $jsLayout = $this->jsLayout;

        if (!isset($jsLayout['components']['techvoot-storelocator-store']['schedule'])) {
            $jsLayout['components']['techvoot-storelocator-store']['schedule'] = [];
        }
        $jsLayout['components']['techvoot-storelocator-store']['retailerId'] = $this->getRetailer()->getId();
        $jsLayout['components']['techvoot-storelocator-store']['schedule']   = array_merge(
            $jsLayout['components']['techvoot-storelocator-store']['schedule'],
            $this->scheduleHelper->getConfig(),
            [
                'calendar' => $this->scheduleManager->getCalendar($this->getRetailer()),
                'openingHours' => $this->getWeekOpeningHours(),
                'specialOpeningHours' => $this->getRetailer()->getData('special_opening_hours'),
            ]
        );

        return json_encode($jsLayout);
    }

    /**
     * Retrieve Week Opening Hours.
     */
    public function getWeekOpeningHours(): array
    {
        return $this->scheduleManager->getWeekOpeningHours($this->getRetailer());
    }

    /**
     * Retrieve Opening Hours with schema.org compliant format.
     *
     * @see http://schema.org/openingHours
     */
    public function getOpeningHoursMicroFormat(): string
    {
        $days   = $this->localeList->getOptionWeekdays(true, true);
        $microData = [];
        $microDataDays = [];

        foreach ($this->getWeekOpeningHours() as $day => $openingHours) {
            if (!empty($openingHours) && isset($days[$day]) && isset($days[$day]['value'])) {
                $dayCode = ucfirst(substr($days[$day]['value'], 0, 2));
                $microData[$dayCode] = [];
                /** @var RetailerTimeSlotInterface $openingHour */
                foreach ($openingHours as $openingHour) {
                    $microData[$dayCode][] = $openingHour->getStartTime() . '-' . $openingHour->getEndTime();
                }
                $microDataDays[] = $dayCode . ' ' . implode(' ', $microData[$dayCode]);
            }
        }

        return implode(',', $microDataDays);
    }
}
