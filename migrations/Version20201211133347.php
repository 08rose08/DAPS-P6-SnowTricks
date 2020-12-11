<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211133347 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE video_trick (id INT AUTO_INCREMENT NOT NULL, trick_id INT NOT NULL, src VARCHAR(255) NOT NULL, INDEX IDX_5792A0BCB281BE2E (trick_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video_trick ADD CONSTRAINT FK_5792A0BCB281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
        $this->addSql('ALTER TABLE image_trick DROP FOREIGN KEY FK_CFB52DB7B281BE2E');
        $this->addSql('ALTER TABLE image_trick ADD CONSTRAINT FK_CFB52DB7B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE video_trick');
        $this->addSql('ALTER TABLE image_trick DROP FOREIGN KEY FK_CFB52DB7B281BE2E');
        $this->addSql('ALTER TABLE image_trick ADD CONSTRAINT FK_CFB52DB7B281BE2E FOREIGN KEY (trick_id) REFERENCES trick (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
