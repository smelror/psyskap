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
  protected $status; // 0 = ubetalt, 1 = betalt, 2 = klippes
  
  function __construct($d) {
    $this->nr = $d['skapnr'];
    $this->rom = $d['rom'];
    $this->bygg = $d['bygg'];
    $this->merknad = $d['merknad'];
    $this->status = $d['status'];
    if($d['eier']) $this->eier = $d['eier'];
    else $this->eier = '';
  }

  /*
    Getters
  */
  public function getNr() { return $this->nr; }
  public function getRom() { return $this->rom; }
  public function getBygg() { return $this->bygg; }
  public function getEier() { return $this->eier; }
  public function getStatus() { return $this->status; } // rue if $status = 1
  public function getMerknad() { return $this->merknad; }
  public function isAvailable() { return ($this->eier == ''); }
  
  /*
    Setters
  */
  public function setOwner($owner, $db) {
    try {
      $c = $db->getCon(); // $db inn er hele objektet, ikke tilkoblingen
      // $sql = "UPDATE skap SET eier = :eierid WHERE nr = :skapnr";
      $q = $c->prepare("UPDATE skap SET eier = :owner WHERE skapnr = :nr");
      $q->execute(array(
        ":owner" => $owner,
        ":nr" => $this->getNr()
        ));
      return true;
    } catch (PDOException $e) {
      $db->logit("Skap.setOwner.error: " .$e->getMessage() ."<br />");
    }
    return false;
  }

  public function setStatus($number, $db) {
    try {
      $c = $db->getCon();
      $q = $c->prepare("UPDATE skap SET status = :am WHERE skapnr = :nr");
      $q->execute(array(
        ":am" => $number,
        ":nr" => $this->getNr()
        ));
      return true;
    } catch (PDOException $e) {
      $db->logit("Skap.setPaid.error: ".$e->getMessage()."<br />");
    }
    return false;
  }

} // Skap
?>