<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250430134707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_examen ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_39DDF3B16B899279 ON resultat_examen (patient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B16B899279');
        $this->addSql('DROP INDEX IDX_39DDF3B16B899279 ON resultat_examen');
        $this->addSql('ALTER TABLE resultat_examen DROP patient_id');
    }
}
