<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221222185623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE condo (id CHAR(36) NOT NULL, taxpayer CHAR(14) NOT NULL, fantasy_name VARCHAR(100) DEFAULT NULL, created_on DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_on DATETIME NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD condo_id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E2B100ED FOREIGN KEY (condo_id) REFERENCES condo (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649E2B100ED ON user (condo_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E2B100ED');
        $this->addSql('DROP TABLE condo');
        $this->addSql('DROP INDEX IDX_8D93D649E2B100ED ON user');
        $this->addSql('ALTER TABLE user DROP condo_id');
    }
}
