<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230522144512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_interaction (id INT AUTO_INCREMENT NOT NULL, list_owner_id INT NOT NULL, related_user_id INT NOT NULL, value INT NOT NULL, INDEX IDX_9E963432515299 (list_owner_id), INDEX IDX_9E96343298771930 (related_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_interaction ADD CONSTRAINT FK_9E963432515299 FOREIGN KEY (list_owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_interaction ADD CONSTRAINT FK_9E96343298771930 FOREIGN KEY (related_user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_interaction DROP FOREIGN KEY FK_9E963432515299');
        $this->addSql('ALTER TABLE user_interaction DROP FOREIGN KEY FK_9E96343298771930');
        $this->addSql('DROP TABLE user_interaction');
    }
}
