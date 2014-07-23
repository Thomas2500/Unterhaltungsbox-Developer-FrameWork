<?php

/**
 *
 * @copyright	2014 Unterhaltungsbox.com
 * @package	    com.unterhaltungsbox.udfw
 * @subpackage	system
 * @category 	UDFrameWork
 * @version     2.1
 * @author      Thomas2500
 *
 */

// Hack protection
if (!defined("FW_PATH"))
	exit(file_get_contents("../pages/hack.txt"));


namespace UDFrameWork\system;
use \PDO;
use \PDOException;

class SQL
{

	private $pdo;
	private $lastQuery;
	private $database;

	/**
	 *	Connects to the given database
	 *	@param 	string 	$host
	 *	@param 	string 	$username
	 *	@param 	string 	$password
	 *	@param 	array 	$settings
	 */
	public function __construct($host, $username, $password, $settings)
	{
		// If settings is empty, set a empty array
		if (empty($settings) || !isset($settings))
			$settings = array();

		// SqLite requires a special DSN string
		if (!empty($settings["driver"]) && (strtolower($settings["driver"]) === "sqlite" || strtolower($settings["driver"]) === "sqlite2"))
		{
			// If no file is given, use memory as a temporary table
			if (empty($settings["file"]) || strtolower($settings["file"]) == "memory")
				$settings["file"] = ":memory:";

			$dsn = strtolower($settings["driver"]) . ":" . $settings["file"];

			// Load file
			$this->pdo = new PDO($dsn);
		}
		else
		{
			// 4th position can be also the database name
			if (gettype($settings) == "string")
				$setting = array("database" => $settings);

			// If no host and no port is set, we use localhost as default host
			// localhost will be a local domain socket. If we want to use a different
			// port, we have to use the loopback ip
			// http://at.php.net/manual/de/ref.pdo-mysql.connection.php
			if (empty($host) && empty($settings["port"]))
				$host = "localhost";
			else if (empty($host))
				$host = "127.0.0.1";
			else
				$host = strtolower($host);

			// If no driver is set, we use MySQL
			if (!isset($settings["driver"]) || empty($settings["driver"]))
				$settings["driver"] = "mysql";

			// Create DSN - Data Source Name
			$dsn = $settings["driver"] . ":host=".$host;

			// Select database
			if (!empty($settings["database"]))
			{
				$dsn .= ";dbname=".$settings["database"];
				$this->database = $settings["database"];
			}

			// Change default port
			if (!empty($settings["port"]))
				$dsn .= ";port=".$settings["port"];

			// Ask for a UTF8 charset
			$dsn .= ";charset=UTF8";

			// Connect
			try
			{
				$this->pdo = new PDO($dsn, $username, $password);
			}
			catch (PDOException $Exception)
			{
				echo '<strong>[Code ' . $Exception->getCode() . ']</strong> ' . $Exception->getMessage();
				exit;
			}

			// Before PHP 5.3.6, charset in DSN was ignored.
			// If we run php before 5.3.6, set names must be set tu support chars like äöüß
			if (version_compare(PHP_VERSION, '5.3.6', '<'))
				$this->pdo->exec("SET NAMES utf8");
		}

		// Throw a exception if a error occurs
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}


	/**
	 *	Execute a query with an array as return by select or with affected rows by insert, update or delete.
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@param 	int 	$fetch_style
	 *	@return int
	 */
	public function query($query, $param = null, $fetch_style = PDO::FETCH_BOTH)
	{
		$query = trim($query);

		$this->ExecuteQuery($query, $param);

		$statement = strtolower(substr($query, 0 , 6));

		if ($statement === 'select') {
			return $this->lastQuery->fetchAll($fetch_style);
		}
		elseif ( $statement === 'insert' ||  $statement === 'update' || $statement === 'delete' ) {
			return $this->lastQuery->rowCount();
		}
		else {
			return NULL;
		}
	}


