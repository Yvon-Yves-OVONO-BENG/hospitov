<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250425053524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C92221116B899279');
        $this->addSql('DROP INDEX IDX_C92221116B899279 ON parametres_vitaux');
        $this->addSql('ALTER TABLE parametres_vitaux CHANGE patient_id billet_de_session_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE parametres_vitaux ADD CONSTRAINT FK_C9222111EBF00256 FOREIGN KEY (billet_de_session_id) REFERENCES billet_de_session (id)');
        $this->addSql('CREATE INDEX IDX_C9222111EBF00256 ON parametres_vitaux (billet_de_session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C9222111EBF00256');
        $this->addSql('DROP INDEX IDX_C9222111EBF00256 ON parametres_vitaux');
        $this->addSql('ALTER TABLE parametres_vitaux CHANGE billet_de_session_id patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE parametres_vitaux ADD CONSTRAINT FK_C92221116B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C92221116B899279 ON parametres_vitaux (patient_id)');
    }
}
