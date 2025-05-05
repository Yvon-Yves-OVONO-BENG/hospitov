<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250424204144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet_de_session ADD caissiere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE billet_de_session ADD CONSTRAINT FK_FBEF362B23BD48D5 FOREIGN KEY (caissiere_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FBEF362B23BD48D5 ON billet_de_session (caissiere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE billet_de_session DROP FOREIGN KEY FK_FBEF362B23BD48D5');
        $this->addSql('DROP INDEX IDX_FBEF362B23BD48D5 ON billet_de_session');
        $this->addSql('ALTER TABLE billet_de_session DROP caissiere_id');
    }
}
