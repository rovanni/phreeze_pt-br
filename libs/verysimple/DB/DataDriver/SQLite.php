<?php 
/** @package verysimple::DB::DataDriver */

require_once("IDataDriver.php");
require_once("verysimple/DB/ISqlFunction.php");
require_once("verysimple/DB/DatabaseException.php");
require_once("verysimple/DB/DatabaseConfig.php");

/**
 * An implementation of IDataDriver that communicates with
 * a SQLite database file.  This is one of the native drivers
 * supported by Phreeze
 *
 * @package    verysimple::DB::DataDriver
 * @author     VerySimple Inc. <noreply@verysimple.com>
 * @copyright  1997-2010 VerySimple Inc.
 * @license    http://www.gnu.org/licenses/lgpl.html  LGPL
 * @version    1.0
 */
class DataDriverSQLite implements IDataDriver
{	
	/**
	 * @inheritdocs
	 */
	function GetServerType()
	{
		return "SQLite";
	}
	
	function Ping($connection)
	{
		 throw new DatabaseException("Not Implemented");
	}
	
	/**
	 * @inheritdocs
	 */
	function Open($connectionstring,$database,$username,$password,$charset='',$bootstrap='') 
	{
		if (!class_exists("SQLite3")) throw new DatabaseException('A extensão SQLite3 não está ativada neste servidor.',DatabaseException::$CONNECTION_ERROR);
		
		if ( !$connection =  new SQLite3($connectionstring, SQLITE3_OPEN_READWRITE,$password) )
		{
			throw new DatabaseException("Erro ao conectar-se ao banco de dados: Não é possível abrir o arquivo de banco de dados.",DatabaseException::$CONNECTION_ERROR);
		}
		
		// charset is ignored with sqlite

		if ($bootstrap)
		{
			$statements = explode(';',$bootstrap);
			foreach ($statements as $sql)
			{
				try
				{
					$this->Execute($connection, $sql);
				}
				catch (Exception $ex)
				{
					throw new DatabaseException("problema com bootstrap sql: " . $ex->getMessage(),DatabaseException::$ERROR_IN_QUERY);
				}
			}
		}
		
		return $connection;
	}
	
	/**
	 * @inheritdocs
	 */
	function Close($connection) 
	{
		@$connection->close(); // ignore warnings
	}
	
	/**
	 * @inheritdocs
	 */
	function Query($connection,$sql) 
	{

		if ( !$rs = $connection->query($sql) )
		{
			throw new DatabaseException($connection->lastErrorMsg(),DatabaseException::$ERROR_IN_QUERY);
		}
		
		return $rs;
	}

	/**
	 * @inheritdocs
	 */
	function Execute($connection,$sql) 
	{
		return $connection->exec($sql);
	}
	
	/**
	 * @inheritdocs
	 */
	function Fetch($connection,$rs) 
	{
		return $rs->fetchArray(SQLITE3_ASSOC);
	}

	/**
	 * @inheritdocs
	 */
	function GetLastInsertId($connection) 
	{
		return $connection->lastInsertRowID();
	}

	/**
	 * @inheritdocs
	 */
	function GetLastError($connection)
	{
		return $connection->lastErrorMsg();
	}
	
	/**
	 * @inheritdocs
	 */
	function Release($connection,$rs) 
	{
		$rs->finalize();
	}
	
	/**
	 * @inheritdocs
	 * @TODO: use SQLite
	 */
	function Escape($val) 
	{
		return str_replace("'","''",$val);
 	}
	
 	/**
 	 * @inheritdocs
 	 */
 	public function GetQuotedSql($val)
 	{
 		if ($val === null) return DatabaseConfig::$CONVERT_NULL_TO_EMPTYSTRING ? "''" : 'NULL';
 	
 		if ($val instanceof ISqlFunction) return $val->GetQuotedSql($this);
 	
 		return "'" . $this->Escape($val) . "'";
 	}
 	
	/**
	 * @inheritdocs
	 */
 	function GetTableNames($connection, $dbname, $ommitEmptyTables = false) 
	{
		if ($ommitEmptyTables) throw new DatabaseException("O SQLite DataDriver não oferece suporte ao retornar somente tabelas não vazias. Defina ommitEmptyTables arg como false para usar esse método.");
		
		$rs = $this->Query($connection,"SELECT name FROM sqlite_master WHERE type='table' and name != 'sqlite_sequence' ORDER BY name");

		$tables = array();
		
		while ( $row = $this->Fetch($connection,$rs) )
		{
			$tables[] = $row['name'];
		}
		
		return $tables;
		
 	}
	
	/**
	 * @inheritdocs
	 */
 	function Optimize($connection,$table) 
	{
		if ($table) throw new DatabaseException("A otimização do SQLite é ampla de banco de dados. Chamar Optimize() com um arg de tabela em branco/nulo para usar esse método.");
		$this->Execute($connection,"VACUUM");
	}
	
	/**
	 * @inheritdocs
	 */
	function StartTransaction($connection)
	{
		throw new Exception('O suporte a transações não é implementado para este DataDriver');
	}
	
	/**
	 * @inheritdocs
	 */
	function CommitTransaction($connection)
	{
		throw new Exception('O suporte a transações não é implementado para este DataDriver');
	}
	
	/**
	 * @inheritdocs
	 */
	function RollbackTransaction($connection)
	{
		throw new Exception('O suporte a transações não é implementado para este DataDriver');
	}
}

?>