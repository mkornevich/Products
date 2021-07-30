<?php


namespace App\Command\ProductsImportCommand;


class ProductRow
{
    const CODE = 0;
    const NAME = 1;
    const DESCRIPTION = 2;
    const STOCK = 3;
    const COST = 4;
    const DISCONTINUED = 5;

    const COLUMN_COUNT = 6;

    private array $errors = [];

    private array $warnings = [];

    private int $position;

    public array $csvRow = [];

    public function __construct(array $csvRow, int $position)
    {
        $this->csvRow = $csvRow;
        $this->position = $position;
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

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}