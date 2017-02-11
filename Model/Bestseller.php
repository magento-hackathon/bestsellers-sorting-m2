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

use MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface;

class Bestseller extends \Magento\Framework\Model\AbstractModel implements BestsellerInterface
{

    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init('MagentoHackathon\BestsellersSorting\Model\ResourceModel\Bestseller');
    }

    /**
     * Get bestseller_id
     * @return string
     */
    public function getBestsellerId()
    {
        return $this->getData(self::BESTSELLER_ID);
    }

    /**
     * Set bestseller_id
     * @param string $bestsellerId
     * @return MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface
     */
    public function setBestsellerId($bestsellerId)
    {
        return $this->setData(self::BESTSELLER_ID, $bestsellerId);
    }

    /**
     * Get sales
     * @return string
     */
    public function getSales()
    {
        return $this->getData(self::SALES);
    }

    /**
     * Set sales
     * @param string $sales
     * @return MagentoHackathon\BestsellersSorting\Api\Data\BestsellerInterface
     */
    public function setSales($sales)
    {
        return $this->setData(self::SALES, $sales);
    }
}
