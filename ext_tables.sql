CREATE TABLE pages (
	tx_booster_faqs  int(11) DEFAULT '0' NOT NULL
);

CREATE TABLE pages_language_overlay (
	tx_booster_faqs  int(11) DEFAULT '0' NOT NULL
);


CREATE TABLE tx_booster_domain_model_content (
	uid int(11) unsigned DEFAULT 0 NOT NULL auto_increment,
	pid int(11) DEFAULT 0 NOT NULL,

	name varchar(255) DEFAULT '' NOT NULL,
	text text NOT NULL,

	tstamp int(11) unsigned DEFAULT 0 NOT NULL,
	crdate int(11) unsigned DEFAULT 0 NOT NULL,
	deleted tinyint(4) unsigned DEFAULT 0 NOT NULL,
	hidden tinyint(4) unsigned DEFAULT 0 NOT NULL,
	sys_language_uid int(11) DEFAULT 0 NOT NULL,
	l18n_parent int(11) DEFAULT 0 NOT NULL,
	l18n_diffsource mediumblob NOT NULL,

	PRIMARY KEY (uid),
	KEY parent (pid),
);

#
# Table structure for table 'tx_booster_pages_content_mm'
#
CREATE TABLE tx_booster_pages_content_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	fieldname varchar(255) DEFAULT '' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
