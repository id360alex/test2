<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250414145927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE reviews (id SERIAL NOT NULL, episode_name VARCHAR(255) NOT NULL, episode_id INT NOT NULL, release_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, create_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, score DOUBLE PRECISION DEFAULT NULL, episode_comment VARCHAR(500) NOT NULL, PRIMARY KEY(id))
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            DROP TABLE reviews
        SQL);
    }
}
