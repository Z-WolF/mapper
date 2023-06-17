<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230617214740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE base_layer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feature_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE feature_category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE game_config_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE reset_password_request_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE base_layer (id INT NOT NULL, game_id INT NOT NULL, name VARCHAR(255) NOT NULL, url TEXT NOT NULL, max_zoom INT DEFAULT NULL, position INT NOT NULL, enabled BOOLEAN DEFAULT false NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_59F0B0C8E48FD905 ON base_layer (game_id)');
        $this->addSql('CREATE TABLE feature (id INT NOT NULL, category_id INT NOT NULL, base_layer_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, enabled BOOLEAN DEFAULT true NOT NULL, geometry geometry(GEOMETRY, 0) NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1FD7756612469DE2 ON feature (category_id)');
        $this->addSql('CREATE INDEX IDX_1FD77566A0855E35 ON feature (base_layer_id)');
        $this->addSql('CREATE TABLE feature_category (id INT NOT NULL, parent_id INT DEFAULT NULL, game_id INT NOT NULL, name VARCHAR(255) NOT NULL, min_zoom INT NOT NULL, max_zoom INT NOT NULL, color VARCHAR(255) DEFAULT NULL, enabled BOOLEAN DEFAULT true NOT NULL, label BOOLEAN DEFAULT false NOT NULL, position INT NOT NULL, old_id INT DEFAULT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7FA1B6FE727ACA70 ON feature_category (parent_id)');
        $this->addSql('CREATE INDEX IDX_7FA1B6FEE48FD905 ON feature_category (game_id)');
        $this->addSql('CREATE TABLE game (id INT NOT NULL, name VARCHAR(255) NOT NULL, short_name VARCHAR(255) NOT NULL, position INT NOT NULL, slug VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_by VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C5E237E06 ON game (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C3EE4B093 ON game (short_name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_232B318C989D9B62 ON game (slug)');
        $this->addSql('CREATE TABLE game_config (id INT NOT NULL, game_id INT NOT NULL, default_base_layer_id INT NOT NULL, default_zoom INT NOT NULL, min_zoom INT DEFAULT NULL, max_zoom INT DEFAULT NULL, default_center geometry(POINT, 0) NOT NULL, scale_x DOUBLE PRECISION DEFAULT NULL, scale_y DOUBLE PRECISION DEFAULT NULL, offset_x DOUBLE PRECISION DEFAULT NULL, offset_y DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9BF82B4E48FD905 ON game_config (game_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_A9BF82B496A4C226 ON game_config (default_base_layer_id)');
        $this->addSql('CREATE TABLE reset_password_request (id INT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, expires_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7CE748AA76ED395 ON reset_password_request (user_id)');
        $this->addSql('COMMENT ON COLUMN reset_password_request.requested_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN reset_password_request.expires_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, is_verified BOOLEAN NOT NULL, created_by VARCHAR(255) DEFAULT NULL, updated_by VARCHAR(255) DEFAULT NULL, created_from_ip VARCHAR(45) DEFAULT NULL, updated_from_ip VARCHAR(45) DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');
        $this->addSql('ALTER TABLE base_layer ADD CONSTRAINT FK_59F0B0C8E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature ADD CONSTRAINT FK_1FD7756612469DE2 FOREIGN KEY (category_id) REFERENCES feature_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature ADD CONSTRAINT FK_1FD77566A0855E35 FOREIGN KEY (base_layer_id) REFERENCES base_layer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature_category ADD CONSTRAINT FK_7FA1B6FE727ACA70 FOREIGN KEY (parent_id) REFERENCES feature_category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE feature_category ADD CONSTRAINT FK_7FA1B6FEE48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_config ADD CONSTRAINT FK_A9BF82B4E48FD905 FOREIGN KEY (game_id) REFERENCES game (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE game_config ADD CONSTRAINT FK_A9BF82B496A4C226 FOREIGN KEY (default_base_layer_id) REFERENCES base_layer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE base_layer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feature_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE feature_category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE game_config_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE reset_password_request_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE base_layer DROP CONSTRAINT FK_59F0B0C8E48FD905');
        $this->addSql('ALTER TABLE feature DROP CONSTRAINT FK_1FD7756612469DE2');
        $this->addSql('ALTER TABLE feature DROP CONSTRAINT FK_1FD77566A0855E35');
        $this->addSql('ALTER TABLE feature_category DROP CONSTRAINT FK_7FA1B6FE727ACA70');
        $this->addSql('ALTER TABLE feature_category DROP CONSTRAINT FK_7FA1B6FEE48FD905');
        $this->addSql('ALTER TABLE game_config DROP CONSTRAINT FK_A9BF82B4E48FD905');
        $this->addSql('ALTER TABLE game_config DROP CONSTRAINT FK_A9BF82B496A4C226');
        $this->addSql('ALTER TABLE reset_password_request DROP CONSTRAINT FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE base_layer');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE feature_category');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE game_config');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE "user"');
    }
}
