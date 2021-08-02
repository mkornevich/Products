<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use App\Entity\Product;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDBProcess extends Process
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @param ProductRow[] $productRows
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return ProductRow[]
     */
    public function process(array $productRows, InputInterface $input, OutputInterface $output): array
    {
        if($input->getOption('test')) {
            return $productRows;
        }

        foreach ($productRows as $productRow) {
            if (!$productRow->hasErrors()) {
                $this->productRowToDatabase($productRow);
            }
        }

        $this->entityManager->flush();
        return $productRows;
    }

    private function productRowToDatabase(ProductRow $productRow)
    {
        $product = $this->entityManager
            ->getRepository(Product::class)
            ->findOneBy(['productCode' => $productRow->csvRow[ProductRow::CODE]]);

        $isNew = false;
        if ($product === null) {
            $product = new Product();
            $this->entityManager->persist($product);
            $isNew = true;
        }

        $this->productRowToProductEntity($productRow, $product, $isNew);
    }

    private function productRowToProductEntity(ProductRow $productRow, Product $product, bool $isNew = false)
    {
        $csvRow = $productRow->csvRow;

        $product->setProductCode($csvRow[ProductRow::CODE]);
        $product->setProductDesc($csvRow[ProductRow::DESCRIPTION]);
        $product->setProductName($csvRow[ProductRow::NAME]);
        $product->setStock($csvRow[ProductRow::STOCK]);
        $product->setCost($csvRow[ProductRow::COST]);
        $product->setTimestamp(new DateTimeImmutable());

        if ($isNew) {
            $product->setAdded(new DateTimeImmutable());
        }

        $discontinued = $csvRow[ProductRow::DISCONTINUED];
        if ($product->getDiscontinued() === null && $discontinued === 'yes') {
            $product->setDiscontinued(new DateTimeImmutable());
        } elseif ($product->getDiscontinued() !== null && $discontinued === '') {
            $product->setDiscontinued(null);
        }
    }
}