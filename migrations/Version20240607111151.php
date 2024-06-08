<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240607111151 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE notification_log_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('CREATE TABLE notification_log (
                            id INT NOT NULL,
                            user_id VARCHAR(255) NOT NULL,
                            created_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                            updated_on TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
                            status VARCHAR(50) NOT NULL,
                            recipient VARCHAR(255) NOT NULL,
                            channel VARCHAR(255) DEFAULT NULL,
                            provider VARCHAR(255) DEFAULT NULL,
                            subject VARCHAR(255) DEFAULT NULL,
                            body TEXT DEFAULT NULL, PRIMARY KEY(id))'
        );

        $this->addSql('CREATE INDEX IDX_USER_ID ON notification_log (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE notification_log_id_seq CASCADE');
        $this->addSql('DROP INDEX IDX_USER_ID');
        $this->addSql('DROP TABLE notification_log');
    }
}
