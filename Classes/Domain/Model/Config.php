<?php
namespace AP\ApLdap\Domain\Model;

/**
 * LDAP configuration model
 *
 * @package TYPO3
 * @subpackage tx_apldap
 * @author Alexander Pankow <info@alexander-pankow.de>
 */
class Config extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {
	const LDAP_TYPE_OPENLDAP = 0;
	const LDAP_TYPE_ACTIVEDIRECTORY = 1;

	/**
	 * @var bool
	 */
	protected $hidden;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 */
	protected $ldapType;

	/**
	 * @var int
	 */
	protected $ldapProtocol;

	/**
	 * @var string
	 */
	protected $ldapHost;

	/**
	 * @var int
	 */
	protected $ldapPort;

	/**
	 * @var bool
	 */
	protected $ldapUseTls;

	/**
	 * @var string
	 */
	protected $ldapBindDn;

	/**
	 * @var string
	 */
	protected $ldapPassword;

	/**
	 * @param boolean $hidden
	 */
	public function setHidden($hidden) {
		$this->hidden = $hidden;
	}

	/**
	 * @return boolean
	 */
	public function getHidden() {
		return $this->hidden;
	}

	/**
	 * @param string $ldapBindDn
	 */
	public function setLdapBindDn($ldapBindDn) {
		$this->ldapBindDn = $ldapBindDn;
	}

	/**
	 * @return string
	 */
	public function getLdapBindDn() {
		return $this->ldapBindDn;
	}

	/**
	 * @param string $ldapHost
	 */
	public function setLdapHost($ldapHost) {
		$this->ldapHost = $ldapHost;
	}

	/**
	 * @return string
	 */
	public function getLdapHost() {
		return $this->ldapHost;
	}

	/**
	 * @param string $ldapPassword
	 */
	public function setLdapPassword($ldapPassword) {
		$this->ldapPassword = $ldapPassword;
	}

	/**
	 * @return string
	 */
	public function getLdapPassword() {
		return $this->ldapPassword;
	}

	/**
	 * @param int $ldapPort
	 */
	public function setLdapPort($ldapPort) {
		$this->ldapPort = $ldapPort;
	}

	/**
	 * @return int
	 */
	public function getLdapPort() {
		return $this->ldapPort;
	}

	/**
	 * @param boolean $ldapUseTls
	 */
	public function setLdapUseTls($ldapUseTls) {
		$this->ldapUseTls = $ldapUseTls;
	}

	/**
	 * @return boolean
	 */
	public function getLdapUseTls() {
		return $this->ldapUseTls;
	}

	/**
	 * @param int $ldapProtocol
	 */
	public function setLdapProtocol($ldapProtocol) {
		$this->ldapProtocol = $ldapProtocol;
	}

	/**
	 * @return int
	 */
	public function getLdapProtocol() {
		return $this->ldapProtocol;
	}

	/**
	 * @param string $ldapServer
	 */
	public function setLdapType($ldapServer) {
		$this->ldapType = $ldapServer;
	}

	/**
	 * @return string
	 */
	public function getLdapType() {
		return $this->ldapType;
	}

	/**
	 * @param string $name
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}
}
