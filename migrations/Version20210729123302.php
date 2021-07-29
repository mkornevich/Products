<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210729123302 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData ADD dcmCost NUMERIC(8, 2) NOT NULL, ADD intStock INT NOT NULL, CHANGE dtmAdded dtmAdded DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE dtmDiscontinued dtmDiscontinued DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE stmTimestamp stmTimestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP');
        $this->addSql('ALTER TABLE tblProductData RENAME INDEX strproductcode TO UNIQ_2C11248662F10A58');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE tblProductData DROP dcmCost, DROP intStock, CHANGE dtmAdded dtmAdded DATETIME DEFAULT NULL, CHANGE dtmDiscontinued dtmDiscontinued DATETIME DEFAULT NULL, CHANGE stmTimestamp stmTimestamp DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL');
        $this->addSql('ALTER TABLE tblProductData RENAME INDEX uniq_2c11248662f10a58 TO strProductCode');
    }
}
