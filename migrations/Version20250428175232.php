<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250428175232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE866410D486E642');
        $this->addSql('ALTER TABLE resultat_examen DROP FOREIGN KEY FK_39DDF3B186AC2AAD');
        $this->addSql('ALTER TABLE prescription_examen DROP FOREIGN KEY FK_FA5067ED4F31A84');
        $this->addSql('ALTER TABLE prescription_examen DROP FOREIGN KEY FK_FA5067ED5C8659A');
        $this->addSql('ALTER TABLE prescription_examen DROP FOREIGN KEY FK_FA5067ED62FF6CDF');
        $this->addSql('ALTER TABLE prescription_medicament DROP FOREIGN KEY FK_22A2B37A4F31A84');
        $this->addSql('ALTER TABLE prescription_medicament DROP FOREIGN KEY FK_22A2B37A62FF6CDF');
        $this->addSql('DROP TABLE cigarette_vin');
        $this->addSql('DROP TABLE prescripteur');
        $this->addSql('DROP TABLE prescription_examen');
        $this->addSql('DROP TABLE prescription_medicament');
        $this->addSql('ALTER TABLE consultation ADD medicaments LONGTEXT DEFAULT NULL, ADD examens LONGTEXT DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_FE866410D486E642 ON facture');
        $this->addSql('ALTER TABLE facture DROP prescripteur_id');
        $this->addSql('DROP INDEX IDX_39DDF3B186AC2AAD ON resultat_examen');
        $this->addSql('ALTER TABLE resultat_examen DROP prescription_examen_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cigarette_vin (id INT AUTO_INCREMENT NOT NULL, supprime TINYINT(1) NOT NULL, slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prescripteur (id INT AUTO_INCREMENT NOT NULL, prescripteur VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prescription_examen (id INT AUTO_INCREMENT NOT NULL, examen_id INT DEFAULT NULL, consultation_id INT DEFAULT NULL, medecin_id INT DEFAULT NULL, date_prescription_at DATETIME NOT NULL, autres VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_FA5067ED4F31A84 (medecin_id), INDEX IDX_FA5067ED5C8659A (examen_id), INDEX IDX_FA5067ED62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE prescription_medicament (id INT AUTO_INCREMENT NOT NULL, consultation_id INT DEFAULT NULL, medecin_id INT DEFAULT NULL, nom_medicament VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, posologie VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, duree_traitement VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_22A2B37A4F31A84 (medecin_id), INDEX IDX_22A2B37A62FF6CDF (consultation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE prescription_examen ADD CONSTRAINT FK_FA5067ED4F31A84 FOREIGN KEY (medecin_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prescription_examen ADD CONSTRAINT FK_FA5067ED5C8659A FOREIGN KEY (examen_id) REFERENCES produit (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prescription_examen ADD CONSTRAINT FK_FA5067ED62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prescription_medicament ADD CONSTRAINT FK_22A2B37A4F31A84 FOREIGN KEY (medecin_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE prescription_medicament ADD CONSTRAINT FK_22A2B37A62FF6CDF FOREIGN KEY (consultation_id) REFERENCES consultation (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE consultation DROP medicaments, DROP examens');
        $this->addSql('ALTER TABLE facture ADD prescripteur_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE866410D486E642 FOREIGN KEY (prescripteur_id) REFERENCES prescripteur (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_FE866410D486E642 ON facture (prescripteur_id)');
        $this->addSql('ALTER TABLE resultat_examen ADD prescription_examen_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE resultat_examen ADD CONSTRAINT FK_39DDF3B186AC2AAD FOREIGN KEY (prescription_examen_id) REFERENCES prescription_examen (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_39DDF3B186AC2AAD ON resultat_examen (prescription_examen_id)');
    }
}
