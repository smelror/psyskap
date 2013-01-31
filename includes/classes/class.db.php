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
    $this->dbuser = "root";
    $this->dbpwd = "";
  }

  public function getCon() {
    return new PDO('mysql:host=localhost;dbname='.$this->dbname, $this->dbuser, $this->dbpwd);
  }


  public function addMod($usr, $pwd, $epost) {
    $db = $this->getCon();
    try {
      // salt shake
      $salt = $this->createSalt();
      $hash = hash('sha256', $salt . $password);
      $q = $db->prepare("INSERT INTO users (username, password, salt, epost) VALUES (:username, :password, :salt, :epost)");
      $q->execute(array(
          'username' => $usr,
          'password' => $pwd,
          'salt' => $salt,
          'epost' => $epost
        ));

    } catch (PDOException $e) {
      $this->logit('db.addMod failed: '. $e-getMessage() .'');
    }
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
  public function createSalt() {
      $string = md5(uniqid(rand(), true));
      return substr($string, 0, 3);
  }
  } // PsyDB
?>