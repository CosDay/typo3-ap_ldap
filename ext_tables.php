<?php
/**
 * @var $_EXTKEY
 */
if (!defined ('TYPO3_MODE')) die ('Access denied.');

// Register backend module
if (TYPO3_MODE === 'BE') {
	/**
	 * Registers a Backend Module
	 */
	\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerModule(
		'AP.' . $_EXTKEY,
		'tools',
		// Submodule key
		'ldap',
		// Position
		'top',
		// An array holding the controller-action-combinations that are accessible
		array(
			'LDAP' => 'index, list, new, edit, checkConfig'
		),
		array(
			'access' => 'user,group',
			'icon'   => 'EXT:' . $_EXTKEY . '/ext_icon.gif',
			'labels' => 'LLL:EXT:' . $_EXTKEY . '/Resources/Private/Language/locallang_mod.xlf',
		)
	);
}
