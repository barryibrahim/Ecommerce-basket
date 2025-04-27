<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250426104228 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE produit_user (produit_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_A40BABA6F347EFB (produit_id), INDEX IDX_A40BABA6A76ED395 (user_id), PRIMARY KEY(produit_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_user ADD CONSTRAINT FK_A40BABA6F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_user ADD CONSTRAINT FK_A40BABA6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_user DROP FOREIGN KEY FK_A40BABA6F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit_user DROP FOREIGN KEY FK_A40BABA6A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE produit_user
        SQL);
    }
}
