<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240311123457 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Supprime la contrainte unique sur la date dans la table planning';
    }

    public function up(Schema $schema): void
    {
        // Supprime toutes les contraintes uniques possibles sur la colonne date
        $this->addSql('ALTER TABLE planning DROP INDEX IF EXISTS unique_date');
        $this->addSql('ALTER TABLE planning DROP INDEX IF EXISTS UNIQ_D499BFF6AA9E377A');
        $this->addSql('ALTER TABLE planning DROP INDEX IF EXISTS date');
        
        // Nettoie les doublons
        $this->addSql('
            CREATE TEMPORARY TABLE temp_planning AS
            SELECT MIN(id) as id
            FROM planning
            GROUP BY date;
        ');
        
        $this->addSql('
            DELETE FROM planning 
            WHERE id NOT IN (SELECT id FROM temp_planning);
        ');
        
        $this->addSql('DROP TEMPORARY TABLE IF EXISTS temp_planning;');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE planning ADD UNIQUE INDEX unique_date (date)');
    }
} 