	/**
	 *	Execute a query and get a singe string as return
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@return string
	 */
	public function result($query, $param = null)
	{
		$query = trim($query);
		$this->ExecuteQuery($query, $param);
		return $this->lastQuery->fetchColumn();
	}

	// Alias of SQL::result
	public function single($query, $param = null)
	{
		return $this->result($query, $param);
	}


	/**
	 *	Execute a query and get as return true if the result is 1. Otherwise it returns 0
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@return bool
	 */
	public function bool($query, $param = null)
	{
		if (intval($this->result($query, $param)) >= 1)
			return true;
		return false;
	}

	/**
	 *	Execute a query and returns the first row of the response
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@param 	int 	$fetch_style
	 *	@return string
	 */
	public function row($query, $param = null, $fetch_style = PDO::FETCH_ASSOC)
	{
		$query = trim($query);
		$this->ExecuteQuery($query, $param);

		return $this->lastQuery->fetch($fetchmode);
	}

	/**
	 *	Execute a query and returns the first row with names
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@return array
	 */
	public function assoc($query, $param = null)
	{
		$query = trim($query);
		$this->ExecuteQuery($query, $param);

		return $this->lastQuery->fetch(PDO::FETCH_ASSOC);
	}

	/**
	 *	Execute a query and returns the first row
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 *	@param 	int 	$index
	 *	@return array
	 */
	public function column($query, $param = null, $index = 0)
	{
		$this->ExecuteQuery($query, $param);
		$Columns = $this->lastQuery->fetchAll(PDO::FETCH_NUM);

		$column = array();
		if (!isset($Columns[0]))
			return array();

		if($index >= count($Columns[0]))
			throw new Exception("SQL::column index pointer is too high");

		foreach($Columns as $cells) {
			$column[] = $cells[$index];
		}

		return $column;
	}

	/**
	 *  Returns the last inserted id.
	 *  @return string
	 */
	public function lastInsertId() {
		return $this->pdo->lastInsertId();
	}

	// Alias of SQL::lastInsertId()
	public function insert_id() {
		return $this->lastInsertId();
	}


	/**
	 *  Returns an escaped string with quotes
	 *  @param 	string 	$str
	 *  @return string
	 */
	public function escape($str)
	{
		return PDO::quote($str);
	}


	/**
	 *	Closes the connection to the database
	 */
	public function Close()
	{
		// By PDO connections, the PDO instance must
		// be set to null to close the connection
		// http://at.php.net/manual/en/pdo.connections.php
		$this->pdo = null;
	}

	/**
	 *	Starts a transaction
	 *	@return bool
	 */
	public function beginTransaction(){
		return $this->pdo->beginTransaction();
	}

	/**
	 *	Submits a transaction
	 *	@return bool
	 */
	public function endTransaction(){
		return $this->pdo->commit();
	}

	// Alias of SQL::endTransaction()
	public function commit()
	{
		return $this->endTransaction();
	}

	/**
	 *	Deletes a Transaction
	 *	@return bool
	 */
	public function cancelTransaction(){
		return $this->pdo->rollBack();
	}

	// Alias of SQL::cancelTransaction()
	public function rollBack()
	{
		return $this->cancelTransaction();
	}

	/**
	 *	Changes current database or returns the current selected database
	 *	@param 	string $change
	 * 	@return string
	 */
	public function database($change = null)
	{
		if (!empty($change))
			return $this->database;
		$this->pdo->exec("USE " . $change . ";");
		$this->database = $change;
	}

	/**
	 *	Executes a query with or without parameters
	 *	@param 	string 	$query
	 *	@param 	array 	$param
	 */
	private function ExecuteQuery($query, $param)
	{
		try
		{
			$this->lastQuery = $this->pdo->prepare($query);

			if (!empty($param))
				$this->lastQuery->execute($param);
			else
				$this->lastQuery->execute();
		}
		catch (PDOException $e)
		{
			var_dump($e);
			exit;
		}
	}
}
