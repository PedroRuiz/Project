<?php declare(strict_types=1);

namespace Project\classes;

 /**
 * @author Pedro Ruiz Hidalgo <ruizhidalgopedro@gmail.com>
 * @pedroruizhidalg
 *
 * coding the world since 1983
 *
 * date 2019-09-19
 *
 * @package Project
 *
 * Name: classes/db.class.php
 *
 * Purpose: PDO Database class
 *
 *
 *
 */



class Database 
{
  private $Host;
  private $User;
  private $Pass;
  private $Database;
  
  function __construct()
  {    
    $ini_file = __DIR__ . '/database.ini'; 
    $ini_content = parse_ini_file( $ini_file , true); 
    $this->Host = $ini_content['database']['Host'];
    $this->User = $ini_content['database']['User'];
    $this->Pass = $ini_content['database']['Pass'];
    $this->Database = $ini_content['database']['Database'];    
  }

  private function config()
  {
    return  [ 
      \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
      \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
      \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
      \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ
    ];
  }  
  
  /**
   * @param none
   * @return \PDO
   */
  private function connection(): \PDO
  {
    $dsn = "mysql:host={$this->Host};dbname={$this->Database}";
    $connection = new \PDO($dsn, $this->User, $this->Pass, $this->config() );
    $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    return $connection;
  }

  /**
   * do_query when you want to bring back data in other case do_sentence
   * @param iterable $query data
   * @param mode pdo get mode by default FETCH_ASSOC
   * @return iterable
   */
  function do_query(iterable $query_data , $mode = \PDO::FETCH_ASSOC ): iterable 
  {
    try
    {

      $db = $this->connection();
      $result = $db->prepare( $query_data['query'] );
      


      if( $result->execute( $query_data['params'] ) )
      {
        http_response_code(200);
        return [          
          'result' => $result->fetchAll( $mode ) ?? NULL,
          'row_count' => $result->rowCount() ?? NULL,
          'last_insert_id' => $db->lastInsertId() ?? NULL,
          'status' => http_response_code() ?? NULL
        ];
      }

    }  catch( \PDOExeption $e) {
    
      return '{"error" : {"text" : ' . $e.getMessage() . '}';
  
    }

  }

  /**
   * do_sentence when you DON'T want to bring back data in other case do_query
   * @param iterable $query data and params
   * @param mode pdo get mode by default FETCH_ASSOC 
   * @return iterable
   */
  function do_sentence(iterable $query_data , $mode = \PDO::FETCH_ASSOC ): iterable 
  {
    try
    {

      $db = $this->connection();
      $result = $db->prepare( $query_data['query'] );
      


      if( $result->execute( $query_data['params'] ) )
      {
        http_response_code(200);
        return [          
          'row_count' => $result->rowCount() ?? NULL,
          'last_insert_id' => $db->lastInsertId() ?? NULL,
          'status' => http_response_code() ?? NULL
        ];
      }

    }  catch( \PDOExeption $e) {
    
      return '{"error" : {"text" : ' . $e.getMessage() . '}';
  
    }

  }
}