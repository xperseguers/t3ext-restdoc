#
# Table structure for table 'tx_restdoc_tocs'
#
CREATE TABLE tx_restdoc_toc (
	tt_content int(11) DEFAULT '0' NOT NULL,
	root varchar(255) DEFAULT '' NOT NULL,
	document varchar(255) DEFAULT '' NOT NULL,
	checksum char(32) NOT NULL,
	tstamp int(11) unsigned DEFAULT '0' NOT NULL,
	crdate int(11) unsigned DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,
	url varchar(4000),

	KEY plugin (tt_content)
) ENGINE=InnoDB;