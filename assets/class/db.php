<?php

class Database
{
  protected $host;
	protected $db;
	protected $username;
	protected $password;

  public $pdo;
	protected $data;
	protected $stmt;
	protected $field;
	public $debug = true;


  public function __construct($host, $db, $username, $password)
	{
		$this->host = $host;
		$this->db = $db;
		$this->username = $username;
		$this->password = $password;

		try
		{
			$this->pdo = new PDO("mysql:host={$this->host}; dbname={$this->db}", $this->username, $this->password, array(PDO::MYSQL_ATTR_LOCAL_INFILE=>1));
			if($this->debug)
			{
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //echo "we are connected";
			}
		}
		catch(PDOException $e)
		{
			die($this->debug ? $e->getMessage(): '');
		}
	}

  public function query($sql)
	{
		return $this->pdo->query($sql);
	}

  public function insert($data, $table)
	{
		$keys = array_keys($data);
		$fields = '`'.implode('`, `', $keys) . '`';
		$placeholders = ':' . implode(', :', $keys);
		$sql = "INSERT INTO {$table} ({$fields}) VALUES ({$placeholders})";
		$this->stmt = $this-> pdo->prepare($sql);
		return $this->stmt->execute($data);
	}

  public function get()
  {
    return $this->stmt->fetchAll(PDO::FETCH_OBJ);
  }

  public function getAll()
	{
    $aSmt = $this->pdo->prepare("SELECT id, welsh_word, english_word FROM word_pantri");
    $aSmt->execute();
  //  return $aSmt->fetchAll();
    return $this->arrangeArray($aSmt);
	}

  public function arrangeArray($array)
  {
    $newArray = array();
    foreach ($array as $child) {
      $tmp = $child[id];
      $newArray[$tmp]['welsh_word'] = $child[welsh_word];
      $newArray[$tmp]['english_word'] = $child[english_word];
    }

    return $newArray;
  }


} // end of the class
?>
