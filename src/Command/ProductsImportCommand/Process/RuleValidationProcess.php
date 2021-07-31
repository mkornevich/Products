<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RuleValidationProcess extends Process
{

    /**
     * @param ProductRow[] $productRows
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param EntityManagerInterface $entityManager
     * @return ProductRow[]
     */
    public function process(array $productRows, InputInterface $input, OutputInterface $output, EntityManagerInterface $entityManager): array
    {
        foreach ($productRows as $productRow) {
            if (!$productRow->hasErrors()) {
                $this->checkRulesForProductRow($productRow);
            }
        }
        return $productRows;
    }

    private function checkRulesForProductRow(ProductRow $productRow)
    {
        $cost = $productRow->csvRow[ProductRow::COST];
        $stock = (int)$productRow->csvRow[ProductRow::STOCK];

        if (bccomp($cost, 5, 2) === -1 && $stock < 10) {
            $productRow->addError('It will not be imported because cost < 5 and stock < 10.');
        }

        if (bccomp($cost, 1000, 2) === 1) {
            $productRow->addError('It will not be imported because cost > 1000.');
        }
    }
}