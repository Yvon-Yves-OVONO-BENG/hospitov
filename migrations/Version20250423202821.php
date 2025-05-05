<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250423202821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP genre_id');
        $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBB452768 FOREIGN KEY (groupe_sanguin_id) REFERENCES groupe_sanguin (id)');
        $this->addSql('CREATE INDEX IDX_1ADAD7EBB452768 ON patient (groupe_sanguin_id)');
        $this->addSql('ALTER TABLE produit ADD specialite_id INT DEFAULT NULL, ADD billet_de_session TINYINT(1) DEFAULT NULL, ADD echographie TINYINT(1) DEFAULT NULL, ADD radio TINYINT(1) DEFAULT NULL, ADD examen TINYINT(1) DEFAULT NULL, ADD carnet TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC272195E0F0 ON produit (specialite_id)');
        $this->addSql('ALTER TABLE profil ADD specialite_id INT DEFAULT NULL, ADD supprime TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE profil ADD CONSTRAINT FK_E6D6B2972195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('CREATE INDEX IDX_E6D6B2972195E0F0 ON profil (specialite_id)');
        $this->addSql('ALTER TABLE user ADD categorie_permis_deconduire_id INT DEFAULT NULL, ADD specialite_id INT DEFAULT NULL, ADD statut_personnel_id INT DEFAULT NULL, ADD numero_permis_de_conduire VARCHAR(255) DEFAULT NULL, ADD supprime TINYINT(1) NOT NULL, ADD salaire_brute DOUBLE PRECISION DEFAULT NULL, ADD statut TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492EC15B36 FOREIGN KEY (categorie_permis_deconduire_id) REFERENCES categorie_permis_de_conduire (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B363224A FOREIGN KEY (statut_personnel_id) REFERENCES statut_personnel (id)');
        $this->addSql('CREATE INDEX IDX_8D93D6492EC15B36 ON user (categorie_permis_deconduire_id)');
        $this->addSql('CREATE INDEX IDX_8D93D6492195E0F0 ON user (specialite_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649B363224A ON user (statut_personnel_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBB452768');
        $this->addSql('DROP INDEX IDX_1ADAD7EBB452768 ON patient');
        $this->addSql('ALTER TABLE patient ADD genre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272195E0F0');
        $this->addSql('DROP INDEX IDX_29A5EC272195E0F0 ON produit');
        $this->addSql('ALTER TABLE produit DROP specialite_id, DROP billet_de_session, DROP echographie, DROP radio, DROP examen, DROP carnet');
        $this->addSql('ALTER TABLE profil DROP FOREIGN KEY FK_E6D6B2972195E0F0');
        $this->addSql('DROP INDEX IDX_E6D6B2972195E0F0 ON profil');
        $this->addSql('ALTER TABLE profil DROP specialite_id, DROP supprime');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492EC15B36');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B363224A');
        $this->addSql('DROP INDEX IDX_8D93D6492EC15B36 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D6492195E0F0 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D649B363224A ON `user`');
        $this->addSql('ALTER TABLE `user` DROP categorie_permis_deconduire_id, DROP specialite_id, DROP statut_personnel_id, DROP numero_permis_de_conduire, DROP supprime, DROP salaire_brute, DROP statut');
    }
}
