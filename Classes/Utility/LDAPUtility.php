<?php
namespace AP\ApLdap\Utility;

use AP\ApLdap\Exception\ConnectionException;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Core\Utility\GeneralUtility,
	AP\ApLdap\Domain\Model\Config,
	AP\ApLdap\Exception\ConfigurationException;

/**
 * LDAP utility
 *
 * @package TYPO3
 * @subpackage tx_apldap
 * @author Alexander Pankow <info@alexander-pankow.de>
 */
class LDAPUtility {

	/**
	 * @var Config
	 */
	protected $config = null;

	/**
	 * LDAP connection resource
	 *
	 * @var resource|null
	 */
	protected $connection = null;

	/**
	 * LDAP bind resource
	 *
	 * @var resource|null
	 */
	protected $bind = null;

	/**
	 * @var \AP\ApLdap\Domain\Repository\ConfigRepository|null
	 */
	protected $configRepository = null;


	/**
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager = null;

	/**
	 * Last stored resources from ldap_search()
	 *
	 * @var null|resource
	 */
	protected $lastSearch = null;

	/**
	 * Last stored resources from ldap_first_entry(), ldap_next_entry()
	 *
	 * @var null|resource
	 */
	protected $lastEntry = null;

	/**
	 * @param \TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager
	 */
	public function __construct(\TYPO3\CMS\Extbase\Object\ObjectManagerInterface $objectManager = null) {
		if ($objectManager === null)
			$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\\CMS\\Extbase\\Object\\ObjectManager');
		else
			$this->objectManager = $objectManager;
	}

	/**
	 * Connects to a specified server
	 *
	 * @param int $configId
	 * @return bool
	 * @throws \AP\ApLdap\Exception\ConnectionException
	 */
	public function connect($configId = 0) {
		$this->initConfig($configId);

		if (!$this->connection = @ldap_connect($this->getConfig()->getLdapHost(), $this->getConfig()->getLdapPort()))
			throw new ConnectionException($this->connection, 'Can\'t connect to LDAP server ' . $this->getConfig()->getLdapHost());

		@ldap_set_option($this->connection, LDAP_OPT_PROTOCOL_VERSION, $this->getConfig()->getLdapProtocol());

		// Active Directory (User@Domain) configuration.
		if ($this->getConfig()->getLdapType() == Config::LDAP_TYPE_ACTIVEDIRECTORY)
			@ldap_set_option($this->connection, LDAP_OPT_REFERRALS, 0);

		if ($this->getConfig()->getLdapUseTls()) {
			if (!@ldap_start_tls($this->connection))
				throw new ConnectionException($this->connection, 'Start TLS failed');
		}

		return true;
	}


	/**
	 * @param int $configId
	 * @throws \AP\ApLdap\Exception\ConfigurationException
	 */
	protected function initConfig($configId = 0) {
		// check if `ldap` php extension is installed loaded
		if (!extension_loaded('ldap'))
			throw new ConfigurationException('The LDAP php extension is not installed or not loaded.');

		$extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ap_ldap']);
		if (empty($configId))
			$configId = $extConf['defaultConfigurationUid'];

		$config = $this->getConfigRepository()->findByUid($configId);
		if (!$config)
			throw new ConfigurationException('LDAP configuration with id `' . $configId . '` not found.');

		$this->config = $config;
	}

	/**
	 *
	 */
	public function disconnect() {
		$this->checkIfConnected();
		@ldap_close($this->connection);
	}

	/**
	 * @param string $bindDn
	 * @param string $bindPw
	 * @return bool
	 * @throws \AP\ApLdap\Exception\ConnectionException
	 */
	public function bind($bindDn = '', $bindPw = '') {
		$this->checkIfConnected();

		if (empty($bindDn))
			$bindDn = $this->getConfig()->getLdapBindDn();
		if (empty($bindPw))
			$bindPw = $this->getConfig()->getLdapPassword();

		if (!$this->bind = @ldap_bind($this->connection, $bindDn, $bindPw))
			throw new ConnectionException($this->connection, 'Bind failed');

		return true;
	}

	/**
	 * @param $baseDn
	 * @param $filter
	 * @param array $attributes
	 * @param bool $attributesOnly
	 * @param int $sizeLimit
	 * @param int $timeLimit
	 * @param int $deRef
	 * @return LDAPUtility
	 * @throws \AP\ApLdap\Exception\ConnectionException
	 */
	public function search($baseDn, $filter, $attributes = array(), $attributesOnly = false, $sizeLimit = 0, $timeLimit = 0, $deRef = LDAP_DEREF_NEVER) {
		$this->checkIfConnected();

		if (!$search = @ldap_search($this->connection, $baseDn, $filter, $attributes, $attributesOnly, $sizeLimit, $timeLimit, $deRef))
			throw new ConnectionException($this->connection, 'Search failed');

		$this->lastSearch = $search;

		return $this;
	}

