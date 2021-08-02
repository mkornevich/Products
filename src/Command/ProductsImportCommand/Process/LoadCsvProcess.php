<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\FilesystemInterface;
use App\Command\ProductsImportCommand\ProductRow;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\Encoder\CsvEncoder;

class LoadCsvProcess extends Process
{
    private FilesystemInterface $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function process(array $productRows, InputInterface $input, OutputInterface $output): array
    {
        $filename = $input->getArgument('filename');

        if (!$this->filesystem->fileExists($filename)) {
            $this->addError("File not found.");
            return [];
        }

        $csvRows = $this->loadCsvRows($filename);

        if (isset($csvRows[0]) && count($csvRows[0]) != ProductRow::COLUMNS_COUNT) {
            $this->addError("In csv file, column count need to be " . ProductRow::COLUMNS_COUNT . ".");
            return [];
        }

        return $this->convertCsvRowsToProductRows($csvRows);
    }

    /**
     * @return ProductRow[]
     */
    #[Pure]
    private function convertCsvRowsToProductRows(array $csvRows): array
    {
        $productRows = [];
        foreach ($csvRows as $index => $csvRow) {
            $productRows[] = new ProductRow($csvRow, $index + 2);
        }
        return $productRows;
    }

    private function loadCsvRows($filename): array
    {
        $csvStr = $this->filesystem->fileGetContents($filename);
        $encoder = new CsvEncoder();
        $csvRows = $encoder->decode($csvStr, 'csv', ['no_headers' => true]);
        return array_slice($csvRows, 1);
    }
}