<?php
namespace MagentoHackathon\BestsellersSorting\Model;
class Indexer implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    public function executeList(array $ids)
    {
        $this->executeFull();
    }  //logic

    public function executeFull()
    {
        echo "Hello World";
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