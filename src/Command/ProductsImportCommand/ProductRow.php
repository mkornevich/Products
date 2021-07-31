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

    private array $errors = [];

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

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }
}