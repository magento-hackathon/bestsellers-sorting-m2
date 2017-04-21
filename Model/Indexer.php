<?php

namespace MagentoHackathon\BestsellersSorting\Model;
class Indexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    /**
     * @var SimpleProductsAggregatedReportDataProcessor
     */
    private $processor;

    public function __construct(
        SimpleProductsAggregatedReportDataProcessor $processor
    )
    {

        $this->processor = $processor;
    }

    public function executeList(array $ids)
    {
        $this->executeFull();
    }  //logic

    public function executeFull()
    {
        $this->processor->calculate(1);
        //echo "Hello World";
    }

    public function executeRow($id)
    {
        $this->executeFull();
    }

    public function execute($ids)
    {
        $this->executeFull();
    }
}