<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241103141006 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient CHANGE quantite_defaut quantite_defaut DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE liste_courses ADD CONSTRAINT FK_189FC21D933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF67785E153 FOREIGN KEY (petit_dejeuner_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF673943D93 FOREIGN KEY (encas_matin_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF668482329 FOREIGN KEY (dejeuner_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF6F58165D0 FOREIGN KEY (encas_apres_midi_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE planning ADD CONSTRAINT FK_D499BFF614B79A3C FOREIGN KEY (diner_id) REFERENCES repas (id)');
        $this->addSql('ALTER TABLE repas_ingredient ADD CONSTRAINT FK_CC79FC391D236AAA FOREIGN KEY (repas_id) REFERENCES repas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE repas_ingredient ADD CONSTRAINT FK_CC79FC39933FE08C FOREIGN KEY (ingredient_id) REFERENCES ingredient (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ingredient CHANGE quantite_defaut quantite_defaut VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE liste_courses DROP FOREIGN KEY FK_189FC21D933FE08C');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF67785E153');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF673943D93');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF668482329');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF6F58165D0');
        $this->addSql('ALTER TABLE planning DROP FOREIGN KEY FK_D499BFF614B79A3C');
        $this->addSql('ALTER TABLE repas_ingredient DROP FOREIGN KEY FK_CC79FC391D236AAA');
        $this->addSql('ALTER TABLE repas_ingredient DROP FOREIGN KEY FK_CC79FC39933FE08C');
    }
}
