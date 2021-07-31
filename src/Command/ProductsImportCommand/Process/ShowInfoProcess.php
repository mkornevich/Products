<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\OutputUtils;
use App\Command\ProductsImportCommand\ProductRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ShowInfoProcess extends Process
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
        $this->printProductRowsInfo($productRows, $output);
        $this->printTotalInfo($productRows, $output);
        return $productRows;
    }

    /**
     * @param ProductRow[] $productRows
     * @param OutputInterface $output
     */
    private function printProductRowsInfo(array $productRows, OutputInterface $output)
    {
        $output->writeln("Csv rows what not be imported:");
        foreach ($productRows as $productRow) {
            if ($productRow->hasErrors()) {
                $pos = $productRow->getPosition();
                $output->writeln("Errors in csv row at position {$pos}:");
                OutputUtils::printNumerateMessages($output, $productRow->getErrors());
                $output->writeln('');
            }
        }
    }

    /**
     * @param ProductRow[] $productRows
     * @param OutputInterface $output
     */
    private function printTotalInfo(array $productRows, OutputInterface $output)
    {
        $productRowCount = count($productRows);
        $validProductRowCount = $this->getValidProductRowCount($productRows);
        $invalidProductRowCount = $productRowCount - $validProductRowCount;

        $output->writeln("=== Total info ===");
        $output->writeln("Processed: " . $productRowCount);
        $output->writeln("Imported: " . $validProductRowCount);
        $output->writeln("Ignored: " . $invalidProductRowCount);
    }

    /**
     * @param ProductRow[] $productRows
     * @return int
     */
    private function getValidProductRowCount(array $productRows): int
    {
        $result = 0;
        foreach ($productRows as $productRow) {
            if (!$productRow->hasErrors()) {
                $result++;
            }
        }
        return $result;
    }

}