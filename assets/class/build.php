<?php

class Build
{

	protected $database;

  public function __construct(Database $database)
  {
    // brings in Database method into the Passage class
		$this->database = $database;
    // builds the MySQL tables in the datebase
    $this->buildTable();
  }

  public function buildTable()
  {
		return $this->database->query("
			CREATE TABLE IF NOT EXISTS word_pantri
			(id INT UNSIGNED NOT NULL PRIMARY KEY AUTO_INCREMENT,
			welsh_word VARCHAR(250) NOT NULL,
      english_word VARCHAR(250) NOT NULL)
		");
	}

} // end of the class
?>
