<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250208230434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE email_sender (id INT AUTO_INCREMENT NOT NULL, user_token_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, mail_protocol VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, mail_server VARCHAR(255) NOT NULL, mail_server_port VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_229A993FA15303B9 (user_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_sender ADD CONSTRAINT FK_229A993FA15303B9 FOREIGN KEY (user_token_id) REFERENCES user_token (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE email_sender DROP FOREIGN KEY FK_229A993FA15303B9');
        $this->addSql('DROP TABLE email_sender');
    }
}
