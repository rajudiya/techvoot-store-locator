<?php

declare(strict_types=1);

namespace Techvoot\StoreLocator\Model\Retailer;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Techvoot\Retailer\Api\Data\RetailerExtensionInterface;
use Techvoot\Retailer\Model\Retailer;
use Techvoot\StoreLocator\Api\Data\RetailerAddressInterface;
use Techvoot\StoreLocator\Model\Data\RetailerAddressConverter as Converter;
use Techvoot\StoreLocator\Model\ResourceModel\RetailerAddress as ResourceModel;
use Techvoot\StoreLocator\Model\RetailerAddressFactory as ModelFactory;

/**
 * Retailer address read handler.
 */
class AddressReadHandler implements ExtensionInterface
{
    public function __construct(
        private ModelFactory $modelFactory,
        private ResourceModel $resource,
        private Converter $converter
    ) {
    }

    /**
     * @inheritdoc
     */
    public function execute($entity, $arguments = [])
    {
        /** @var Retailer $entity */
        $addressModel = $this->modelFactory->create();
        $addressModel->setRetailerId((int) $entity->getId());

        $this->resource->load($addressModel, $entity->getId(), RetailerAddressInterface::RETAILER_ID);

        $addressEntity = $this->converter->toEntity($addressModel);

        /** @var RetailerExtensionInterface $entityExtensionAttr */
        $entityExtensionAttr = $entity->getExtensionAttributes();
        $entityExtensionAttr->setAddress($addressEntity);
        $entity->setAddress($addressEntity);

        return $entity;
    }
}
