<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250521172127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        // $this->addSql('ALTER TABLE facture ADD facture_mere_id INT DEFAULT NULL');
        // $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410D486E642 FOREIGN KEY (prescripteur_id) REFERENCES `user` (id)');
        // $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641071396557 FOREIGN KEY (facture_mere_id) REFERENCES facture (id)');
        // $this->addSql('CREATE INDEX IDX_FE86641071396557 ON facture (facture_mere_id)');
        // $this->addSql('ALTER TABLE historique_paiement ADD heure_avance_at TIME NOT NULL');
        // $this->addSql('ALTER TABLE historique_paiement ADD CONSTRAINT FK_710402EC7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        // $this->addSql('ALTER TABLE historique_paiement ADD CONSTRAINT FK_710402EC59820928 FOREIGN KEY (recu_par_id) REFERENCES `user` (id)');
        // $this->addSql('ALTER TABLE hospitalisation_daily_report ADD CONSTRAINT FK_A2154CFCF531F4C5 FOREIGN KEY (hospitalisation_id) REFERENCES hospitalisation (id)');
        // $this->addSql('ALTER TABLE ligne_consultation ADD CONSTRAINT FK_2E98C56962FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id)');
        // $this->addSql('ALTER TABLE ligne_consultation ADD CONSTRAINT FK_2E98C5695C8659A FOREIGN KEY (examen_id) REFERENCES produit (id)');
        // $this->addSql('ALTER TABLE ligne_de_commande ADD CONSTRAINT FK_7982ACE682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        // $this->addSql('ALTER TABLE ligne_de_commande ADD CONSTRAINT FK_7982ACE6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        // $this->addSql('ALTER TABLE ligne_de_facture ADD CONSTRAINT FK_2132BC8E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        //$this->addSql('ALTER TABLE ligne_de_facture ADD CONSTRAINT FK_2132BC8EF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        //$this->addSql('ALTER TABLE ligne_de_kit ADD CONSTRAINT FK_6114D66AF347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        //$this->addSql('ALTER TABLE ligne_de_kit ADD CONSTRAINT FK_6114D66ACDDCABD5 FOREIGN KEY (produit_kit_id) REFERENCES produit (id)');
        //$this->addSql('ALTER TABLE liste_examens_afaire ADD CONSTRAINT FK_4EF719905C8659A FOREIGN KEY (examen_id) REFERENCES produit (id)');
        //$this->addSql('ALTER TABLE liste_examens_afaire ADD CONSTRAINT FK_4EF71990652E4D39 FOREIGN KEY (resultat_examen_id) REFERENCES resultat_examen (id)');
        //$this->addSql('ALTER TABLE lit ADD CONSTRAINT FK_5DDB8E9D9B177F54 FOREIGN KEY (chambre_id) REFERENCES chambre (id)');
        //$this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291BCB5FDB3E FOREIGN KEY (enregistre_par_id) REFERENCES `user` (id)');
        // $this->addSql('ALTER TABLE lot ADD CONSTRAINT FK_B81291B1237A8DE FOREIGN KEY (type_produit_id) REFERENCES type_produit (id)');
        // $this->addSql('ALTER TABLE modele ADD CONSTRAINT FK_100285584827B9B2 FOREIGN KEY (marque_id) REFERENCES marque (id)');
        // $this->addSql('ALTER TABLE parametres_vitaux ADD CONSTRAINT FK_C9222111C2BE0752 FOREIGN KEY (infirmier_id) REFERENCES `user` (id)');
        // $this->addSql('ALTER TABLE parametres_vitaux ADD CONSTRAINT FK_C9222111EBF00256 FOREIGN KEY (billet_de_session_id) REFERENCES billet_de_session (id)');
        // $this->addSql('ALTER TABLE patient ADD enregistre_par_id INT DEFAULT NULL, ADD date_enregistrement_at DATE NOT NULL, ADD heure_enregistrement_at TIME NOT NULL');
        // $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBA6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id)');
        // $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBB452768 FOREIGN KEY (groupe_sanguin_id) REFERENCES groupe_sanguin (id)');
        // $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EB4296D31F FOREIGN KEY (genre_id) REFERENCES genre (id)');
        // $this->addSql('ALTER TABLE patient ADD CONSTRAINT FK_1ADAD7EBCB5FDB3E FOREIGN KEY (enregistre_par_id) REFERENCES `user` (id)');
        // $this->addSql('CREATE INDEX IDX_1ADAD7EBCB5FDB3E ON patient (enregistre_par_id)');
        // $this->addSql('ALTER TABLE prime_speciale ADD CONSTRAINT FK_E655B2B61C109075 FOREIGN KEY (personnel_id) REFERENCES `user` (id)');
        // $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27A8CBA5F7 FOREIGN KEY (lot_id) REFERENCES lot (id)');
        // $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27CB5FDB3E FOREIGN KEY (enregistre_par_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC272195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id)');
        $this->addSql('ALTER TABLE rendez_vous ADD CONSTRAINT FK_65E8AA0A6B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
        $this->addSql('ALTER TABLE reponse_question ADD CONSTRAINT FK_E97BC6396BD4A821 FOREIGN KEY (question_secrete_id) REFERENCES question_secrete (id)');
        $this->addSql('ALTER TABLE reponse_question ADD CONSTRAINT FK_E97BC639A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B15F6BCF73 FOREIGN KEY (laborantin_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B1EBF00256 FOREIGN KEY (billet_de_session_id) REFERENCES billet_de_session (id)');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B16B899279 FOREIGN KEY (patient_id) REFERENCES patient (id)');
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
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410D486E642');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641071396557');
        $this->addSql('DROP INDEX IDX_FE86641071396557 ON facture');
        $this->addSql('ALTER TABLE facture DROP facture_mere_id');
        $this->addSql('ALTER TABLE historique_paiement DROP FOREIGN KEY FK_710402EC7F2DEE08');
        $this->addSql('ALTER TABLE historique_paiement DROP FOREIGN KEY FK_710402EC59820928');
        $this->addSql('ALTER TABLE historique_paiement DROP heure_avance_at');
        $this->addSql('ALTER TABLE hospitalisation_daily_report DROP FOREIGN KEY FK_A2154CFCF531F4C5');
        $this->addSql('ALTER TABLE ligne_consultation DROP FOREIGN KEY FK_2E98C56962FF6CDF');
        $this->addSql('ALTER TABLE ligne_consultation DROP FOREIGN KEY FK_2E98C5695C8659A');
        $this->addSql('ALTER TABLE ligne_de_commande DROP FOREIGN KEY FK_7982ACE682EA2E54');
        $this->addSql('ALTER TABLE ligne_de_commande DROP FOREIGN KEY FK_7982ACE6F347EFB');
        $this->addSql('ALTER TABLE ligne_de_facture DROP FOREIGN KEY FK_2132BC8E7F2DEE08');
        $this->addSql('ALTER TABLE ligne_de_facture DROP FOREIGN KEY FK_2132BC8EF347EFB');
        $this->addSql('ALTER TABLE ligne_de_kit DROP FOREIGN KEY FK_6114D66AF347EFB');
        $this->addSql('ALTER TABLE ligne_de_kit DROP FOREIGN KEY FK_6114D66ACDDCABD5');
        $this->addSql('ALTER TABLE liste_examens_afaire DROP FOREIGN KEY FK_4EF719905C8659A');
        $this->addSql('ALTER TABLE liste_examens_afaire DROP FOREIGN KEY FK_4EF71990652E4D39');
        $this->addSql('ALTER TABLE lit DROP FOREIGN KEY FK_5DDB8E9D9B177F54');
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291BCB5FDB3E');
        $this->addSql('ALTER TABLE lot DROP FOREIGN KEY FK_B81291B1237A8DE');
        $this->addSql('ALTER TABLE modele DROP FOREIGN KEY FK_100285584827B9B2');
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C9222111C2BE0752');
        $this->addSql('ALTER TABLE parametres_vitaux DROP FOREIGN KEY FK_C9222111EBF00256');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBA6E44244');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBB452768');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EB4296D31F');
        $this->addSql('ALTER TABLE patient DROP FOREIGN KEY FK_1ADAD7EBCB5FDB3E');
        $this->addSql('DROP INDEX IDX_1ADAD7EBCB5FDB3E ON patient');
        $this->addSql('ALTER TABLE patient DROP enregistre_par_id, DROP date_enregistrement_at, DROP heure_enregistrement_at');
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
        $this->addSql('ALTER TABLE sous_categorie DROP FOREIGN KEY FK_52743D7BBCF5E72D');
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC181726BE0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AD4BC8DB');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6494296D31F');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492EC15B36');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6492195E0F0');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649B363224A');
    }
}
