<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430110401 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_examen ADD billet_de_session_id INT DEFAULT NULL, ADD etat TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B1EBF00256 FOREIGN KEY (billet_de_session_id) REFERENCES billet_de_session (id)');
        $this->addSql('CREATE INDEX IDX_39DDF3B1EBF00256 ON resultat_examen (billet_de_session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B1EBF00256');
        $this->addSql('DROP INDEX IDX_39DDF3B1EBF00256 ON resultat_examen');
        $this->addSql('ALTER TABLE resultat_examen DROP billet_de_session_id, DROP etat');
    }
}
