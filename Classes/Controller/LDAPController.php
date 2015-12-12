<?php
namespace AP\ApLdap\Controller;

use AP\ApLdap\Exception\LDAPException,
	TYPO3\CMS\Core\Utility\GeneralUtility,
	AP\ApLdap\Utility\LDAPUtility,
	AP\ApLdap\Exception\ConnectionException,
	\TYPO3\CMS\Core\Messaging\FlashMessage,
	\TYPO3\CMS\Extbase\Utility\LocalizationUtility,
	TYPO3\CMS\Core\Utility\DebugUtility;

/**
 * LDAP backend module controller
 *
 * @package TYPO3
 * @subpackage tx_apldap
 * @author Alexander Pankow <info@alexander-pankow.de>
 */
class LDAPController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * Redirects to list action
	 */
	public function indexAction() {
		$this->forward('list');
	}

	/**
	 * Lists all configurations
	 */
	public function listAction() {
		/** @var \AP\ApLdap\Domain\Repository\ConfigRepository $configRepository */
		$configRepository = $this->objectManager->get('AP\\ApLdap\\Domain\\Repository\\ConfigRepository');
		$ldapConfigs = $configRepository->findAll();

		$this->view->assign('ldapConfigs', $ldapConfigs);
	}

	/**
	 * Redirect to creation form to create a new configuration
	 */
	public function newAction() {
		$this->redirectToUri("alt_doc.php?edit[tx_apldap_domain_model_config][0]=new&returnUrl=" . $this->getReturnUrl());
	}

	/**
	 * Redirects to edit form to edit this configuration
	 *
	 * @param int $configUid
	 */
	public function editAction($configUid) {
		$this->redirectToUri("alt_doc.php?edit[tx_apldap_domain_model_config][$configUid]=edit&returnUrl=" . $this->getReturnUrl());
	}

	/**
	 * Check LDAP configuration; trying to connect and bind against LDAP server
	 *
	 * @param int $configUid
	 */
	public function checkConfigAction($configUid) {
		/** @var \AP\ApLdap\Utility\LDAPUtility $ldapUtility */
		$ldapUtility = $this->objectManager->get('AP\\ApLdap\\Utility\\LDAPUtility');
		try {
			$ldapUtility->connect($configUid);
			$ldapUtility->bind();
			$ldapUtility->disconnect();

			// set flash message
			$title = LocalizationUtility::translate('list.checkConfig.ok.title', $this->extensionName);
			$message = LocalizationUtility::translate('list.checkConfig.ok.message', $this->extensionName);
			$this->controllerContext->getFlashMessageContainer()->add($message, $title, FlashMessage::OK);
		} catch (LDAPException $e) {
			$title = LocalizationUtility::translate('list.checkConfig.failed.title', $this->extensionName);
			$message = $e->getMessage();
			if ($e instanceof ConnectionException)
				$message .= ': ' . $e->getLdapMessage() . ' [' . $e->getLdapCode() . ']';
			$this->controllerContext->getFlashMessageContainer()->add($message, $title, FlashMessage::ERROR);
		}

		$this->forward('index');
	}

	/**
	 * @return string
	 */
	protected function getReturnUrl() {
		return rawurlencode(\TYPO3\CMS\Backend\Utility\BackendUtility::getModuleUrl('tools_ApLdapLdap'));
	}
}
