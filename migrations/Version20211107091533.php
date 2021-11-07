<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211107091533 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', reference VARCHAR(200) DEFAULT NULL, INDEX IDX_FE866410979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_detail (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, tva_id INT DEFAULT NULL, libelle VARCHAR(255) DEFAULT NULL, pu DOUBLE PRECISION DEFAULT NULL, qtt DOUBLE PRECISION DEFAULT NULL, reference_det VARCHAR(255) DEFAULT NULL, montant_tva DOUBLE PRECISION DEFAULT NULL, INDEX IDX_7B916D347F2DEE08 (facture_id), INDEX IDX_7B916D344D79775F (tva_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tva (id INT AUTO_INCREMENT NOT NULL, taux DOUBLE PRECISION DEFAULT NULL, tva DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE facture_detail ADD CONSTRAINT FK_7B916D347F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE facture_detail ADD CONSTRAINT FK_7B916D344D79775F FOREIGN KEY (tva_id) REFERENCES tva (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture_detail DROP FOREIGN KEY FK_7B916D347F2DEE08');
        $this->addSql('ALTER TABLE facture_detail DROP FOREIGN KEY FK_7B916D344D79775F');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_detail');
        $this->addSql('DROP TABLE tva');
    }
}
