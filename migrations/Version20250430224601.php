<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430224601 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fichier_resultat_examen (id INT AUTO_INCREMENT NOT NULL, resultat_examen_id INT DEFAULT NULL, fichier_resultat VARCHAR(255) NOT NULL, INDEX IDX_70DCD3DD652E4D39 (resultat_examen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fichier_resultat_examen ADD CONSTRAINT FK_70DCD3DD652E4D39 FOREIGN KEY (resultat_examen_id) REFERENCES resultat_examen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fichier_resultat_examen DROP FOREIGN KEY FK_70DCD3DD652E4D39');
        $this->addSql('DROP TABLE fichier_resultat_examen');
    }
}
