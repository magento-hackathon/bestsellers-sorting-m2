<?php


namespace MagentoHackathon\BestsellersSorting\Console\Command;

use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Framework\App\ObjectManager\ConfigLoader;
use Magento\Framework\App\ObjectManagerFactory;
use Magento\Framework\App\State;
use Magento\ImportExport\Model\Import;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Generatebestsellers extends Command
{

    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $objectManager;
    /**
     * Object manager factory
     *
     * @var ObjectManagerFactory
     */
    private $objectManagerFactory;
    /**
     * Constructor
     *
     * @param ObjectManagerFactory $objectManagerFactory
     */
    public function __construct(ObjectManagerFactory $objectManagerFactory)
    {
        $this->objectManagerFactory = $objectManagerFactory;
        parent::__construct();
    }



    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {


        $omParams = $_SERVER;
        $omParams[StoreManager::PARAM_RUN_CODE] = 'admin';
        $omParams[Store::CUSTOM_ENTRY_POINT_PARAM] = true;
        $this->objectManager = $this->objectManagerFactory->create($omParams);
        $area = FrontNameResolver::AREA_CODE;
        /** @var \Magento\Framework\App\State $appState */
        $appState = $this->objectManager->get('Magento\Framework\App\State');
        $appState->setAreaCode($area);
        $configLoader = $this->objectManager->get('Magento\Framework\ObjectManager\ConfigLoaderInterface');
        $this->objectManager->configure($configLoader->load($area));

        $output->writeln("Calculating..." );
        /** @var \MagentoHackathon\BestsellersSorting\Model\SimpleProductsAggregatedReportDataProcessor $importerModel */
        $importerModel = $this->objectManager->create('\MagentoHackathon\BestsellersSorting\Model\SimpleProductsAggregatedReportDataProcessor');
        $importerModel->calculate();

        $output->writeln("Calculation done!" );
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
