<?php


namespace App\Command\ProductsImportCommand;


class ProductRow
{
    private array $errors = [];

    private array $warnings = [];

    public array $csvRow = [];

    public array $normalizedCsvRow = [];

    public function __construct(array $csvRow)
    {
        $this->csvRow = $csvRow;
    }

    public function addError(string $error)
    {
        $this->errors[] = $error;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function addWarning(string $warning)
    {
        $this->warnings[] = $warning;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function hasWarnings(): bool
    {
        return !empty($this->warnings);
    }
}