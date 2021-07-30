<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210725100921 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobile ADD brand_id INT NOT NULL');
        $this->addSql('ALTER TABLE mobile ADD CONSTRAINT FK_3C7323E044F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('CREATE INDEX IDX_3C7323E044F5D008 ON mobile (brand_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobile DROP FOREIGN KEY FK_3C7323E044F5D008');
        $this->addSql('DROP INDEX IDX_3C7323E044F5D008 ON mobile');
        $this->addSql('ALTER TABLE mobile DROP brand_id');
    }
}
