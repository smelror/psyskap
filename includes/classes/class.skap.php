<?php
  /*
   Page: class.skap.php
   Desc: Skap (locker) object. Contains every function related to lockers.
   */
class Skap {
  protected $nr;
  protected $rom;
  protected $bygg;
  protected $eier;
  protected $merknad;
  protected $betalt;
  
  function __construct($d) {
    $this->nr = $d['skapnr'];
    $this->rom = $d['rom'];
    $this->bygg = $d['bygg'];
    $this->merknad = $d['merknad'];
    if($d['eier']) $this->eier = $d['eier'];
    else $this->eier = '';
  }
  public function getNr() { return $this->nr; }
  public function getRom() { return $this->rom; }
  public function getBygg() { return $this->bygg; }
  public function getEier() { return $this->eier; }
  public function isBetalt() { return $this->betalt == 1; } // true if $betalt = 1
  public function getMerknad() { return $this->merknad; }
  public function isAvailable() { return ($this->eier == ''); }
  
  public function set($variable, $value, $db) {
    $allowed = array("skapnr", "rom", "bygg", "eier", "merknad", "betalt");
    if(!in_array($variable, $allowed)) { die("Nonexistent variable declared."); }
    if(!$value) { die("No value given."); }
    try {
      $c = $db->getCon(); // $db inn er hele objektet, ikke tilkoblingen
      // $sql = "UPDATE skap SET eier = :eierid WHERE nr = :skapnr";
      $q = $c->prepare("UPDATE skap SET ? = ? WHERE skapnr = ?");
      $q->bindParam(1, $variable);
      $q->bindParam(2, $value);
      $q->bindParam(3, $this->getNr());
      $q->execute();
      return true;
    } catch (PDOException $e) {
      $db->logit("Skap.set.error: " .$e->getMessage() ."<br />");
    }
    return false;
  }
} // Skap
?>