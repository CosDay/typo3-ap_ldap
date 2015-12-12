<?php
namespace AP\ApLdap\Exception;

/**
 * LDAP configuration exception. Throw if there are any errors while connecting or binding against ldap server.
 *
 * @package TYPO3
 * @subpackage tx_apldap
 * @author Alexander Pankow <info@alexander-pankow.de>
 */
class ConnectionException extends LDAPException {
	/**
	 * @var resource
	 */
	protected $ldapConnection;

	/**
	 * @param resource $ldapConnection
	 * @param string $additionalMessage
	 * @param \Exception $previous
	 */
	public function __construct(&$ldapConnection, $additionalMessage = '', \Exception $previous = null) {
		$this->ldapConnection = $ldapConnection;
		$this->message = $additionalMessage;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return __CLASS__ . ": " . ldap_err2str(ldap_errno($this->ldapConnection)) . "\n";
	}

	/**
	 * @return string
	 */
	public function getLdapMessage() {
		return ldap_error($this->ldapConnection);
	}

	/**
	 * @return int
	 */
	public function getLdapCode() {
		return ldap_errno($this->ldapConnection);
	}
}
