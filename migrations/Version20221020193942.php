<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221020193942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE recipe_component (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', ingredient_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', numericQuantity VARCHAR(255) DEFAULT NULL, formattedQuantity VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_5E3F8FB1933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE unit (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_component ADD CONSTRAINT FK_5E3F8FB1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
        $this->addSql('ALTER TABLE ingredient DROP unit');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_component DROP FOREIGN KEY FK_5E3F8FB1933FE08C');
        $this->addSql('DROP TABLE recipe_component');
        $this->addSql('DROP TABLE unit');
        $this->addSql('ALTER TABLE ingredient ADD unit VARCHAR(255) DEFAULT NULL');
    }
}
