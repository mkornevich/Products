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
        $rows = $this->productRows;
        $ignorePositions = [];

        for ($upI = 0; $upI < count($rows); $upI++) {
            $upRow = $rows[$upI];
            if (in_array($upRow->getPosition(), $ignorePositions)) continue;

            $repeatRows = [$upRow];
            $repeatPositions = [$upRow->getPosition()];

            for ($downI = $upI + 1; $downI < count($rows); $downI++) {
                $downRow = $rows[$downI];
                if (in_array($downRow->getPosition(), $ignorePositions)) continue;

                if ($upRow->csvRow[ProductRow::CODE] === $downRow->csvRow[ProductRow::CODE]) {
                    $repeatRows[] = $downRow;
                    $repeatPositions[] = $downRow->getPosition();
                    $ignorePositions[] = $downRow->getPosition();
                }
            }

            if (count($repeatRows) > 1) {
                $repeatPositionsStr = join(", ", $repeatPositions);
                foreach ($repeatRows as $repeatRow) {
                    $ignorePositions[] = $repeatRow->getPosition();
                    $repeatRow->addError(
                        "In this position, product code repeat with another positions: " .
                        $repeatPositionsStr);
                }
            }
        }
    }
}