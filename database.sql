-- Import in phpMyAdmin (Import tab) or run: mysql -u root < database.sql
-- WARNING: re-importing resets all tables, including saved reports.

CREATE DATABASE IF NOT EXISTS gambling_compliance
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gambling_compliance;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS reports, hotline_numbers, hotlines, scope_items, sections;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE sections (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  section_no   INT UNSIGNED NOT NULL,
  title        VARCHAR(255) NOT NULL,
  prepared_for VARCHAR(255) NOT NULL,
  summary      TEXT NOT NULL
) ENGINE=InnoDB;

CREATE TABLE scope_items (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  section_id INT UNSIGNED NOT NULL,
  label      VARCHAR(100) NOT NULL,
  icon       VARCHAR(30)  NOT NULL DEFAULT 'user',
  sort_order INT NOT NULL DEFAULT 0,
  FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE hotlines (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name        VARCHAR(150) NOT NULL,
  description TEXT NOT NULL,
  hours       VARCHAR(100) NOT NULL,
  note        VARCHAR(255) NULL,
  website     VARCHAR(255) NULL,
  icon        VARCHAR(30)  NOT NULL DEFAULT 'phone',
  sort_order  INT NOT NULL DEFAULT 0
) ENGINE=InnoDB;

CREATE TABLE hotline_numbers (
  id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hotline_id INT UNSIGNED NOT NULL,
  network    VARCHAR(50)  NOT NULL,
  display    VARCHAR(30)  NOT NULL,
  tel        VARCHAR(20)  NOT NULL,
  FOREIGN KEY (hotline_id) REFERENCES hotlines(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE reports (
  id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  site_url    VARCHAR(500) NOT NULL,
  description TEXT NOT NULL,
  contact     VARCHAR(150) NULL,
  status      ENUM('new','reviewed') NOT NULL DEFAULT 'new',
  created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO sections (section_no, title, prepared_for, summary) VALUES
(1, 'Anti-Illegal Gambling Enforcement and Compliance',
 'People addicted to online gambling.',
 'Establishes a framework to detect, suppress, and eliminate unauthorized gambling operations through regulatory oversight, digital monitoring, intelligence coordination, and enforcement measures.');

INSERT INTO scope_items (section_id, label, icon, sort_order) VALUES
(1, 'Minors', 'user', 1),
(1, 'Adults', 'users', 2);

INSERT INTO hotlines (name, description, hours, note, website, icon, sort_order) VALUES
('NCMH Crisis Hotline',
 'Free and confidential phone support for emotional distress, including gambling. Available nationwide in English and Filipino.',
 'Open 24 hours, every day', NULL, NULL, 'phone', 1),
('National Problem Gambling Helpline',
 'Launched by PAGCOR on May 26, 2026 with Seagulls Flock Organization Inc. It offers counseling for people harmed by gambling and for their family members.',
 'Open 24 hours, every day',
 'Get the current hotline number on the official PAGCOR website.',
 'https://www.pagcor.ph', 'heart', 2);

INSERT INTO hotline_numbers (hotline_id, network, display, tel) VALUES
(1, 'Smart', '0919 057 1553', '+639190571553'),
(1, 'Globe', '0966 351 4518', '+639663514518'),
(1, 'Other', '0917 899 8727', '+639178998727');
