<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423204852 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2972195E0F0');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2974296D31F');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B297A76ED395');
        $this->addSql('DROP TABLE profil');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE profil (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, genre_id INT DEFAULT NULL, specialite_id INT DEFAULT NULL, modifie_at DATETIME DEFAULT NULL, image_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, nom VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, contact VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, adresse VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, username VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, supprime TINYINT(1) NOT NULL, INDEX IDX_E6D6B2972195E0F0 (specialite_id), INDEX IDX_E6D6B2974296D31F (genre_id), UNIQUE INDEX UNIQ_E6D6B297A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2972195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2974296D31F FOREIGN KEY (genre_id) REFERENCES genre (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B297A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
