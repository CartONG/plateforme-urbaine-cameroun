<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218174810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (69, 'Abong-Mbang', '', 'CM003002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (70, 'Afanloum', '', 'CM002005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 29)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (71, 'Ako', '', 'CM007003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 32)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (72, 'Akoeman', '', 'CM002010001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (73, 'Akom 2', '', 'CM009003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (74, 'Akono', '', 'CM002006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 30)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (75, 'Akonolinga', '', 'CM002009001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 62)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (76, 'Akwaya', '', 'CM010004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 53)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (77, 'Alou', '', 'CM010003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (78, 'Ambam', '', 'CM009004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (79, 'Assamba', '', 'CM002005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 29)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (80, 'Awae', '', 'CM002005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 29)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (81, 'Ayos', '', 'CM002009002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 62)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (82, 'Babadjou', '', 'CM008001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (83, 'Babessi', '', 'CM007007001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (84, 'Bafang', '', 'CM008002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (85, 'Bafia', '', 'CM002003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 27)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (86, 'Bafoussam 1er', '', 'CM008006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (87, 'Bafoussam 2e', '', 'CM008006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (88, 'Bafoussam 3e', '', 'CM008006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (89, 'Bafut', '', 'CM007005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (90, 'Baham', '', 'CM008003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (91, 'Bakou', '', 'CM008002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (92, 'Bali', '', 'CM007005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (93, 'Balikumbat', '', 'CM007007002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (94, 'Bamenda 1st', '', 'CM007005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (95, 'Bamenda 2nd', '', 'CM007005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (96, 'Bamenda 3rd', '', 'CM007005005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (97, 'Bamendjou', '', 'CM008003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (98, 'Bamusso', '', 'CM010006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (99, 'Bana', '', 'CM008002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (100, 'Bandja', '', 'CM008002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (101, 'Bandjoun', '', 'CM008004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (102, 'Bangangte', '', 'CM008007001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (103, 'Bangem', '', 'CM010002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (104, 'Bangou', '', 'CM008003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (105, 'Bangourain', '', 'CM008008001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (106, 'Banka', '', 'CM008002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (107, 'Bankim', '', 'CM001003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (108, 'Banwa', '', 'CM008002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (109, 'Banyo', '', 'CM001003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (110, 'Bare-Bakem', '', 'CM005001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 59)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (111, 'Bascheo', '', 'CM006001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (112, 'Bassamba', '', 'CM008007002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (113, 'Batcham', '', 'CM008001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (114, 'Batchenga', '', 'CM002002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 38)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (115, 'Batibo', '', 'CM007006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (116, 'Batie', '', 'CM008003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (117, 'Batouri', '', 'CM003003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (118, 'Bayangam', '', 'CM008004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (119, 'Bazou', '', 'CM008007003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (120, 'Bebeng', '', 'CM003002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (121, 'Beka', '', 'CM006002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (122, 'Belabo', '', 'CM003004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (123, 'Belel', '', 'CM001005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (124, 'Belo', '', 'CM007001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 49)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (125, 'Bengbis', '', 'CM009001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (126, 'Bertoua 1er', '', 'CM003004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (127, 'Bertoua 2e', '', 'CM003004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (128, 'Betare-Oya', '', 'CM003004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (129, 'Bibemi', '', 'CM006001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (130, 'Bibey', '', 'CM002001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 37)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (131, 'Bikok', '', 'CM002006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 39)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (132, 'Bipindi', '', 'CM009003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (133, 'Biwong-Bane', '', 'CM009002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (134, 'Biwong-Bulu', '', 'CM009002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (135, 'Biyouha', '', 'CM002008001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (136, 'Blangoua', '', 'CM004002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (137, 'Bogo', '', 'CM004001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (138, 'Bokito', '', 'CM002003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (139, 'Bombe', '', 'CM003003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (140, 'Bondjock', '', 'CM002008002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (141, 'Bot-Makak', '', 'CM002008003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (142, 'Bourrha', '', 'CM004006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (143, 'Buea', '', 'CM010001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (144, 'Bum', '', 'CM007001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (145, 'Campo', '', 'CM009003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (146, 'Darak', '', 'CM004002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (147, 'Dargala', '', 'CM004001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (148, 'Datcheka', '', 'CM004003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 49)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (149, 'Dembo', '', 'CM006001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (150, 'Demsa', '', 'CM006001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (151, 'Deuk', '', 'CM002003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (152, 'Dibamba', '', 'CM005003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (153, 'Dibang', '', 'CM002008004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (154, 'Dibombari', '', 'CM005001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (155, 'Dikome Balue', '', 'CM010006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 53)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (156, 'Dimako', '', 'CM003002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (157, 'Dir', '', 'CM001004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 32)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (158, 'Dizangue', '', 'CM005003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (159, 'Djebem', '', 'CM008004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (160, 'Djohong', '', 'CM001004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 32)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (161, 'Djoum', '', 'CM009001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (162, 'Douala 1er', '', 'CM005004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (163, 'Douala 2e', '', 'CM005004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (164, 'Douala 3e', '', 'CM005004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (165, 'Douala 4e', '', 'CM005004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (166, 'Douala 5e', '', 'CM005004005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (167, 'Douala 6e', '', 'CM005004006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 68)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (168, 'Doume', '', 'CM003002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (169, 'Doumetang', '', 'CM003002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (170, 'Dschang', '', 'CM008005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (171, 'Dzeng', '', 'CM002010002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (172, 'Ebebda', '', 'CM002002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (173, 'Ebolowa 1er', '', 'CM009002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (174, 'Ebolowa 2e', '', 'CM009002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (175, 'Edea 1er', '', 'CM005003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (176, 'Edea 2e', '', 'CM005003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (177, 'Edzendouan', '', 'CM002005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (178, 'Efoulan', '', 'CM009002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (179, 'Ekondo Titi', '', 'CM010006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (180, 'Elig-Mfomo', '', 'CM002002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (181, 'Endom', '', 'CM002009003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 62)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (182, 'Eseka', '', 'CM002008005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (183, 'Esse', '', 'CM002005005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (184, 'Evodoula', '', 'CM002002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (185, 'Eyumodjock', '', 'CM010004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (186, 'Fifinda', '', 'CM009003006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (187, 'Figuil', '', 'CM006003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (188, 'Fiko', '', 'CM005001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (189, 'Fokoue', '', 'CM008005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (190, 'Fongo-Tongo', '', 'CM008005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (191, 'Fontem', '', 'CM010003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (192, 'Fotokol', '', 'CM004002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (193, 'Foumban', '', 'CM008008002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (194, 'Foumbot', '', 'CM008008003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (195, 'Fundong', '', 'CM007001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (196, 'Fungom', '', 'CM007004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (197, 'Furu-Awa', '', 'CM007004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (198, 'Galim', '', 'CM008001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (199, 'Galim-Tignere', '', 'CM001002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (200, 'Gari Gombo', '', 'CM003001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 49)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (201, 'Garoua-Boulai', '', 'CM003004006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (202, 'Garoua 1er', '', 'CM006001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (203, 'Garoua 2e', '', 'CM006001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (204, 'Garoua 3e', '', 'CM006001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (205, 'Gazawa', '', 'CM004001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 19)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (206, 'Gobo', '', 'CM004003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (207, 'Goulfey', '', 'CM004002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 20)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (208, 'Guere', '', 'CM004003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (209, 'Guider', '', 'CM006003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (210, 'Guidiguis', '', 'CM004004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 22)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (211, 'Hile-Alifa', '', 'CM004002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 20)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (212, 'Hina', '', 'CM004006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 24)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (213, 'Idabato', '', 'CM010006004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (214, 'Issanguele', '', 'CM010006005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (215, 'Jakiri', '', 'CM007002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (216, 'Kaele', '', 'CM004004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 22)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (217, 'Kai-Kai', '', 'CM004003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (218, 'Kalfou', '', 'CM004003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (219, 'Kar-Hay', '', 'CM004003006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (220, 'Kekem', '', 'CM008002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (221, 'Kette', '', 'CM003003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (222, 'Kiiki', '', 'CM002003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 11)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (223, 'Kolofata', '', 'CM004005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 23)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (224, 'Kombo Abedimo', '', 'CM010006006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (225, 'Kombo Itindi', '', 'CM010006007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (226, 'Kon Yambetta', '', 'CM002003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 38)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (227, 'Kontcha', '', 'CM001002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (228, 'Konye', '', 'CM010005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (229, 'Kouoptamo', '', 'CM008008004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (230, 'Kousseri', '', 'CM004002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (231, 'Koutaba', '', 'CM008008005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (232, 'Koza', '', 'CM004006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (233, 'Kribi 1er', '', 'CM009003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (234, 'Kribi 2e', '', 'CM009003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (235, 'Kumba 1st', '', 'CM010005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (236, 'Kumba 2nd', '', 'CM010005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (237, 'Kumba 3rd', '', 'CM010005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (238, 'Kumbo', '', 'CM007002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (239, 'Kye-Ossi', '', 'CM009004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (240, 'Lagdo', '', 'CM006001008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (241, 'Lembe Yezoum', '', 'CM002001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 37)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (242, 'Limbe 1st', '', 'CM010001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (243, 'Limbe 2nd', '', 'CM010001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (244, 'Limbe 3rd', '', 'CM010001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (245, 'Lobo', '', 'CM002002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (246, 'Logone-Birni', '', 'CM004002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (247, 'Lolodorf', '', 'CM009003007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (248, 'Lomie', '', 'CM003002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (249, 'Loum', '', 'CM005001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (250, 'Maan', '', 'CM009004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (251, 'Madingring', '', 'CM006004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (252, 'Maga', '', 'CM004003007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (253, 'Magba', '', 'CM008008006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (254, 'Makak', '', 'CM002008006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (255, 'Makari', '', 'CM004002008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (256, 'Makenene', '', 'CM002003006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 38)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (257, 'Malantouen', '', 'CM008008007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (258, 'Mamfe', '', 'CM010004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (259, 'Mandjou', '', 'CM003004007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (260, 'Manjo', '', 'CM005001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (261, 'Maroua 1er', '', 'CM004001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (262, 'Maroua 2e', '', 'CM004001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (263, 'Maroua 3e', '', 'CM004001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (264, 'Martap', '', 'CM001005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (265, 'Massangam', '', 'CM008008008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (266, 'Massock-Songloulou', '', 'CM005003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (267, 'Matomb', '', 'CM002008007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (268, 'Mayo-Baleo', '', 'CM001002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (269, 'Mayo-Darle', '', 'CM001003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (270, 'Mayo-Hourna', '', 'CM006001009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (271, 'Mayo-Moskota', '', 'CM004006004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (272, 'Mayo-Oulo', '', 'CM006003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (273, 'Mbalmayo', '', 'CM002010003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (274, 'Mbandjock', '', 'CM002001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 39)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (275, 'Mbang', '', 'CM003003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (276, 'Mbanga', '', 'CM005001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (277, 'Mbangassina', '', 'CM002004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (278, 'Mbankomo', '', 'CM002006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (279, 'Mbe', '', 'CM001005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (280, 'Mbengwi', '', 'CM007006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (281, 'Mboma', '', 'CM003002009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (282, 'Mbonge', '', 'CM010005005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (283, 'Mbotoro', '', 'CM003003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 25)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (284, 'Mbouanz', '', 'CM003002008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 24)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (285, 'Mbouda', '', 'CM008001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (286, 'Mbven', '', 'CM007002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (287, 'Meiganga', '', 'CM001004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 26)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (288, 'Melon', '', 'CM005001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (289, 'Menchum-Valley', '', 'CM007004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (290, 'Mengang', '', 'CM002009004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 62)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (291, 'Mengong', '', 'CM009002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 14)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (292, 'Mengueme', '', 'CM002010004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (293, 'Meri', '', 'CM004001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 27)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (294, 'Messamena', '', 'CM003002010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 24)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (295, 'Messok', '', 'CM003002011', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 24)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (296, 'Messondo', '', 'CM002008008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (297, 'Meyomessala', '', 'CM009001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (298, 'Meyomessi', '', 'CM009001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (299, 'Mfou', '', 'CM002005006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 17)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (300, 'Mindif', '', 'CM004004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 30)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (301, 'Mindourou', '', 'CM003002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 24)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (302, 'Minta', '', 'CM002001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 15)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (303, 'Mintom 2', '', 'CM009001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (304, 'Misaje', '', 'CM007003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (305, 'Mvengue', '', 'CM009003008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (306, 'Nanga-Eboko', '', 'CM002001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 17)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (307, 'Ndelele', '', 'CM003003006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 19)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (308, 'Ndem Nam', '', 'CM003003007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 19)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (309, 'Ndiang', '', 'CM003004005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 20)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (310, 'Ndikinimeki', '', 'CM002003007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 15)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (311, 'Ndom', '', 'CM005003007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (312, 'Ndop', '', 'CM007007003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (313, 'Ndoukoula', '', 'CM004001008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 21)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (314, 'Ndu', '', 'CM007003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (315, 'Ngambe', '', 'CM005003008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (316, 'Ngambe-Tikar', '', 'CM002004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 16)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (317, 'Nganha', '', 'CM001005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (318, 'Ngaoui', '', 'CM001004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (319, 'Ngaoundal', '', 'CM001001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 11)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (320, 'Ngaoundere 1er', '', 'CM001005005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (321, 'Ngaoundere 2e', '', 'CM001005006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (322, 'Ngaoundere 3e', '', 'CM001005007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (323, 'Ngie', '', 'CM007006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (324, 'Ngog-Mapubi', '', 'CM002008009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (325, 'Ngomedzap', '', 'CM002010005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (326, 'Ngoro', '', 'CM002004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 16)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (327, 'Ngoulemakong', '', 'CM009002008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 32)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (328, 'Ngoumou', '', 'CM002006004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 36)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (329, 'Ngoura', '', 'CM003004008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 20)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (330, 'Ngoyla', '', 'CM003002012', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 18)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (331, 'Nguelemendouka', '', 'CM003002013', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 18)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (332, 'Nguibassal', '', 'CM002008010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 61)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (333, 'Nguti', '', 'CM010002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (334, 'Ngwei', '', 'CM005003009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (335, 'Niete', '', 'CM009003009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (336, 'Nitoukou', '', 'CM002003008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 15)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (337, 'Njikwa', '', 'CM007006004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (338, 'Njimom', '', 'CM008008009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 60)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (339, 'Njinikom', '', 'CM007001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (340, 'Njombe-Penja', '', 'CM005001009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (341, 'Nkambe', '', 'CM007003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (342, 'Nkolmetet', '', 'CM002010006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (343, 'Nkolofamba', '', 'CM002005007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (344, 'Nkondjock', '', 'CM005002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 59)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (345, 'Nkong-Ni', '', 'CM008005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (346, 'Nkongsamba 1er', '', 'CM005001010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (347, 'Nkongsamba 2e', '', 'CM005001011', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (348, 'Nkongsamba 3e', '', 'CM005001012', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (349, 'Nkoteng', '', 'CM002001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (350, 'Nkum', '', 'CM007002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (351, 'Nlonako', '', 'CM005001013', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (352, 'Noni', '', 'CM007002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (353, 'Nord-Makombe', '', 'CM005002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 59)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (354, 'Nsem', '', 'CM002001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (355, 'Ntui', '', 'CM002004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (356, 'Nwa', '', 'CM007003005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (357, 'Nyakokombo', '', 'CM002009005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 62)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (358, 'Nyambaka', '', 'CM001005008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 67)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (359, 'Nyanon', '', 'CM005003010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (360, 'Nye''ete', '', 'CM009003010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 64)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (361, 'Obala', '', 'CM002002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (362, 'Okola', '', 'CM002002008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (363, 'Oku', '', 'CM007002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (364, 'Olamze', '', 'CM009004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 66)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (365, 'Ombessa', '', 'CM002003009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (366, 'Oveng', '', 'CM009001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 53)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (367, 'Penka-Michel', '', 'CM008005005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (368, 'Pette', '', 'CM004001009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 49)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (369, 'Pitoa', '', 'CM006001010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (370, 'Poli', '', 'CM006002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 52)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (371, 'Porhi', '', 'CM004004006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (372, 'Pouma', '', 'CM005003011', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (373, 'Rey-Bouba', '', 'CM006004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 56)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (374, 'Saa', '', 'CM002002009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 43)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (375, 'Salapoumbe', '', 'CM003001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (376, 'Sangmelima', '', 'CM009001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 53)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (377, 'Santa', '', 'CM007005006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (378, 'Santchou', '', 'CM008005006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (379, 'Soa', '', 'CM002005008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (380, 'Somalomo', '', 'CM003002014', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (381, 'Soulede-Roua', '', 'CM004006007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 58)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (382, 'Taibong', '', 'CM004004007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 50)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (383, 'Tchatibali', '', 'CM004003008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 63)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (384, 'Ngong', '', 'CM006001011', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (385, 'Tchollire', '', 'CM006004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (386, 'Tibati', '', 'CM001001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 31)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (387, 'Tignere', '', 'CM001002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 32)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (388, 'Tiko', '', 'CM010001006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (389, 'Toko', '', 'CM010006009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 18)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (390, 'Tokombere', '', 'CM004005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 37)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (391, 'Tombel', '', 'CM010002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 14)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (392, 'Tonga', '', 'CM008007004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 49)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (393, 'Touboro', '', 'CM006004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (394, 'Touroua', '', 'CM006001012', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 42)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (395, 'Tubah', '', 'CM007005007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 47)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (396, 'Upper Bayang', '', 'CM010004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 16)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (397, 'Vele', '', 'CM004003009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (398, 'Wabane', '', 'CM010003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 15)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (399, 'Waza', '', 'CM004002009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (400, 'West-Coast', '', 'CM010001007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (401, 'Widikum', '', 'CM007006005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 48)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (402, 'Wina', '', 'CM004003010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (403, 'Wum', '', 'CM007004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 46)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (404, 'Yabassi', '', 'CM005002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 59)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (405, 'Yagoua', '', 'CM004003011', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 35)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (406, 'Yaounde 1er', '', 'CM002007001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (407, 'Yaounde 2e', '', 'CM002007002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (408, 'Yaounde 3e', '', 'CM002007003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (409, 'Yaounde 4e', '', 'CM002007004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (410, 'Yaounde 5e', '', 'CM002007005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (411, 'Yaounde 6e', '', 'CM002007006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (412, 'Yaounde 7e', '', 'CM002007007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 45)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (413, 'Yingui', '', 'CM005002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 59)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (414, 'Yokadouma', '', 'CM003001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 33)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (415, 'Yoko', '', 'CM002004005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 44)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (416, 'Zina', '', 'CM004002010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 34)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id) 
                VALUES (417, 'Zoetele', '', 'CM009001008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 51)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (418, 'Mogode', '', 'CM004006005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (419, 'Mokolo', '', 'CM004006006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 41)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (420, 'Moloundou', '', 'CM003001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 13)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (421, 'Mombo', '', 'CM005001008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 54)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (422, 'Monatele', '', 'CM002002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 31)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (423, 'Mora', '', 'CM004005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 40)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (424, 'Mouanko', '', 'CM005003006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 65)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (425, 'Moulvoudaye', '', 'CM004004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 37)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (426, 'Moutourwa', '', 'CM004004005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 37)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (427, 'Mundemba', '', 'CM010006008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 57)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (428, 'Muyuka', '', 'CM010001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 20)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (429, 'Mvangane', '', 'CM009002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 4, 55)");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
