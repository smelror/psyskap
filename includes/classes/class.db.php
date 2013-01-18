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
    $this->dbname = "psyskap2";
    $this->dbuser = "";
    $this->dbpwd = "";
  }

  public function getCon() {
    return new PDO('mysql:host=localhost;dbname='.$this->dbname, $this->user, $this->pass);
  }

  public function logit($message) {
    if(!$message) { die(); }
    $db = $this->getCon();
    try {
        $ps = $db->prepare("INSERT INTO errorlog (message) VALUES (?)");
        $ps->execute(array($message));
    } catch (PDOException $e) {
        print '<div class="error">Logit error: ' . $e->getMessage() . '<div/>';
        die();
    }
  }

  } // PsyDB
?>