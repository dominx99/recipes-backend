<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230429214210 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe ADD owner_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid_string)\', CHANGE externalIdentifier externalIdentifier VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP owner_id, CHANGE externalIdentifier externalIdentifier VARCHAR(255) NOT NULL');
    }
}
