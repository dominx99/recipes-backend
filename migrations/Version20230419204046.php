<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230419204046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite_recipe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', recipe_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', UNIQUE INDEX user_id_recipe_id_uidx (user_id, recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ingredient (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', parent_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid_string)\', name VARCHAR(255) NOT NULL, INDEX IDX_D34A04AD727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_category_product (product_category_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', product_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', INDEX IDX_9A1E202FBE6903FD (product_category_id), INDEX IDX_9A1E202F4584665A (product_id), PRIMARY KEY(product_category_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', externalIdentifier VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', recipe_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', ingredient_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', unit VARCHAR(255) DEFAULT NULL, numeric_quantity VARCHAR(255) DEFAULT NULL, formatted_quantity VARCHAR(255) DEFAULT NULL, INDEX IDX_5E3F8FB159D8A214 (recipe_id), INDEX IDX_5E3F8FB1933FE08C (ingredient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE refresh_token (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_C74F2195C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX email_uidx (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD727ACA70 FOREIGN KEY (parent_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202FBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_category_product ADD CONSTRAINT FK_9A1E202F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_component ADD CONSTRAINT FK_5E3F8FB159D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id)');
        $this->addSql('ALTER TABLE recipe_component ADD CONSTRAINT FK_5E3F8FB1933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD727ACA70');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202FBE6903FD');
        $this->addSql('ALTER TABLE product_category_product DROP FOREIGN KEY FK_9A1E202F4584665A');
        $this->addSql('ALTER TABLE recipe_component DROP FOREIGN KEY FK_5E3F8FB159D8A214');
        $this->addSql('ALTER TABLE recipe_component DROP FOREIGN KEY FK_5E3F8FB1933FE08C');
        $this->addSql('DROP TABLE favorite_recipe');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_category_product');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_component');
        $this->addSql('DROP TABLE refresh_token');
        $this->addSql('DROP TABLE user');
    }
}
