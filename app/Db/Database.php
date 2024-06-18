<?php
namespace App\Db;
use \PDO;
use \PDOException;

class Database    
{
  private static $instance = null;
  private $connection;
  private $table;

  public function __construct($table = null) {
    $this->table = $table;
    $this->setConnection();
  } 

  private function setConnection() {


      /* Get access to all database parameters */
      $jsonFilePath = __DIR__ . '/db-credentials.json';

      if (!file_exists($jsonFilePath))  {
           die( __DIR__ . "\db-credentials.json file not found. Please, create it." );
      }

      $jsonContent = file_get_contents($jsonFilePath);
      $dbParams = json_decode($jsonContent, true);
      if ($dbParams === null) {
          // Handle error if the JSON is invalid
          die('Error decoding JSON file.');
      }

      $host = getenv('DB_HOST') ?: $dbParams['host'];
      $dbname = getenv('DB_NAME') ?: $dbParams['dbname'];
      $user = getenv('DB_USER') ?: $dbParams['user'];
      $pass = getenv('DB_PASS') ?: $dbParams['pass'];

      try {
        $this->connection = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      } catch (PDOException $e) {
        die('Database connection error: ' . $e->getMessage());
      }
  }

  public function execute( $query, $params = [] )     {

      try {
          $statement = $this->connection->prepare($query);
          $statement->execute( $params );

          return $statement;
      } catch (PDOException $e)   {
          // echo "Akiki epa epa 222 " . $e->getCode() . " -- " . $e->getMessage();
          die('SQL error: ' . $e->getMessage() );
      }
  }

  public function insert( $values )                 {
      $fields = array_keys( $values );
      $binds  = array_fill(0, count($fields), '?');
      $query  = 'INSERT INTO ' . $this->table . ' (' . implode(',', $fields) . ') VALUES (' . implode(',', $binds) . ')';
      
      // echo "QUERY?!?! " . $query;

      $this->execute($query, array_values($values));
      return $this->connection->lastInsertId();
  } 

  public function select($where = '', $order = '', $limit = '', $fields = '*') {
    $where = $where ? 'WHERE ' . $where : '';
    $order = $order ? 'ORDER BY ' . $order : '';
    $limit = $limit ? 'LIMIT ' . $limit : '';
    $query = 'SELECT ' . $fields . ' FROM ' . $this->table . ' ' . $where . ' ' . $order . ' ' . $limit;

    // echo "<br>**QUERY?!? " . $query;
    return $this->execute($query);
  }

  public function selectJOIN($where = '', $order = '', $limit = '', $fields = '*', $joins = '') {
    $where = $where ? 'WHERE ' . $where : '';
    $order = $order ? 'ORDER BY ' . $order : '';
    $limit = $limit ? 'LIMIT ' . $limit : '';
    $query = 'SELECT ' . $fields . ' FROM ' . $this->table;
    if ($joins) {
      $query .= ' ' . $joins;
    }
    $query .= ' ' . $where . ' ' . $order . ' ' . $limit;
    return $this->execute($query);
  }

  public function update($where, $values) {
    $fields = array_keys($values);
    $query = 'UPDATE ' . $this->table . ' SET ' . implode('=?,', $fields) . '=? WHERE ' . $where;
    $this->execute($query, array_values($values));
    return true;
  }

  public function delete($where) {
    $query = 'DELETE FROM ' . $this->table . ' WHERE ' . $where;
    $this->execute($query);
    return true;
  }
}
