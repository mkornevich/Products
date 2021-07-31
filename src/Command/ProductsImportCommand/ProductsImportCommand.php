<?php


namespace App\Command\ProductsImportCommand;


use App\Command\ProductsImportCommand\Process\LoadCsvProcess;
use App\Command\ProductsImportCommand\Process\NormalizeProcess;
use App\Command\ProductsImportCommand\Process\RuleValidationProcess;
use App\Command\ProductsImportCommand\Process\UpdateDBProcess;
use App\Command\ProductsImportCommand\Process\ValidationProcess;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ProductsImportCommand extends Command
{
    protected static $defaultName = 'app:products-import';

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription("Import products from csv file.")
            ->setHelp("This command allows you to import products into DB from csv file, also show import information.")
            ->addArgument('filename', InputArgument::REQUIRED, "This argument represent csv file for import.");
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $processor = new ProductRowsProcessor();

        $processor->addProcess(new LoadCsvProcess());
        $processor->addProcess(new NormalizeProcess());
        $processor->addProcess(new ValidationProcess());
        $processor->addProcess(new RuleValidationProcess());
        $processor->addProcess(new UpdateDBProcess());

        $processor->process($input, $output, $this->entityManager);

        return Command::SUCCESS;
    }
}