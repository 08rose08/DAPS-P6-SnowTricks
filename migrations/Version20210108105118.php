<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108105118 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX name ON trick');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8F0A91E5E237E06 ON trick (name)');
        $this->addSql('ALTER TABLE user ADD token VARCHAR(255) NOT NULL, ADD is_valid TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_d8f0a91e5e237e06 ON trick');
        $this->addSql('CREATE UNIQUE INDEX name ON trick (name)');
        $this->addSql('ALTER TABLE user DROP token, DROP is_valid');
    }
}
