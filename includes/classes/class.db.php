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
    /*
    // Local server
    $this->dbname = "psyskap2";
    $this->dbuser = "root";
    $this->dbpwd = "";
    */
    // Live test-area!
    $this->dbuser = 'psychaid_skap';  // db username
    $this->dbpwd = '_KSNsnmax92u_12';    // db password
    $this->dbname = 'psychaid_skap'; // db name

  }

  public function getCon() {
    return new PDO('mysql:host=localhost;dbname='.$this->dbname, $this->dbuser, $this->dbpwd, array(PDO::ATTR_PERSISTENT => true)); // Always persistent => utilizes same connection for improved performance.
  }

  public function addMod($usr, $pwd, $epost) {
    $db = $this->getCon();
    try {
      // salt shake
      $salt = $this->createSalt();
      $hash = hash('sha256', $salt . $pwd);
      $q = $db->prepare("INSERT INTO users (username, password, salt, epost) VALUES (:username, :password, :salt, :epost)");
      $q->execute(array(
          'username' => $usr,
          'password' => $hash,
          'salt' => $salt,
          'epost' => $epost
        ));
    } catch (PDOException $e) {
      $this->logit('db.addMod failed: '. $e->getMessage() .'');
    }
  }

  public function getAllSkap() {
      $allSkap = array();
      $c = $this->getCon();
      $q = $c->prepare("SELECT * FROM skap ORDER BY skapnr ASC");
      $q->execute();
      $allSkap = $q->fetchAll();
      return $allSkap;
  }

  public function getSkap($skapnr) {
    $c = $this->getCon();
    $q = $c->prepare("SELECT * FROM skap WHERE skapnr = :nr");
    $q->execute(array(':nr' => $skapnr));
    $d = $q->fetch();
    if($d) return $d;
    else return null;
  }

  public function addSuggestion($eier, $skap) {
    $c = $this->getCon();
    try {
      $q = $c->prepare("INSERT INTO forslag (navn, skap) VALUES (?, ?)");
      $q->execute(array($eier, $skap));
      return true;
    } catch (PDOException $e) {
      $this->logit('db.addSuggestion failed: '.$e->getMessage() .'');
      return false;
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