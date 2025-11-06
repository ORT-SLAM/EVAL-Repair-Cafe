<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251106102057 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, icone VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE objet (id INT AUTO_INCREMENT NOT NULL, categorie_id INT NOT NULL, titre VARCHAR(200) NOT NULL, description_panne LONGTEXT NOT NULL, nom_proprietaire VARCHAR(255) NOT NULL, email_proprietaire VARCHAR(255) NOT NULL, date_depot VARCHAR(255) NOT NULL, estimation_cout_reparation NUMERIC(10, 2) DEFAULT NULL, est_fonctionnel TINYINT(1) NOT NULL, photo VARCHAR(255) DEFAULT NULL, INDEX IDX_46CD4C38BCF5E72D (categorie_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) DEFAULT NULL, date_inscription DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', niveau_experience VARCHAR(255) NOT NULL, presentation LONGTEXT DEFAULT NULL, est_actif TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_22E97E6DE7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reparation (id INT AUTO_INCREMENT NOT NULL, objet_id INT NOT NULL, reparateur_id INT NOT NULL, date_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', date_fin DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', statut VARCHAR(255) NOT NULL, commentaire LONGTEXT DEFAULT NULL, temps_passe_minutes INT DEFAULT NULL, pieces_utilisees LONGTEXT DEFAULT NULL, INDEX IDX_8FDF219DF520CF5A (objet_id), INDEX IDX_8FDF219D4E2493C5 (reparateur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE objet ADD CONSTRAINT FK_46CD4C38BCF5E72D FOREIGN KEY (categorie_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219DF520CF5A FOREIGN KEY (objet_id) REFERENCES objet (id)');
        $this->addSql('ALTER TABLE reparation ADD CONSTRAINT FK_8FDF219D4E2493C5 FOREIGN KEY (reparateur_id) REFERENCES reparateur (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE objet DROP FOREIGN KEY FK_46CD4C38BCF5E72D');
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219DF520CF5A');
        $this->addSql('ALTER TABLE reparation DROP FOREIGN KEY FK_8FDF219D4E2493C5');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE objet');
        $this->addSql('DROP TABLE reparateur');
        $this->addSql('DROP TABLE reparation');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
