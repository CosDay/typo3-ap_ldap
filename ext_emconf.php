<?php
/**
 * Extension Manager/Repository config file for ext "ap_ldap".
 *
 * @var $_EXTKEY
 */
$EM_CONF[$_EXTKEY] = array(
	'title' => 'LDAP',
	'description' => 'Provides general configuration and core files for communication with LDAP servers.',
	'author' => 'Alexander Pankow',
	'author_email' => 'info@alexander-pankow.de',
	'category' => 'services',
	'author_company' => '',
	'shy' => '',
	'dependencies' => 'cms',
	'conflicts' => '',
	'priority' => '',
	'module' => '',
	'state' => 'alpha',
	'internal' => '',
	'uploadfolder' => 0,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 1,
	'lockType' => '',
	'version' => '0.0.1',
	'constraints' => array(
		'depends' => array(
			'typo3' => '6.1.1-6.1.99',
			'cms' => '',
		),
		'conflicts' => array(),
		'suggests' => array(),
	),
	'suggests' => array(),
	'_md5_values_when_last_written' => '',
);
