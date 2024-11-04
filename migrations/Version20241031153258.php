<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241031153258 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Étape 1 : Créer les tables principales sans contraintes
        $this->addSql('CREATE TABLE ingredient (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) DEFAULT NULL,
            quantite_defaut VARCHAR(255) DEFAULT NULL,
            unite VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE repas (
            id INT AUTO_INCREMENT NOT NULL,
            nom VARCHAR(255) NOT NULL,
            categorie VARCHAR(255) NOT NULL,
            description LONGTEXT DEFAULT NULL,
            recette LONGTEXT DEFAULT NULL,
            date DATETIME DEFAULT NULL,
            type_repas VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Étape 2 : Créer les tables de liaison avec leurs colonnes de clés étrangères
        $this->addSql('CREATE TABLE ingredient_quantite (
            id INT AUTO_INCREMENT NOT NULL,
            ingredient_id INT NOT NULL,
            repas_id INT NOT NULL,
            quantite DOUBLE PRECISION NOT NULL,
            INDEX IDX_F9F41AF7933FE08C (ingredient_id),
            INDEX IDX_F9F41AF71D236AAA (repas_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        $this->addSql('CREATE TABLE liste_courses (
            id INT AUTO_INCREMENT NOT NULL,
            ingredient_id INT DEFAULT NULL,
            date_creation DATE DEFAULT NULL,
            quantite VARCHAR(255) DEFAULT NULL,
            en_stock TINYINT(1) DEFAULT NULL,
            INDEX IDX_189FC21D933FE08C (ingredient_id),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

        // Étape 3 : Ajouter les contraintes de clés étrangères
        $this->addSql('ALTER TABLE ingredient_quantite 
            ADD CONSTRAINT FK_F9F41AF7933FE08C 
            FOREIGN KEY (ingredient_id) 
            REFERENCES ingredient (id)');

        $this->addSql('ALTER TABLE ingredient_quantite 
            ADD CONSTRAINT FK_F9F41AF71D236AAA 
            FOREIGN KEY (repas_id) 
            REFERENCES repas (id)');

        $this->addSql('ALTER TABLE liste_courses 
            ADD CONSTRAINT FK_189FC21D933FE08C 
            FOREIGN KEY (ingredient_id) 
            REFERENCES ingredient (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE ingredient_quantite DROP FOREIGN KEY FK_F9F41AF7933FE08C');
        $this->addSql('ALTER TABLE ingredient_quantite DROP FOREIGN KEY FK_F9F41AF71D236AAA');
        $this->addSql('ALTER TABLE liste_courses DROP FOREIGN KEY FK_189FC21D933FE08C');
        $this->addSql('DROP TABLE ingredient_quantite');
        $this->addSql('DROP TABLE liste_courses');
        $this->addSql('DROP TABLE ingredient');
        $this->addSql('DROP TABLE repas');
    }
} 