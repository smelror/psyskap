<?php
  /*
   Page: class.db.php
   Desc: Database-communication object. Utilizes PHP's PDO layer.
   */
class PsyDB {
  protected $dbname;
  protected $dbuser;
  protected $dbpwd;

  // Sets variables for connection
  function __construct() {
    $this->dbname = "";
    $this->dbuser = "";
    $this->dbpwd = "";
  }

  public function getCon() {
    return // TO-BE-ADDED
  }


  } // PsyDB
?>