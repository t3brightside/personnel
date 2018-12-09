CREATE TABLE tt_content (
	tx_personnel tinytext,
	tx_personnel_template int(11) DEFAULT '0' NOT NULL,
	tx_personnel_images int(1) DEFAULT '0' NOT NULL,
	tx_personnel_vcard int(1) DEFAULT '0' NOT NULL,
	tx_personnel_information int(1) DEFAULT '0' NOT NULL,
	tx_personnel_orderby tinytext,
	tx_personnel_paginate int(1) DEFAULT '0' NOT NULL,
	tx_personnel_paginateitems varchar(25),
);

CREATE TABLE tx_personnel_domain_model_person (
	title tinytext,
	firstname tinytext,
  lastname tinytext,
  profession tinytext,
  info text,
  phone tinytext,
  email tinytext,
  images int(11) unsigned DEFAULT '0',
	selected_categories text,
);
