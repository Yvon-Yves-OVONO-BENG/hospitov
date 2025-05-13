<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250510155222 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE reponse_question ADD CONSTRAINT FK_E97BC6396BD4A821 FOREIGN KEY (question_secrete_id) REFERENCES question_secrete (id)');
        $this->addSql('ALTER TABLE reponse_question ADD CONSTRAINT FK_E97BC639A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('DROP INDEX IDX_39DDF3B186AC2AAD ON resultat_examen');
        $this->addSql('ALTER TABLE resultat_examen ADD patient_id INT DEFAULT NULL, ADD realise TINYINT(1) NOT NULL, ADD paye TINYINT(1) NOT NULL, ADD date_prescription_at DATE NOT NULL, ADD heure_resultat_at TIME DEFAULT NULL, CHANGE date_resultat_at date_resultat_at DATE DEFAULT NULL, CHANGE prescription_examen_id billet_de_session_id INT DEFAULT NULL, CHANGE fichier_resultat slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B15F6BCF73 FOREIGN KEY (laborantin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B1EBF00256 FOREIGN KEY (billet_de_session_id) REFERENCES billet_de_session (id)');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('CREATE INDEX IDX_39DDF3B1EBF00256 ON resultat_examen (billet_de_session_id)');
        $this->addSql('CREATE INDEX IDX_39DDF3B16B899279 ON resultat_examen (patient_id)');
        $this->addSql('ALTER TABLE sous_categorie ADD CONSTRAINT FK_52743D7BBCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE specialite ADD CONSTRAINT FK_E7D6FCC181726BE0 FOREIGN KEY (chef_de_departement_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649AD4BC8DB FOREIGN KEY (type_utilisateur_id) REFERENCES type_utilisateur (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6494296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492EC15B36 FOREIGN KEY (categorie_permis_deconduire_id) REFERENCES categorie_permis_de_conduire (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6492195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649B363224A FOREIGN KEY (statut_personnel_id) REFERENCES statut_personnel (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291B1237A8DE');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_100285584827B9B2');
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C9222111C2BE0752');
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C9222111EBF00256');
        $this->addSql('DROP INDEX IDX_C9222111EBF00256 ON parametres_vitaux');
        $this->addSql('ALTER TABLE parametres_vitaux DROP slug, DROP heure_prise_at, CHANGE date_prise_at date_prise_at DATETIME NOT NULL, CHANGE billet_de_session_id patient_id INT DEFAULT NULL');
        $this->addSql('CREATE INDEX IDX_C92221116B899279 ON parametres_vitaux (patient_id)');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA6E44244');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBB452768');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB4296D31F');
        $this->addSql('ALTER TABLE prime_speciale DROP FOREIGN KEY FK_E655B2B61C109075');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27A8CBA5F7');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27CB5FDB3E');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC272195E0F0');
        $this->addSql('ALTER TABLE rendez_vous DROP FOREIGN KEY FK_65E8AA0A6B899279');
        $this->addSql('ALTER TABLE reponse_question DROP FOREIGN KEY FK_E97BC6396BD4A821');
        $this->addSql('ALTER TABLE reponse_question DROP FOREIGN KEY FK_E97BC639A76ED395');
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B15F6BCF73');
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B1EBF00256');
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B16B899279');
        $this->addSql('DROP INDEX IDX_39DDF3B1EBF00256 ON resultat_examen');
        $this->addSql('DROP INDEX IDX_39DDF3B16B899279 ON resultat_examen');
        $this->addSql('ALTER TABLE resultat_examen ADD prescription_examen_id INT DEFAULT NULL, DROP billet_de_session_id, DROP patient_id, DROP realise, DROP paye, DROP date_prescription_at, DROP heure_resultat_at, CHANGE date_resultat_at date_resultat_at DATETIME NOT NULL, CHANGE slug fichier_resultat VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX IDX_39DDF3B186AC2AAD ON resultat_examen (prescription_examen_id)');
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC181726BE0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AD4BC8DB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6494296D31F');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492EC15B36');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B363224A');
    }
}
