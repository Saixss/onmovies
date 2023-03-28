<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230207135430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update `created_on` column in user table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY created_on DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE user MODIFY created_on DATETIME NULL');
    }
}
