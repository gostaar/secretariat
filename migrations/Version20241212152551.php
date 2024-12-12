<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241212152551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, repertoire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_4C62E6381E61B789 (repertoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, montant DOUBLE PRECISION NOT NULL, date_devis DATETIME NOT NULL, commentaire LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_version (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, date_devis DATETIME NOT NULL, commentaire VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_utilisateur (id INT AUTO_INCREMENT NOT NULL, type_document_id INT DEFAULT NULL, dossier_id INT DEFAULT NULL, service_id INT DEFAULT NULL, client_id INT DEFAULT NULL, date DATETIME DEFAULT NULL, details VARCHAR(255) DEFAULT NULL, expediteur VARCHAR(255) DEFAULT NULL, destinataire VARCHAR(255) DEFAULT NULL, file_path VARCHAR(255) DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_C78002328826AFA6 (type_document_id), INDEX IDX_C7800232611C0C56 (dossier_id), INDEX IDX_C7800232ED5CA9E6 (service_id), INDEX IDX_C780023219EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, montant_paye DOUBLE PRECISION NOT NULL, date_paiement DATETIME NOT NULL, INDEX IDX_B1DC7A1E7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repertoire (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, dossier_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, mobile VARCHAR(20) DEFAULT NULL, email VARCHAR(180) NOT NULL, siret VARCHAR(20) NOT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, INDEX IDX_3C36787619EB6921 (client_id), INDEX IDX_3C367876611C0C56 (dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_document (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_services (user_id INT NOT NULL, services_id INT NOT NULL, INDEX IDX_93BF0569A76ED395 (user_id), INDEX IDX_93BF0569AEF5A6C1 (services_id), PRIMARY KEY(user_id, services_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6381E61B789 FOREIGN KEY (repertoire_id) REFERENCES repertoire (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C78002328826AFA6 FOREIGN KEY (type_document_id) REFERENCES type_document (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C7800232611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C7800232ED5CA9E6 FOREIGN KEY (service_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C780023219EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C36787619EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C367876611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE user_services ADD CONSTRAINT FK_93BF0569A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_services ADD CONSTRAINT FK_93BF0569AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_5387574A19EB6921 ON events (client_id)');
        $this->addSql('ALTER TABLE facture ADD commentaire LONGTEXT DEFAULT NULL, ADD is_active TINYINT(1) NOT NULL, DROP name, DROP info, DROP pdf_file, CHANGE date date_paiement DATETIME NOT NULL');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD code_postal VARCHAR(10) NOT NULL, ADD ville VARCHAR(100) NOT NULL, ADD pays VARCHAR(100) NOT NULL, ADD telephone VARCHAR(20) NOT NULL, ADD mobile VARCHAR(20) DEFAULT NULL, ADD siret VARCHAR(20) DEFAULT NULL, ADD nom_entreprise VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6381E61B789');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C78002328826AFA6');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C7800232611C0C56');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C7800232ED5CA9E6');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C780023219EB6921');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E7F2DEE08');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C36787619EB6921');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C367876611C0C56');
        $this->addSql('ALTER TABLE user_services DROP FOREIGN KEY FK_93BF0569A76ED395');
        $this->addSql('ALTER TABLE user_services DROP FOREIGN KEY FK_93BF0569AEF5A6C1');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_version');
        $this->addSql('DROP TABLE documents_utilisateur');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE repertoire');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE type_document');
        $this->addSql('DROP TABLE user_services');
        $this->addSql('ALTER TABLE `user` DROP nom, DROP adresse, DROP code_postal, DROP ville, DROP pays, DROP telephone, DROP mobile, DROP siret, DROP nom_entreprise');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A19EB6921');
        $this->addSql('DROP INDEX IDX_5387574A19EB6921 ON events');
        $this->addSql('ALTER TABLE events DROP client_id');
        $this->addSql('ALTER TABLE facture ADD name VARCHAR(255) NOT NULL, ADD info LONGTEXT NOT NULL, ADD pdf_file VARCHAR(255) NOT NULL, DROP commentaire, DROP is_active, CHANGE date_paiement date DATETIME NOT NULL');
    }
}
