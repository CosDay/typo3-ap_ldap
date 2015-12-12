<?php
if (!defined ('TYPO3_MODE')) die ('Access denied.');

$extKey = 'ap_ldap';
$extPath = \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($extKey);

return array(
	'ctrl' => array(
		'title' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.title',
		'label' => 'name',
		'adminOnly' => true,
		'rootLevel' => 1,
		'dividers2tabs' => true,
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'default_sortby' => 'ORDER BY name',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden'
		),
		'iconfile' => $extPath . 'ext_icon.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden,name,ldap_type,ldap_protocol,ldap_host,ldap_port,ldap_use_tls,ldap_bind_dn,ldap_password'
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.xml:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'name' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.name',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'required,trim',
			)
		),
		'ldap_type' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_type',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_type.I.0', '0'),
//					array('LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_type.I.1', '1')
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'ldap_protocol' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_protocol',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_protocol.I.0', '3'),
					array('LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_protocol.I.1', '2'),
				),
				'size' => 1,
				'maxitems' => 1,
			)
		),
		'ldap_host' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_host',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'required,trim',
			)
		),
		'ldap_port' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_port',
			'config' => array(
				'type' => 'input',
				'size' => '5',
				'max' => '5',
				'eval' => 'int,trim',
				'default' => '389',
			)
		),
		'ldap_use_tls' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_use_tls',
			'config' => array(
				'type' => 'check',
				'default' => 0
			)
		),
		'ldap_bind_dn' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_bind_dn',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'eval' => 'trim',
			)
		),
		'ldap_password' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.ldap_password',
			'config' => array(
				'type' => 'input',
				'size' => '30',
				'max' => '255',
				'eval' => 'password',
			)
		)
	),
	'types' => array(
		0 => array(
			'showitem' => '
				--div--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.tabs.general,name,
				--div--;LLL:EXT:' . $extKey . '/Resources/Private/Language/locallang_db.xml:tx_apldap_domain_model_config.tabs.server, ldap_type, ldap_protocol, ldap_host, ldap_port, ldap_use_tls, ldap_bind_dn, ldap_password'
		)
	)
);
