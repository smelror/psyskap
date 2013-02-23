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
    $this->dbuser = 'psychaid_skap';  // db username
    $this->dbpwd = '_KSNsnmax92u_12';    // db password
    $this->dbname = 'psychaid_skap'; // db name

  }
  public function getCon() {
    return new PDO('mysql:host=localhost;dbname='.$this->dbname, $this->dbuser, $this->dbpwd, array(PDO::ATTR_PERSISTENT => true)); // Always persistent => utilizes same connection for improved performance.
  }

  /*
    Moderator transactions
  */
  public function addMod($usr, $pwd, $epost) {
    $db = $this->getCon();
    try {
      $salt = $this->createSalt();
      $hash = hash('sha256', $salt . $pwd);
      $q = $db->prepare("INSERT INTO users (username, password, salt, epost) VALUES (:username, :password, :salt, :epost)");
      $q->execute(array(
          'username' => $usr,
          'password' => $hash,
          'salt' => $salt,
          'epost' => $epost
        ));
      return true;
    } catch (PDOException $e) {
      $this->logit('db.addMod failed: '. $e->getMessage() .'');
      return false;
    }
  }
  public function getAllMods() {
    $c = $this->getCon();
    $q = $c->prepare("SELECT username, epost, created FROM users");
    $q->execute();
    $allmods = $q->fetchAll();
    return $allmods;
  }

  /*
    Skap transactions
  */
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
    if(!empty($d)) return $d;
    else return null;
  }

  /*
    User transactions
  */
  public function getUser($usr) {
    $c = $this->getCon();
    $q = $c->prepare("SELECT * FROM users WHERE username = :name");
    $q->execute(array(':name' => $usr));
    return $q->fetch();
  }
  public function editUser($id, $new_epost, $new_pwd) {
    $c = $this->getCon();
    $salt = $this->createSalt();
    $hash = hash('sha256', $salt . $new_pwd);
    try {
      if(($new_epost != '') && ($new_pwd != '')) {
        $q = $c->prepare("UPDATE users SET epost=?, password=?, salt=? WHERE id = ?");
        $q->execute(array($new_epost, $hash, $salt, $id));
      }
      if($new_epost == '') {
        $q = $c->prepare("UPDATE users SET password=?, salt=? WHERE id = ?");
        $q->execute(array($hash, $salt, $id));
      }
      if($new_pwd == '') {
        $q = $c->prepare("UPDATE users SET epost=? WHERE id = ?");
        $q->execute(array($new_epost, $id));
      }
      return true;
    } catch (PDOException $e) {
      $this->logit('db.editUser failed: '.$e->getMessage() .'');
      return false;
    }
  }

  /*
    Suggestions transactions
  */
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
  public function getErrorlog() {
    $c = $this->getCon();
    $q = $c->prepare("SELECT * FROM errorlog ORDER BY DESC");
    $q->execute();
    $elog = $q->fetchAll();
    return $elog;
  }
  public function createSalt() {
      $string = md5(uniqid(rand(), true));
      return substr($string, 0, 3);
  }
  } // PsyDB
?>