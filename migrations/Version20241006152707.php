<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20241006152707 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initialize DB structure';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE email_process_log (id INT AUTO_INCREMENT NOT NULL, email_receiver_id INT DEFAULT NULL, sender_email VARCHAR(255) NOT NULL, subject LONGTEXT NOT NULL, body LONGTEXT NOT NULL, response_status VARCHAR(255) NOT NULL, error_message LONGTEXT DEFAULT NULL, INDEX IDX_A53D0B31DB939101 (email_receiver_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_receivers (id INT AUTO_INCREMENT NOT NULL, user_token_id INT NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_D54DEDDA15303B9 (user_token_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_token (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, related_url VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, last_usage DATE DEFAULT NULL, INDEX IDX_BDF55A63A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE email_process_log ADD CONSTRAINT FK_A53D0B31DB939101 FOREIGN KEY (email_receiver_id) REFERENCES email_receivers (id)');
        $this->addSql('ALTER TABLE email_receivers ADD CONSTRAINT FK_D54DEDDA15303B9 FOREIGN KEY (user_token_id) REFERENCES user_token (id)');
        $this->addSql('ALTER TABLE user_token ADD CONSTRAINT FK_BDF55A63A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE email_process_log DROP FOREIGN KEY FK_A53D0B31DB939101');
        $this->addSql('ALTER TABLE email_receivers DROP FOREIGN KEY FK_D54DEDDA15303B9');
        $this->addSql('ALTER TABLE user_token DROP FOREIGN KEY FK_BDF55A63A76ED395');
        $this->addSql('DROP TABLE email_process_log');
        $this->addSql('DROP TABLE email_receivers');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_token');
    }
}
