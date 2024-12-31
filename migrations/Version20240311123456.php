<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240311123456 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Supprime la contrainte unique sur la date dans la table planning';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('
            SELECT IF(
                EXISTS(
                    SELECT * FROM information_schema.statistics 
                    WHERE table_schema = DATABASE()
                    AND table_name = "planning" 
                    AND index_name = "UNIQ_D499BFF6AA9E377A"
                ),
                "ALTER TABLE planning DROP INDEX UNIQ_D499BFF6AA9E377A",
                "SELECT 1"
            ) INTO @dropIndex1
        ');
        $this->addSql('PREPARE stmt1 FROM @dropIndex1');
        $this->addSql('EXECUTE stmt1');
        $this->addSql('DEALLOCATE PREPARE stmt1');

        $this->addSql('
            SELECT IF(
                EXISTS(
                    SELECT * FROM information_schema.statistics 
                    WHERE table_schema = DATABASE()
                    AND table_name = "planning" 
                    AND index_name = "unique_date"
                ),
                "ALTER TABLE planning DROP INDEX unique_date",
                "SELECT 1"
            ) INTO @dropIndex2
        ');
        $this->addSql('PREPARE stmt2 FROM @dropIndex2');
        $this->addSql('EXECUTE stmt2');
        $this->addSql('DEALLOCATE PREPARE stmt2');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE planning ADD UNIQUE INDEX unique_date (date)');
    }
} 