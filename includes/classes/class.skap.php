<?php
  /*
   Page: class.skap.php
   Desc: Skap (locker) object. Contains every function related to lockers.
   */
class Skap {
  protected $nr;
  protected $rom;
  protected $bygg;
  protected $pris;
  protected $eier;
  
  function __construct($d) {
    $this->nr = $d['skapnr'];
    $this->rom = $d['rom'];
    $this->bygg = $d['bygg'];
    $this->pris = $d['pris'];
    if($d['eier']) $this->eier = $d['eier'];
    else $this->eier = '';
  }
  public function getNr() { return $this->nr; }
  public function getRom() { return $this->rom; }
  public function getBygg() { return $this->bygg; }
  public function getPris() { return $this->pris; }
  public function getEier() { return $this->eier; }
  public function isAvailable() { return ($this->eier == ''); }
  public function set($variable, $value, $db) {
    if($variable != "skapnr" || "rom" || "bygg" || "pris" || "eier") { die("Nonexistent variable declared."); }
    if(!$value) { die("No value given."); }
    try {
      $c = $db->getCon(); // $db inn er hele objektet, ikke tilkoblingen
      // $sql = "UPDATE skap SET eier = :eierid WHERE nr = :skapnr";
      $q = $c->prepare("UPDATE skap SET ? = ? WHERE skapnr = :skapnr");
      $q->execute(array($variable, $value,':skapnr' => $this->getNr()));
      return true;
    } catch (PDOException $e) {
      $db->logit("User.setUserSkap.error: " .$e->getMessage() ."<br />");
    }
    return false;
  }
  } // Skap
?>