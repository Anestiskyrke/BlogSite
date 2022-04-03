<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401112930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE blog_post_blog_post (blog_post_source INT NOT NULL, blog_post_target INT NOT NULL, INDEX IDX_5F433553B69807E8 (blog_post_source), INDEX IDX_5F433553AF7D5767 (blog_post_target), PRIMARY KEY(blog_post_source, blog_post_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE blog_post_blog_post ADD CONSTRAINT FK_5F433553B69807E8 FOREIGN KEY (blog_post_source) REFERENCES blog_post (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blog_post_blog_post ADD CONSTRAINT FK_5F433553AF7D5767 FOREIGN KEY (blog_post_target) REFERENCES blog_post (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE blog_post_blog_post');
        $this->addSql('ALTER TABLE author CHANGE username username VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE password password VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE email email VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE profile_image profile_image VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE blog_post CHANGE slug slug VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE description description LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image_url image_url LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE category category VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE messenger_messages CHANGE body body LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE headers headers LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE queue_name queue_name VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
