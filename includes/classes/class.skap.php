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
		$this->nr = $d['nr'];
		$this->rom = $d['rom'];
		$this->bygg = $d['bygg'];
		$this->pris = $d['pris'];
		if($d['eier']) $this->eier = $d['eier'];
		else $this->eier = '';
	}
	public function getNr() { return $this->nr; }
	public function getRom() { return $this->romnr; }
	public function getBygg() { return $this->bygg; }
	public function getPris() { return $this->pris; }
	public function getEier() { return $this->eier; }
	public function isAvailable() { return ($this->eier == ''); }
	public function setOwner($userid, $db) {
		try {
			$sql = "UPDATE skap SET eier = :eierid WHERE nr = :skapnr";
			$q = $db->prepare($sql);
			$q->execute(array(
				':eierid' => $userid,
				':skapnr' => $this->getNr()
				));
			return true;
		} catch (PDOException $e) {
			print "User.setUserSkap.error: " .$e->getMessage() ."<br />";
		}
		return false;
	}
}
?>