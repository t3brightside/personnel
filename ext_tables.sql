CREATE TABLE tt_content (
	tx_personnel tinytext,
	tx_personnel_template varchar(25),
	tx_personnel_images int(1) DEFAULT '0' NOT NULL,
	tx_personnel_vcard int(1) DEFAULT '0' NOT NULL,
	tx_personnel_information int(1) DEFAULT '0' NOT NULL,
	tx_personnel_orderby tinytext,
	tx_personnel_startfrom varchar(6),
	tx_personnel_limit varchar(6),
);

CREATE TABLE tx_personnel_domain_model_person (
	title tinytext,
	firstname tinytext,
	lastname tinytext,
	profession tinytext,
	responsibility tinytext,
	info text,
	phone tinytext,
	email tinytext,
	images int(11) unsigned DEFAULT '0',
	selected_categories text,
	linkedin tinytext,
	xing tinytext,

	KEY parent (pid,sorting),
	KEY t3ver_oid (t3ver_oid,t3ver_wsid),
	KEY language (sys_language_uid)
);

CREATE TABLE pages (
	tx_personnel_authors tinytext,
);
