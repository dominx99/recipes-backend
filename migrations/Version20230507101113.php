<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230507101113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add published column to recipe table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe ADD published TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE recipe DROP published');
    }
}
