<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428073824 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation DROP FOREIGN KEY FK_964685A66B899279');
        $this->addSql('DROP INDEX IDX_964685A66B899279 ON consultation');
        $this->addSql('ALTER TABLE consultation DROP patient_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE consultation ADD patient_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE consultation ADD CONSTRAINT FK_964685A66B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_964685A66B899279 ON consultation (patient_id)');
    }
}
