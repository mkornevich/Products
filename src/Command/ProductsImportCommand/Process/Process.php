<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Process
{
    /**
     * @var string[]
     */
    private array $errors = [];

    /**
     * @param ProductRow[] $productRows
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param EntityManagerInterface $entityManager
     * @return ProductRow[]
     */
    abstract public function process(array $productRows, InputInterface $input,
                                     OutputInterface $output, EntityManagerInterface $entityManager): array;

    protected function addError(string $error)
    {
        $this->errors[] = $error;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function clearErrors()
    {
        $this->errors = [];
    }

    /**
     * @return string[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}