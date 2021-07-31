<?php


namespace App\Command\ProductsImportCommand\Process;


use App\Command\ProductsImportCommand\ProductRow;
use App\Entity\Product;
use App\Repository\ProductRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateDBProcess extends Process
{

    /**
     * @param ProductRow[] $productRows
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param EntityManagerInterface $entityManager
     * @return ProductRow[]
     */
    public function process(array $productRows, InputInterface $input, OutputInterface $output, EntityManagerInterface $entityManager): array
    {
        foreach ($productRows as $productRow) {
            if (!$productRow->hasErrors()) {
                $this->productRowToDatabase($productRow, $entityManager);
            }
        }
        $entityManager->flush();
        return $productRows;
    }

    private function productRowToDatabase(ProductRow $productRow, EntityManagerInterface $entityManager)
    {
        $product = $entityManager
            ->getRepository(Product::class)
            ->findOneBy(['productCode' => $productRow->csvRow[ProductRow::CODE]]);

        $isNew = false;
        if ($product === null) {
            $product = new Product();
            $isNew = true;
        }

        $this->productRowToProductEntity($productRow, $product, $isNew);
        $entityManager->persist($product);
    }

    private function productRowToProductEntity(ProductRow $productRow, Product $product, bool $isNew = false)
    {
        $csvRow = $productRow->csvRow;

        $product->setProductCode($csvRow[ProductRow::CODE]);
        $product->setProductDesc($csvRow[ProductRow::DESCRIPTION]);
        $product->setProductName($csvRow[ProductRow::NAME]);
        $product->setStock($csvRow[ProductRow::STOCK]);
        $product->setCost($csvRow[ProductRow::COST]);

        $discontinued = $csvRow[ProductRow::DISCONTINUED];
        if ($product->getDiscontinued() === null && $discontinued === 'yes') {
            $product->setDiscontinued(new DateTimeImmutable());
        } elseif ($product->getDiscontinued() !== null && $discontinued === '') {
            $product->setDiscontinued(null);
        }

        if ($isNew) {
            $product->setAdded(new DateTimeImmutable());
        }

        $product->setTimestamp(new DateTimeImmutable());
    }
}