	/**
	 * @param null|resource $search
	 * @return array|null
	 */
	public function getEntries($search = null) {
		if ($this->lastSearch === null && $search === null)
			return null;

		if ($search === null)
			$search = $this->lastSearch;

		return ldap_get_entries($this->connection, $search);
	}

	/**
	 * @param null|resource $search
	 * @return LDAPUtility
	 */
	public function getFirstEntry($search = null) {
		if ($this->lastSearch === null && $search === null)
			return null;

		if ($search === null)
			$search = $this->lastSearch;

		$this->lastEntry = ldap_first_entry($this->connection, $search);

		if ($this->lastEntry === false)
			return false;

		return $this;
	}

	/**
	 * @param null|resource $lastEntry
	 * @return LDAPUtility|boolean false if no more entries in the result
	 */
	public function getNextEntry($lastEntry = null) {
		if ($this->lastEntry === null && $lastEntry === null)
			return $this->getFirstEntry();

		if ($lastEntry === null)
			$lastEntry = $this->lastEntry;

		$this->lastEntry = ldap_next_entry($this->connection, $lastEntry);

		if ($this->lastEntry === false)
			return false;

		return $this;
	}

	/**
	 * @param null|resource $entry
	 * @return null|string
	 */
	public function getDN($entry = null) {
		if ($this->lastEntry === null && $entry === null)
			return null;

		if ($entry === null)
			$entry = $this->lastEntry;

		if ($entry === false)
			return false;

		return ldap_get_dn($this->connection, $entry);
	}

	/**
	 * @param null|resource $search
	 * @return int|null
	 */
	public function countEntries($search = null) {
		if ($this->lastSearch === null && $search === null)
			return null;

		if ($search === null)
			$search = $this->lastSearch;

		return ldap_count_entries($this->connection, $search);
	}

	/**
	 * @param null|resource $entry
	 * @param string $attribute
	 * @return array|null|bool
	 */
	public function getValues($attribute = '', $entry = null) {
		if (($this->lastEntry === null && $entry === null) || empty($attribute))
			return null;

		if ($entry === null)
			$entry = $this->lastEntry;

		if ($entry === false)
			return false;

		return ldap_get_values($this->connection, $entry, $attribute);
	}

	/**
	 * @param null|resource $entry
	 * @param string $attribute
	 * @return array|null|bool
	 */
	public function getBinaryValues($attribute = '', $entry = null) {
		if (($this->lastEntry === null && $entry === null) || empty($attribute))
			return null;

		if ($entry === null)
			$entry = $this->lastEntry;

		if ($entry === false)
			return false;

		return ldap_get_values_len($this->connection, $entry, $attribute);
	}

	/**
	 * @param null $entry
	 * @return array|null|bool
	 */
	public function getAttributes($entry = null) {
		if ($this->lastEntry === null && $entry === null)
			return null;

		if ($entry === null)
			$entry = $this->lastEntry;

		if ($entry === false)
			return false;

		$attributes = array();
		$ldapAttributes = ldap_get_attributes($this->connection, $entry);
		foreach ($ldapAttributes as $attribute => $value) {
			if (is_numeric($attribute))
				$attributes[] = $value;
		}

		return $attributes;
	}

	/**
	 * @return null|resource
	 */
	public function getLastSearch() {
		return $this->lastSearch;
	}

	/**
	 * @return null|resource
	 */
	public function getLastEntry() {
		return $this->lastEntry;
	}

	/**
	 * @return bool
	 * @throws \AP\ApLdap\Exception\ConnectionException
	 */
	protected function checkIfConnected() {
		if (!is_resource($this->connection))
			throw new ConnectionException($this->connection, 'Not connected');

		return true;
	}

	/**
	 * @return null|resource
	 */
	public function getConnectionResource() {
		return $this->connection;
	}

	/**
	 * @return null|resource
	 */
	public function getBindResource() {
		return $this->bind;
	}

	/**
	 * @return int
	 */
	public function getConfigUid() {
		return $this->getConfig()->getUid();
	}

	/**
	 * @return Config|\AP\ApLdapAuth\Domain\Model\Config
	 */
	public function getConfig() {
		return $this->config;
	}

	/**
	 * @param string $className
	 */
	public function setConfigRepository($className = 'AP\\ApLdap\\Domain\\Repository\\ConfigRepository') {
		$this->configRepository = $this->objectManager->get($className);
	}

	/**
	 * @return \Ap\ApLdap\Domain\Repository\ConfigRepository|null
	 */
	public function getConfigRepository() {
		if ($this->configRepository === null)
			$this->setConfigRepository();
		return $this->configRepository;
	}
}
