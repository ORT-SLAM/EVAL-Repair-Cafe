<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251107075107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reparateur_category (reparateur_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E026FE094E2493C5 (reparateur_id), INDEX IDX_E026FE0912469DE2 (category_id), PRIMARY KEY(reparateur_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reparateur_category ADD CONSTRAINT FK_E026FE094E2493C5 FOREIGN KEY (reparateur_id) REFERENCES reparateur (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reparateur_category ADD CONSTRAINT FK_E026FE0912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reparateur_category DROP FOREIGN KEY FK_E026FE094E2493C5');
        $this->addSql('ALTER TABLE reparateur_category DROP FOREIGN KEY FK_E026FE0912469DE2');
        $this->addSql('DROP TABLE reparateur_category');
    }
}
