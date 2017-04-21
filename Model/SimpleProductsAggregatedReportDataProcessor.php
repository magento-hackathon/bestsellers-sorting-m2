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

/**
 * Class is responsible to calculate
 * and save bestsellers order to product attribute
 *
 */

namespace MagentoHackathon\BestsellersSorting\Model;

class SimpleProductsAggregatedReportDataProcessor
{


    /**
     * @var ResourceModel\Bestseller
     */
    private $bestseller;
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Action
     */
    private $action;
    /**
     * @var ResourceModel\Bestseller\Collection
     */
    private $collection;

    public function __construct(
        \MagentoHackathon\BestsellersSorting\Model\ResourceModel\Bestseller $bestseller,
        \Magento\Catalog\Model\ResourceModel\Product\Action $action,
        \MagentoHackathon\BestsellersSorting\Model\ResourceModel\Bestseller\Collection $collection

    )
    {

        $this->bestseller = $bestseller;
        $this->action = $action;
        $this->collection = $collection;
    }

    public function calculate($storeId = 0)
    {
        $this->bestseller->aggregate(null, null);

        $collection = $this->collection;
        /** @var \MagentoHackathon\BestsellersSorting\Model\Bestseller $productId */
        foreach ($collection as $item) {

            /**https://magento.stackexchange.com/questions/151186/best-way-to-update-products-attribute-value */
            $this->action->updateAttributes(
                [$item->getProductId()],
                ['bestseller_order' => $item->getRatingPos()],
                $item->getStoreId());
        }


    }
}
