#
# Table structure for table 'tx_apldap_domain_model_config'
#
CREATE TABLE tx_apldap_domain_model_config (
		uid int(11) NOT NULL auto_increment,
		pid int(11) DEFAULT '0' NOT NULL,
		tstamp int(11) DEFAULT '0' NOT NULL,
		crdate int(11) DEFAULT '0' NOT NULL,
		cruser_id int(11) DEFAULT '0' NOT NULL,
		deleted tinyint(4) DEFAULT '0' NOT NULL,
		hidden tinyint(4) DEFAULT '0' NOT NULL,
		name varchar(255) DEFAULT '' NOT NULL,
		ldap_type int(11) DEFAULT '0' NOT NULL,
		ldap_protocol int(11) DEFAULT '0' NOT NULL,
		ldap_host varchar(255) DEFAULT '' NOT NULL,
		ldap_port int(11) DEFAULT '0' NOT NULL,
		ldap_use_tls tinyint(1) DEFAULT '0' NOT NULL,
		ldap_bind_dn tinytext NOT NULL,
		ldap_password varchar(255) DEFAULT '' NOT NULL,
		PRIMARY KEY (uid),
		KEY parent (pid)
);
