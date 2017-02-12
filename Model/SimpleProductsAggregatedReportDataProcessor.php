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

class SimpleProductsAggregatedReportDataProcessor implements DataProcessorInterface
{
    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resource;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $date;

    /**
     * @var \Magento\Eav\Model\Config
     */
    private $eavConfig;

    /**
     * SimpleProductsAggregatedReportDataProcessor constructor.
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $date
     * @param \Magento\Eav\Model\Config $eavConfig
     */
    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Magento\Eav\Model\Config $eavConfig
    )
    {
        $this->resource = $resource;
        $this->scopeConfig = $scopeConfig;
        $this->date = $date;
        $this->eavConfig = $eavConfig;
    }

    /**
     * @inheritdoc
     */
    public function calculate($storeId = 0)
    {

        $connection = $this->resource->getConnection();

        $periodDays = $this->scopeConfig->getValue(
            'catalog/group/period',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $column = 'qty_ordered';
        $mainTable = 'sales_bestsellers_aggregated_daily';

        $periodSubSelect = $connection->select();
        $ratingSubSelect = $connection->select();
        $ratingSelect = $connection->select();
        $periodCol = 't.period';

        $columns = [
            'period' => 't.period',
            'store_id' => 't.store_id',
            'product_id' => 't.product_id',
            'product_name' => 't.product_name',
            'product_price' => 't.product_price',
        ];

        $cols = array_keys($columns);
        $cols['total_qty'] = new \Zend_Db_Expr('SUM(t.' . $column . ')');
        $periodSubSelect->from(
            ['t' => $mainTable],
            $cols
        )->where(
            $periodCol . '>=?', $this->date->gmtDate('Y-m-d')
        )->where(
            $periodCol . '<=?', $this->date->gmtDate('Y-m-d')
        )->where(
            'store_id =?', $storeId
        )->group(
            ['t.store_id', 't.product_id']
        )->order(
            ['t.store_id', 'total_qty DESC']
        );

        $cols = $columns;
        $cols[$column] = 't.total_qty';
        $cols['rating_pos'] = new \Zend_Db_Expr(
            "(@pos := IF(t.`store_id` <> @prevStoreId OR {$periodCol} <> @prevPeriod, 1, @pos+1))"
        );
        $cols['prevStoreId'] = new \Zend_Db_Expr('(@prevStoreId := t.`store_id`)');
        $cols['prevPeriod'] = new \Zend_Db_Expr("(@prevPeriod := {$periodCol})");
        $ratingSubSelect->from($periodSubSelect, $cols);

        $cols = [];
        $cols['store_id'] = 't.store_id as store_id';
        $cols['product_id'] = 't.product_id as entity_id';
        $cols['rating_pos'] = 't.rating_pos as value';


        $attribute = $this->eavConfig->getAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            \MagentoHackathon\BestsellersSorting\Setup\InstallData::ATTRIBUTE_NAME
        );

        $ratingSelect->from($ratingSubSelect, $cols);

        $preQuery = "SET @pos = 0, @prevStoreId = -1, @prevPeriod = '0000-00-00'";

        //$connection->query();

        //$sql = $ratingSelect->insertFromSelect($aggregationTable, array_keys($cols));
                die($preQuery.";".$ratingSelect. PHP_EOL);

        //@todo use approach from \Magento\Reports\Model\ResourceModel\Helper::updateReportRatingPos
    }
}
