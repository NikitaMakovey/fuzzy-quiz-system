<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240324153928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE quiz_attempt_answer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE quiz_attempt_question_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE quiz_attempt_answer (id INT NOT NULL, quiz_attempt_question_id INT NOT NULL, answer_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9453B9FC8B4006DF ON quiz_attempt_answer (quiz_attempt_question_id)');
        $this->addSql('CREATE INDEX IDX_9453B9FCAA334807 ON quiz_attempt_answer (answer_id)');
        $this->addSql('COMMENT ON COLUMN quiz_attempt_answer.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE quiz_attempt_question (id INT NOT NULL, quiz_attempt_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C2B9DA30F8FE9957 ON quiz_attempt_question (quiz_attempt_id)');
        $this->addSql('COMMENT ON COLUMN quiz_attempt_question.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE quiz_attempt_answer ADD CONSTRAINT FK_9453B9FC8B4006DF FOREIGN KEY (quiz_attempt_question_id) REFERENCES quiz_attempt_question (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_attempt_answer ADD CONSTRAINT FK_9453B9FCAA334807 FOREIGN KEY (answer_id) REFERENCES answer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_attempt_question ADD CONSTRAINT FK_C2B9DA30F8FE9957 FOREIGN KEY (quiz_attempt_id) REFERENCES quiz_attempt (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE quiz_attempt ADD quiz_id INT NOT NULL');
        $this->addSql('ALTER TABLE quiz_attempt ADD CONSTRAINT FK_AB6AFC6853CD175 FOREIGN KEY (quiz_id) REFERENCES quiz (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_AB6AFC6853CD175 ON quiz_attempt (quiz_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE quiz_attempt_answer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE quiz_attempt_question_id_seq CASCADE');
        $this->addSql('ALTER TABLE quiz_attempt_answer DROP CONSTRAINT FK_9453B9FC8B4006DF');
        $this->addSql('ALTER TABLE quiz_attempt_answer DROP CONSTRAINT FK_9453B9FCAA334807');
        $this->addSql('ALTER TABLE quiz_attempt_question DROP CONSTRAINT FK_C2B9DA30F8FE9957');
        $this->addSql('DROP TABLE quiz_attempt_answer');
        $this->addSql('DROP TABLE quiz_attempt_question');
        $this->addSql('ALTER TABLE quiz_attempt DROP CONSTRAINT FK_AB6AFC6853CD175');
        $this->addSql('DROP INDEX IDX_AB6AFC6853CD175');
        $this->addSql('ALTER TABLE quiz_attempt DROP quiz_id');
    }
}
