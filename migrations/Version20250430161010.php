<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430161010 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE liste_examens_afaire (id INT AUTO_INCREMENT NOT NULL, examen_id INT DEFAULT NULL, resultat_examen_id INT DEFAULT NULL, INDEX IDX_4EF719905C8659A (examen_id), INDEX IDX_4EF71990652E4D39 (resultat_examen_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE liste_examens_afaire ADD CONSTRAINT FK_4EF719905C8659A FOREIGN KEY (examen_id) REFERENCES produit (id)');
        $this->addSql('ALTER TABLE liste_examens_afaire ADD CONSTRAINT FK_4EF71990652E4D39 FOREIGN KEY (resultat_examen_id) REFERENCES resultat_examen (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE liste_examens_afaire DROP FOREIGN KEY FK_4EF719905C8659A');
        $this->addSql('ALTER TABLE liste_examens_afaire DROP FOREIGN KEY FK_4EF71990652E4D39');
        $this->addSql('DROP TABLE liste_examens_afaire');
    }
}
