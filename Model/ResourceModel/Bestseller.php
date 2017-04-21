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

namespace MagentoHackathon\BestsellersSorting\Model\ResourceModel;

use Magento\Sales\Model\ResourceModel\Report\AbstractReport;

class Bestseller extends \Magento\Sales\Model\ResourceModel\Report\Bestsellers
{

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('magentohackathon_bestseller', 'bestseller_id');
    }

    protected function _updateRatingPos($aggregation)
    {
        return $this;
    }

}
