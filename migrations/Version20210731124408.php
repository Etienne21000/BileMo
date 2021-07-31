<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210731124408 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD token INT NOT NULL, DROP email, DROP password, DROP creation_date, DROP adress_liv, DROP fact_id');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD creation_date DATE NOT NULL, ADD fact_id INT NOT NULL, CHANGE token adress_liv INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, ADD creation_date DATE NOT NULL, ADD fact_id INT NOT NULL, CHANGE token adress_liv INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD token INT NOT NULL, DROP email, DROP password, DROP creation_date, DROP adress_liv, DROP fact_id');
    }
}
