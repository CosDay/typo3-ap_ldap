<?php
namespace AP\ApLdap\Exception;

/**
 * LDAP exception. Base exception for this extension. It is abstract, so the other exceptions have to extend this class.
 *
 * @package TYPO3
 * @subpackage tx_apldap
 * @author Alexander Pankow <info@alexander-pankow.de>
 */
abstract class LDAPException extends \Exception {}
