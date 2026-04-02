CREATE TABLE tx_blog_domain_model_author (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned DEFAULT '0' NOT NULL,
  tstamp int(10) unsigned DEFAULT '0' NOT NULL,
  crdate int(10) unsigned DEFAULT '0' NOT NULL,
  deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
  hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l18n_parent int(10) unsigned DEFAULT '0' NOT NULL,
  l10n_state text,
  l18n_diffsource mediumblob,
  name varchar(100) DEFAULT '' NOT NULL,
  slug varchar(2048) DEFAULT '' NOT NULL,
  title varchar(100) DEFAULT '' NOT NULL,
  website varchar(255) DEFAULT '' NOT NULL,
  email varchar(255) DEFAULT '' NOT NULL,
  location varchar(255) DEFAULT '' NOT NULL,
  image int(11) DEFAULT '0' NOT NULL,
  twitter varchar(255) DEFAULT '' NOT NULL,
  linkedin varchar(255) DEFAULT '' NOT NULL,
  xing varchar(255) DEFAULT '' NOT NULL,
  instagram varchar(255) DEFAULT '' NOT NULL,
  profile varchar(255) DEFAULT '' NOT NULL,
  bio text,
  details_page int(11) DEFAULT '0' NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid,deleted,hidden),
  KEY language_identifier (l18n_parent,sys_language_uid)
);

CREATE TABLE tx_blog_domain_model_tag (
  uid int(10) unsigned NOT NULL auto_increment,
  pid int(10) unsigned DEFAULT '0' NOT NULL,
  tstamp int(10) unsigned DEFAULT '0' NOT NULL,
  crdate int(10) unsigned DEFAULT '0' NOT NULL,
  deleted smallint(5) unsigned DEFAULT '0' NOT NULL,
  hidden smallint(5) unsigned DEFAULT '0' NOT NULL,
  sys_language_uid int(11) DEFAULT '0' NOT NULL,
  l18n_parent int(10) unsigned DEFAULT '0' NOT NULL,
  l10n_state text,
  l18n_diffsource mediumblob,
  title varchar(255) DEFAULT '' NOT NULL,
  slug varchar(2048) DEFAULT '' NOT NULL,
  description text,
  PRIMARY KEY (uid),
  KEY parent (pid,deleted,hidden),
  KEY language_identifier (l18n_parent,sys_language_uid)
);

CREATE TABLE tx_blog_post_author_mm (
  uid_local int(11) unsigned DEFAULT '0' NOT NULL,
  uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  sorting int(11) unsigned DEFAULT '0' NOT NULL,
  sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,
  PRIMARY KEY (uid_local, uid_foreign),
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);

CREATE TABLE pages (
  featured_image int(11) unsigned DEFAULT '0' NOT NULL,
  comments_active tinyint(4) DEFAULT '1' NOT NULL,
  publish_date int(11) DEFAULT '0' NOT NULL,
  archive_date int(11) DEFAULT '0' NOT NULL,
  crdate_month int(11) DEFAULT '0' NOT NULL,
  crdate_year int(11) DEFAULT '0' NOT NULL,
  comments text,
  tags text,
  authors text
);

CREATE TABLE sys_category (
  slug varchar(2048) DEFAULT '' NOT NULL
);
