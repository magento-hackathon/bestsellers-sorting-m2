<?php
namespace MagentoHackathon\BestsellersSorting\Model;
class Custom implements \Magento\Framework\Indexer\ActionInterface, \Magento\Framework\Mview\ActionInterface
{
    public function executeList($ids)
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