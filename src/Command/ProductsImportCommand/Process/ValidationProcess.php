<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidationProcess extends Process
{
    /**
     * @var ProductRow[]
     */
    private array $productRows;

    public function process(array $productRows, InputInterface $input, OutputInterface $output, EntityManagerInterface $entityManager): array
    {
        $this->productRows = $productRows;
        $this->RegexValidate();
        $this->productCodeRepeatValidate();
        return $productRows;
    }

    private function RegexValidate()
    {
        $this->AddErrorIfNotMatch(ProductRow::CODE, '/^P\d{4}$/', "Incorrect product code.");
        $this->AddErrorIfNotMatch(ProductRow::STOCK, '/^\d+$/', "Incorrect stock. Must be number without point.");
        $this->AddErrorIfNotMatch(ProductRow::COST, '/^\d+(.\d+)?$/', "Incorrect cost. Must be number.");
        $this->AddErrorIfNotMatch(ProductRow::DISCONTINUED, '/^yes$|^$/', "Incorrect discontinued. Must be yes or empty.");
    }

    private function AddErrorIfNotMatch(int $column, string $pattern, string $errorMessage)
    {
        foreach ($this->productRows as $productRow) {
            $cell = $productRow->csvRow[$column];
            if (preg_match($pattern, $cell) === 0) {
                $productRow->addError($errorMessage);
            }
        }
    }

    private function productCodeRepeatValidate()
    {
        foreach ($this->productRows as $key => $productRow) {
            //TODO
        }
    }
}