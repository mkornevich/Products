<?php


namespace App\Command\ProductsImportCommand;


use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Serializer;

class ProductRowsFromCsvLoader
{
    /**
     * @var string[]
     */
    private array $errors = [];

    /**
     * @var ProductRow[]
     */
    private array $productRows = [];

    public function load(string $filename)
    {
        $this->errors = [];
        if (!file_exists($filename)) {
            $this->errors[] = "File not found.";
            return;
        }

        $csvRows = $this->loadCsvRows($filename);
        $this->productRows = $this->csvRowsToProductRows($csvRows);
    }

    /**
     * @return ProductRow[]
     */
    private function csvRowsToProductRows(array $csvRows): array
    {
        $productRows = [];
        foreach ($csvRows as $csvRow) {
            $productRows[] = new ProductRow($csvRow);
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

    /**
     * @return ProductRow[]
     */
    public function getProductRows(): array
    {
        return $this->productRows;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}