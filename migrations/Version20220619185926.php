<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220619185926 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE booking_room (booking_id INT NOT NULL, room_id INT NOT NULL, PRIMARY KEY(booking_id, room_id))');
        $this->addSql('CREATE INDEX IDX_6A0E73D53301C60 ON booking_room (booking_id)');
        $this->addSql('CREATE INDEX IDX_6A0E73D554177093 ON booking_room (room_id)');
        $this->addSql('ALTER TABLE booking_room ADD CONSTRAINT FK_6A0E73D53301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_room ADD CONSTRAINT FK_6A0E73D554177093 FOREIGN KEY (room_id) REFERENCES room (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE booking_room');
    }
}
