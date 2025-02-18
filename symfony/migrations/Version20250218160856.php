<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250218160856 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (11, 'Bamboutos', '', 'CM008001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (12, 'Bénoué', '', 'CM006001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 6)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (13, 'Boumba-et-Ngoko', '', 'CM003001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 3)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (14, 'Boyo', '', 'CM007001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (15, 'Bui', '', 'CM007002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (16, 'Diamaré', '', 'CM004001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (17, 'Dja-et-Lobo', '', 'CM009001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 8)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (18, 'Djérem', '', 'CM001001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 1)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (19, 'Donga-Mantung', '', 'CM007003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (20, 'Fako', '', 'CM010001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (21, 'Faro', '', 'CM006002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 6)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (22, 'Faro-et-Déo', '', 'CM001002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 1)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (23, 'Haut-Nkam', '', 'CM008002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (24, 'Haut-Nyong', '', 'CM003002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 3)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (25, 'Haute-Sanaga', '', 'CM002001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (26, 'Hauts-Plateaux', '', 'CM008003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (27, 'Kadei', '', 'CM003003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 3)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (28, 'Koung-Khi', '', 'CM008004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (29, 'Kupe-Manenguba', '', 'CM010002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (30, 'Lebialem', '', 'CM010003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (31, 'Lekié', '', 'CM002002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (32, 'Logone-et-Chari', '', 'CM004002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (33, 'Lom-et-Djérem', '', 'CM003004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 3)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (34, 'Manyu', '', 'CM010004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (35, 'Mayo-Banyo', '', 'CM001003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 1)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (36, 'Mayo-Danay', '', 'CM004003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (37, 'Mayo-Kani', '', 'CM004004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (38, 'Mayo-Louti', '', 'CM006003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 6)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (39, 'Mayo-Rey', '', 'CM006004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 6)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (40, 'Mayo-Sava', '', 'CM004005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (41, 'Mayo-Tsanaga', '', 'CM004006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 4)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (42, 'Mbam-et-Inoubou', '', 'CM002003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (43, 'Mbam-et-Kim', '', 'CM002004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (44, 'Mbéré', '', 'CM001004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 1)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (45, 'Mefou-et-Afamba', '', 'CM002005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (46, 'Mefou-et-Akono', '', 'CM002006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (47, 'Meme', '', 'CM010005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (48, 'Menchum', '', 'CM007004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (49, 'Menoua', '', 'CM008005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (50, 'Mezam', '', 'CM007005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (51, 'Mfoundi', '', 'CM002007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (52, 'Mifi', '', 'CM008006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (53, 'Momo', '', 'CM007006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (54, 'Moungo', '', 'CM005001', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 5)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (55, 'Mvila', '', 'CM009002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 8)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (56, 'Ndé', '', 'CM008007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (57, 'Ndian', '', 'CM010006', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 9)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (58, 'Ngo-Ketunjia', '', 'CM007007', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 7)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (59, 'Nkam', '', 'CM005002', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 5)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (60, 'Noun', '', 'CM008008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 10)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (61, 'Nyong-et-Kellé', '', 'CM002008', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (62, 'Nyong-et-Mfoumou', '', 'CM002009', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (63, 'Nyong-et-So''o', '', 'CM002010', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 2)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (64, 'Océan', '', 'CM009003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 8)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (65, 'Sanaga-Maritime', '', 'CM005003', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 5)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (66, 'Vallée-du-Ntem', '', 'CM009004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 8)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (67, 'Vina', '', 'CM001005', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 1)");
        $this->addSql("INSERT INTO administrative_area (id, name, name_en, code, created_at, validated_at, administrative_scope_id, parent_id)
                   VALUES (68, 'Wouri', '', 'CM005004', '2018-12-17 00:00:00', '2019-01-04 00:00:00', 3, 5)");

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
    }
}
