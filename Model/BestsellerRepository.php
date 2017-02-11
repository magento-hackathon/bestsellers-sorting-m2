<?php
/**
 * Bestsellers sorting of products in categories for Magento 2.
 * Copyright (C) 2016  2017 Firegento Hackathon
 * 
 * This file is part of MagentoHackathon/BestsellersSorting.
 * 
 * MagentoHackathon/BestsellersSorting is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

namespace MagentoHackathon\BestsellersSorting\Model;

use MagentoHackathon\BestsellersSorting\Api\BestsellerRepositoryInterface;
use MagentoHackathon\BestsellersSorting\Api\Data\BestsellerSearchResultsInterfaceFactory;
use MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Reflection\DataObjectProcessor;
use MagentoHackathon\BestsellersSorting\Model\ResourceModel\Bestseller as ResourceBestseller;
use MagentoHackathon\BestsellersSorting\Model\ResourceModel\Bestseller\CollectionFactory as BestsellerCollectionFactory;
use Magento\Store\Model\StoreManagerInterface;

class BestsellerRepository implements BestsellerRepositoryInterface
{

    protected $resource;

    protected $BestsellerFactory;

    protected $BestsellerCollectionFactory;

    protected $searchResultsFactory;

    protected $dataObjectHelper;

    protected $dataObjectProcessor;

    protected $dataBestsellerFactory;

    private $storeManager;


    /**
     * @param ResourceBestseller $resource
     * @param BestsellerFactory $bestsellerFactory
     * @param BestsellerInterfaceFactory $dataBestsellerFactory
     * @param BestsellerCollectionFactory $bestsellerCollectionFactory
     * @param BestsellerSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceBestseller $resource,
        BestsellerFactory $bestsellerFactory,
        BestsellerInterfaceFactory $dataBestsellerFactory,
        BestsellerCollectionFactory $bestsellerCollectionFactory,
        BestsellerSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
    ) {
        $this->resource = $resource;
        $this->bestsellerFactory = $bestsellerFactory;
        $this->bestsellerCollectionFactory = $bestsellerCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataBestsellerFactory = $dataBestsellerFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
    }

    /**
     * {@inheritdoc}
     */
    public function save(
        \MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface $bestseller
    ) {
        /* if (empty($bestseller->getStoreId())) {
            $storeId = $this->storeManager->getStore()->getId();
            $bestseller->setStoreId($storeId);
        } */
        try {
            $this->resource->save($bestseller);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the bestseller: %1',
                $exception->getMessage()
            ));
        }
        return $bestseller;
    }

    /**
     * {@inheritdoc}
     */
    public function getById($bestsellerId)
    {
        $bestseller = $this->bestsellerFactory->create();
        $bestseller->load($bestsellerId);
        if (!$bestseller->getId()) {
            throw new NoSuchEntityException(__('Bestseller with id "%1" does not exist.', $bestsellerId));
        }
        return $bestseller;
    }

    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
    ) {
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        
        $collection = $this->bestsellerCollectionFactory->create();
        foreach ($criteria->getFilterGroups() as $filterGroup) {
            foreach ($filterGroup->getFilters() as $filter) {
                if ($filter->getField() === 'store_id') {
                    $collection->addStoreFilter($filter->getValue(), false);
                    continue;
                }
                $condition = $filter->getConditionType() ?: 'eq';
                $collection->addFieldToFilter($filter->getField(), [$condition => $filter->getValue()]);
            }
        }
        $searchResults->setTotalCount($collection->getSize());
        $sortOrders = $criteria->getSortOrders();
        if ($sortOrders) {
            /** @var SortOrder $sortOrder */
            foreach ($sortOrders as $sortOrder) {
                $collection->addOrder(
                    $sortOrder->getField(),
                    ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                );
            }
        }
        $collection->setCurPage($criteria->getCurrentPage());
        $collection->setPageSize($criteria->getPageSize());
        $items = [];
        
        foreach ($collection as $bestsellerModel) {
            $bestsellerData = $this->dataBestsellerFactory->create();
            $this->dataObjectHelper->populateWithArray(
                $bestsellerData,
                $bestsellerModel->getData(),
                'MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface'
            );
            $items[] = $this->dataObjectProcessor->buildOutputDataArray(
                $bestsellerData,
                'MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface'
            );
        }
        $searchResults->setItems($items);
        return $searchResults;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(
        \MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface $bestseller
    ) {
        try {
            $this->resource->delete($bestseller);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Bestseller: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function deleteById($bestsellerId)
    {
        return $this->delete($this->getById($bestsellerId));
    }
}
