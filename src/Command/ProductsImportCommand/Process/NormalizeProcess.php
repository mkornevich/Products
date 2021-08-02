<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class NormalizeProcess extends Process
{
    public function process(array $productRows, InputInterface $input, OutputInterface $output): array
    {
        foreach ($productRows as $productRow) {
            $this->normalizeCost($productRow);
        }
        return $productRows;
    }

    private function normalizeCost(ProductRow $productRow)
    {
        $cost = $productRow->csvRow[ProductRow::COST];
        $cost = str_replace([',', ' ', '$'], ['.', '', ''], $cost);
        $productRow->csvRow[ProductRow::COST] = $cost;
    }
}