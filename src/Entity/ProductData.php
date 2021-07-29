<?php


namespace App\Entity;

use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tblProductData")
 */
class ProductData
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="intProductDataId", type="integer", options={"unsigned": true})
     */
    private int $productDataId;

    /**
     * @ORM\Column(name="strProductName", type="string", length=50)
     */
    private string $productName;

    /**
     * @ORM\Column(name="strProductDesc", type="string", length=255)
     */
    private string $productDesc;

    /**
     * @ORM\Column(name="strProductCode", type="string", length=10, unique=true)
     */
    private string $productCode;

    /**
     * @ORM\Column(name="dcmCost", type="decimal", precision=8, scale=2)
     */
    private string $cost;

    /**
     * @ORM\Column(name="intStock", type="integer")
     */
    private int $stock;

    /**
     * @ORM\Column(name="dtmAdded", type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $added;

    /**
     * @ORM\Column(name="dtmDiscontinued", type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $discontinued;

    /**
     * @ORM\Column(name="stmTimestamp", type="datetime_immutable", columnDefinition="TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP")
     */
    private DateTimeImmutable $timestamp;

    /**
     * @return int
     */
    public function getProductDataId(): int
    {
        return $this->productDataId;
    }

    /**
     * @param int $productDataId
     */
    public function setProductDataId(int $productDataId): void
    {
        $this->productDataId = $productDataId;
    }

    /**
     * @return string
     */
    public function getProductName(): string
    {
        return $this->productName;
    }

    /**
     * @param string $productName
     */
    public function setProductName(string $productName): void
    {
        $this->productName = $productName;
    }

    /**
     * @return string
     */
    public function getProductDesc(): string
    {
        return $this->productDesc;
    }

    /**
     * @param string $productDesc
     */
    public function setProductDesc(string $productDesc): void
    {
        $this->productDesc = $productDesc;
    }

    /**
     * @return string
     */
    public function getProductCode(): string
    {
        return $this->productCode;
    }

    /**
     * @param string $productCode
     */
    public function setProductCode(string $productCode): void
    {
        $this->productCode = $productCode;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getAdded(): DateTimeImmutable
    {
        return $this->added;
    }

    /**
     * @param DateTimeImmutable $added
     */
    public function setAdded(DateTimeImmutable $added): void
    {
        $this->added = $added;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getDiscontinued(): DateTimeImmutable
    {
        return $this->discontinued;
    }

    /**
     * @param DateTimeImmutable $discontinued
     */
    public function setDiscontinued(DateTimeImmutable $discontinued): void
    {
        $this->discontinued = $discontinued;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getTimestamp(): DateTimeImmutable
    {
        return $this->timestamp;
    }

    /**
     * @param DateTimeImmutable $timestamp
     */
    public function setTimestamp(DateTimeImmutable $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return string
     */
    public function getCost(): string
    {
        return $this->cost;
    }

    /**
     * @param string $cost
     */
    public function setCost(string $cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return int
     */
    public function getStock(): int
    {
        return $this->stock;
    }

    /**
     * @param int $stock
     */
    public function setStock(int $stock): void
    {
        $this->stock = $stock;
    }
}