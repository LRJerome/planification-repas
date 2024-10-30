<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241022055058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning ADD nombre_personnes_encas_matin INT DEFAULT NULL, ADD nombre_personnes_dejeuner INT DEFAULT NULL, ADD nombre_personnes_encas_apres_midi INT DEFAULT NULL, ADD nombre_personnes_diner INT DEFAULT NULL, CHANGE nombre_personnes nombre_personnes_petit_dejeuner INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE planning ADD nombre_personnes INT DEFAULT NULL, DROP nombre_personnes_petit_dejeuner, DROP nombre_personnes_encas_matin, DROP nombre_personnes_dejeuner, DROP nombre_personnes_encas_apres_midi, DROP nombre_personnes_diner');
    }
}
