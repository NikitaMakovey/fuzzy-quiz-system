<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240325120328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_attempt_question ADD question_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz_attempt_question ADD CONSTRAINT FK_C2B9DA301E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_C2B9DA301E27F6BF ON quiz_attempt_question (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quiz_attempt_question DROP CONSTRAINT FK_C2B9DA301E27F6BF');
        $this->addSql('DROP INDEX IDX_C2B9DA301E27F6BF');
        $this->addSql('ALTER TABLE quiz_attempt_question DROP question_id');
    }
}
