<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226115331 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INT AUTO_INCREMENT NOT NULL, repertoire_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, email VARCHAR(180) DEFAULT NULL, role VARCHAR(100) DEFAULT NULL, commentaire VARCHAR(255) DEFAULT NULL, INDEX IDX_4C62E6381E61B789 (repertoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, montant NUMERIC(10, 2) NOT NULL, date_devis DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, commentaire LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_8B27C52B19EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_ligne (id INT AUTO_INCREMENT NOT NULL, devis_id INT DEFAULT NULL, designation VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_41D3C6A741DEFADA (devis_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE devis_version (id INT AUTO_INCREMENT NOT NULL, montant DOUBLE PRECISION NOT NULL, commentaire VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, status VARCHAR(255) NOT NULL, version VARCHAR(255) NOT NULL, date_modification DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE documents_utilisateur (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, dossier_id INT DEFAULT NULL, type_document_id INT DEFAULT NULL, date_document DATETIME NOT NULL, name VARCHAR(255) NOT NULL, expediteur VARCHAR(255) NOT NULL, destinataire VARCHAR(255) NOT NULL, file_path VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, details VARCHAR(255) NOT NULL, INDEX IDX_C7800232A76ED395 (user_id), INDEX IDX_C7800232611C0C56 (dossier_id), INDEX IDX_C78002328826AFA6 (type_document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dossier (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, services_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3D48E037A76ED395 (user_id), INDEX IDX_3D48E037AEF5A6C1 (services_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, services_id INT DEFAULT NULL, user_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, location VARCHAR(255) DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, google_calendar_event_id JSON DEFAULT NULL, INDEX IDX_5387574AAEF5A6C1 (services_id), INDEX IDX_5387574AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, montant NUMERIC(10, 2) NOT NULL, date_paiement DATETIME DEFAULT NULL, date_facture DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(255) NOT NULL, commentaire LONGTEXT DEFAULT NULL, is_active TINYINT(1) NOT NULL, INDEX IDX_FE86641019EB6921 (client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE facture_ligne (id INT AUTO_INCREMENT NOT NULL, facture_id INT NOT NULL, designation VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix_unitaire NUMERIC(10, 2) NOT NULL, INDEX IDX_C5C453347F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE paiement (id INT AUTO_INCREMENT NOT NULL, facture_id INT DEFAULT NULL, montant_paye DOUBLE PRECISION NOT NULL, date_paiement DATETIME NOT NULL, INDEX IDX_B1DC7A1E7F2DEE08 (facture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repertoire (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, dossier_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, mobile VARCHAR(20) DEFAULT NULL, email VARCHAR(180) NOT NULL, siret VARCHAR(20) NOT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, INDEX IDX_3C367876A76ED395 (user_id), INDEX IDX_3C367876611C0C56 (dossier_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_document (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_activity DATETIME DEFAULT NULL, nom VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, code_postal VARCHAR(10) NOT NULL, ville VARCHAR(100) NOT NULL, pays VARCHAR(100) NOT NULL, telephone VARCHAR(20) NOT NULL, mobile VARCHAR(20) DEFAULT NULL, siret VARCHAR(20) DEFAULT NULL, nom_entreprise VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_services (user_id INT NOT NULL, services_id INT NOT NULL, INDEX IDX_93BF0569A76ED395 (user_id), INDEX IDX_93BF0569AEF5A6C1 (services_id), PRIMARY KEY(user_id, services_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact ADD CONSTRAINT FK_4C62E6381E61B789 FOREIGN KEY (repertoire_id) REFERENCES repertoire (id)');
        $this->addSql('ALTER TABLE devis ADD CONSTRAINT FK_8B27C52B19EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE devis_ligne ADD CONSTRAINT FK_41D3C6A741DEFADA FOREIGN KEY (devis_id) REFERENCES devis (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C7800232A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C7800232611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE documents_utilisateur ADD CONSTRAINT FK_C78002328826AFA6 FOREIGN KEY (type_document_id) REFERENCES type_document (id)');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE dossier ADD CONSTRAINT FK_3D48E037AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AAEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE facture ADD CONSTRAINT FK_FE86641019EB6921 FOREIGN KEY (client_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE facture_ligne ADD CONSTRAINT FK_C5C453347F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE paiement ADD CONSTRAINT FK_B1DC7A1E7F2DEE08 FOREIGN KEY (facture_id) REFERENCES facture (id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C367876A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C367876611C0C56 FOREIGN KEY (dossier_id) REFERENCES dossier (id)');
        $this->addSql('ALTER TABLE user_services ADD CONSTRAINT FK_93BF0569A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_services ADD CONSTRAINT FK_93BF0569AEF5A6C1 FOREIGN KEY (services_id) REFERENCES services (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact DROP FOREIGN KEY FK_4C62E6381E61B789');
        $this->addSql('ALTER TABLE devis DROP FOREIGN KEY FK_8B27C52B19EB6921');
        $this->addSql('ALTER TABLE devis_ligne DROP FOREIGN KEY FK_41D3C6A741DEFADA');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C7800232A76ED395');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C7800232611C0C56');
        $this->addSql('ALTER TABLE documents_utilisateur DROP FOREIGN KEY FK_C78002328826AFA6');
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037A76ED395');
        $this->addSql('ALTER TABLE dossier DROP FOREIGN KEY FK_3D48E037AEF5A6C1');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AAEF5A6C1');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AA76ED395');
        $this->addSql('ALTER TABLE facture DROP FOREIGN KEY FK_FE86641019EB6921');
        $this->addSql('ALTER TABLE facture_ligne DROP FOREIGN KEY FK_C5C453347F2DEE08');
        $this->addSql('ALTER TABLE paiement DROP FOREIGN KEY FK_B1DC7A1E7F2DEE08');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C367876A76ED395');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C367876611C0C56');
        $this->addSql('ALTER TABLE user_services DROP FOREIGN KEY FK_93BF0569A76ED395');
        $this->addSql('ALTER TABLE user_services DROP FOREIGN KEY FK_93BF0569AEF5A6C1');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE devis');
        $this->addSql('DROP TABLE devis_ligne');
        $this->addSql('DROP TABLE devis_version');
        $this->addSql('DROP TABLE documents_utilisateur');
        $this->addSql('DROP TABLE dossier');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE facture');
        $this->addSql('DROP TABLE facture_ligne');
        $this->addSql('DROP TABLE paiement');
        $this->addSql('DROP TABLE repertoire');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE type_document');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE user_services');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
