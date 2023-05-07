<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230507150252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add publish_recipe_request table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE publish_recipe_request (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid_string)\', owner_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid_string)\', recipe_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid_string)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE publish_recipe_request');
    }
}
