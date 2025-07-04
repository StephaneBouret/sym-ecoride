<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250704154643 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_2D5B02345E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trip ADD departure_city_id INT NOT NULL, ADD arrival_city_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trip ADD CONSTRAINT FK_7656F53B918B251E FOREIGN KEY (departure_city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trip ADD CONSTRAINT FK_7656F53B4067ACA7 FOREIGN KEY (arrival_city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7656F53B918B251E ON trip (departure_city_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_7656F53B4067ACA7 ON trip (arrival_city_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B918B251E
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trip DROP FOREIGN KEY FK_7656F53B4067ACA7
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7656F53B918B251E ON trip
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_7656F53B4067ACA7 ON trip
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE trip DROP departure_city_id, DROP arrival_city_id
        SQL);
    }
}
