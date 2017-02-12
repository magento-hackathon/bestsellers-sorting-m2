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

    public function __construct(
        \Magento\Framework\App\ResourceConnection $resource

    )
    {

        $this->resource = $resource;
    }

    /**
     * @inheritdoc
     */
    public function calculate()
    {

        $connection = $this->resource->getConnection();
        $column = 'qty_ordered';
        $mainTable = 'sales_bestseller_aggregated_daily';
        $type = 'month';

        $periodSubSelect = $connection->select();
        $ratingSubSelect = $connection->select();
        $ratingSelect = $connection->select();

        switch ($type) {
            case 'year':
                $periodCol = $connection->getDateFormatSql('t.period', '%Y-01-01');
                break;
            case 'month':
                $periodCol = $connection->getDateFormatSql('t.period', '%Y-%m-01');
                break;
            default:
                $periodCol = 't.period';
                break;
        }

        $columns = [
            'period' => 't.period',
            'store_id' => 't.store_id',
            'product_id' => 't.product_id',
            'product_name' => 't.product_name',
            'product_price' => 't.product_price',
        ];

        if ($type == 'day') {
            $columns['id'] = 't.id';  // to speed-up insert on duplicate key update
        }

        $cols = array_keys($columns);
        $cols['total_qty'] = new \Zend_Db_Expr('SUM(t.' . $column . ')');
        $periodSubSelect->from(
            ['t' => $mainTable],
            $cols
        )->group(
            ['t.store_id', $periodCol, 't.product_id']
        )->order(
            ['t.store_id', $periodCol, 'total_qty DESC']
        );

        $cols = $columns;
        $cols[$column] = 't.total_qty';
        $cols['rating_pos'] = new \Zend_Db_Expr(
            "(@pos := IF(t.`store_id` <> @prevStoreId OR {$periodCol} <> @prevPeriod, 1, @pos+1))"
        );
        $cols['prevStoreId'] = new \Zend_Db_Expr('(@prevStoreId := t.`store_id`)');
        $cols['prevPeriod'] = new \Zend_Db_Expr("(@prevPeriod := {$periodCol})");
        $ratingSubSelect->from($periodSubSelect, $cols);

        $cols = $columns;
        $cols['period'] = $periodCol;
        $cols[$column] = 't.' . $column;
        $cols['rating_pos'] = 't.rating_pos';
        $ratingSelect->from($ratingSubSelect, $cols);


        $preQuery = "SET @pos = 0, @prevStoreId = -1, @prevPeriod = '0000-00-00'";



            //$connection->query();

        //$sql = $ratingSelect->insertFromSelect($aggregationTable, array_keys($cols));
        die($preQuery.";".$ratingSelect);

        //@todo use approach from \Magento\Reports\Model\ResourceModel\Helper::updateReportRatingPos
    }
}
