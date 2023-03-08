<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230307204044 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add favorite recipes table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE favorite_recipe (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', recipe_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE UNIQUE INDEX user_id_recipe_id_uidx ON favorite_recipe (user_id, recipe_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX user_id_recipe_id_uidx ON favorite_recipe');
        $this->addSql('DROP TABLE favorite_recipe');
    }
}
