<?php


namespace MagentoHackathon\BestsellersSorting\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generatebestsellers extends Command
{


    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {

        $output->writeln("Hello World" );
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("magentohackathon_bestsellerssorting:generatebestsellers");
        $this->setDescription("Generate Bestsellers");

        parent::configure();
    }
}
