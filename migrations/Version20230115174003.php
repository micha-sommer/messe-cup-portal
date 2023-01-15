<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230115174003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ega_female_contestant (id INT AUTO_INCREMENT NOT NULL, registration_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, gender VARCHAR(255) NOT NULL, weight_category VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CBFD8094833D8F43 (registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ega_male_contestant (id INT AUTO_INCREMENT NOT NULL, registration_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, gender VARCHAR(255) NOT NULL, weight_category VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D85EF1B0833D8F43 (registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messe_female_contestant (id INT AUTO_INCREMENT NOT NULL, registration_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, gender VARCHAR(255) NOT NULL, weight_category VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_972260E2833D8F43 (registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messe_male_contestant (id INT AUTO_INCREMENT NOT NULL, registration_id INT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, year SMALLINT NOT NULL, gender VARCHAR(255) NOT NULL, weight_category VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_54B9A7E4833D8F43 (registration_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ega_female_contestant ADD CONSTRAINT FK_CBFD8094833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id)');
        $this->addSql('ALTER TABLE ega_male_contestant ADD CONSTRAINT FK_D85EF1B0833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id)');
        $this->addSql('ALTER TABLE messe_female_contestant ADD CONSTRAINT FK_972260E2833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id)');
        $this->addSql('ALTER TABLE messe_male_contestant ADD CONSTRAINT FK_54B9A7E4833D8F43 FOREIGN KEY (registration_id) REFERENCES registration (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ega_female_contestant DROP FOREIGN KEY FK_CBFD8094833D8F43');
        $this->addSql('ALTER TABLE ega_male_contestant DROP FOREIGN KEY FK_D85EF1B0833D8F43');
        $this->addSql('ALTER TABLE messe_female_contestant DROP FOREIGN KEY FK_972260E2833D8F43');
        $this->addSql('ALTER TABLE messe_male_contestant DROP FOREIGN KEY FK_54B9A7E4833D8F43');
        $this->addSql('DROP TABLE ega_female_contestant');
        $this->addSql('DROP TABLE ega_male_contestant');
        $this->addSql('DROP TABLE messe_female_contestant');
        $this->addSql('DROP TABLE messe_male_contestant');
    }
}
