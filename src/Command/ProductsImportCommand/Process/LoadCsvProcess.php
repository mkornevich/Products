<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class LoadCsvProcess extends Process
{
    public function process(array $productRows, InputInterface $input, OutputInterface $output, EntityManagerInterface $entityManager): array
    {
        $filename = '/home/ITRANSITION.CORP/m.kornevich/Desktop/TestTask/stock.csv';

        if (!file_exists($filename)) {
            $this->addError("File not found.");
            return [];
        }

        $csvRows = $this->loadCsvRows($filename);

        if (isset($csvRows[0]) && count($csvRows[0]) != 6 ) {
            $this->addError("In csv file, column count need to be 6.");
            return [];
        }

        return $this->csvRowsToProductRows($csvRows);
    }

    /**
     * @return ProductRow[]
     */
    private function csvRowsToProductRows(array $csvRows): array
    {
        $productRows = [];
        foreach ($csvRows as $index => $csvRow) {
            $productRows[] = new ProductRow($csvRow, $index + 2);
        }
        return $productRows;
    }

    private function loadCsvRows($filename): array
    {
        $csvStr = file_get_contents($filename);
        $encoder = new CsvEncoder();
        $csvRows = $encoder->decode($csvStr, 'csv', ['no_headers' => true]);
        return array_slice($csvRows, 1);
    }
}