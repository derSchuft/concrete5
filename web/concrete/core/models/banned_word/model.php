<?php defined('C5_EXECUTE') or die("Access Denied.");

class Concrete5_Model_BannedWord extends Object {

	protected $id;
	protected $word;

	public function getWord() { return $this->word; }
	public function getID() { return $this->id; }

	public function setWord($word) {
		$db = Loader::db();
		if ($word == false) return $this->delete();
		$db->execute('UPDATE BannedWords SET bannedWord=? WHERE bwID=?',array($word,$this->id));
		$this->word = $word;
	}

	public function __construct($id=false,$word=false) {
		$this->init($id,$word);
	}

	public function init($id,$word) {
		if ($this->id && $this->word) {
			throw new Exception('Banned word already initialized.');
		}
		$this->id   = $id;
		$this->word = $word;
	}

	public function delete() {
		$db = Loader::db();
		$db->Execute('DELETE FROM BannedWords WHERE bwID=?',array($this->id));
	}

	public static function getByID($id) {
		$db = Loader::db();
		$word = $db->getOne("SELECT bannedWord FROM BannedWords WHERE bwID=?",array($id));
		if (!$word) return false;
		$bw = new BannedWord($id, $word);
		return $bw;
	}

	public static function getByWord($word) {
		$db = Loader::db();
		$word = strtolower($word);
		$id = $db->getOne("SELECT bwID FROM BannedWords WHERE bannedWord=?",array($word));
		if (!$id) return false;
		$bw = new BannedWord($id, $word);
		return $bw;
	}

	public static function add($word) {
		if (!$word) return false;
		$db = Loader::db();
		$word = strtolower($word);
		if ($bw = BannedWord::getByWord($word)) return $bw;
		$db->execute('INSERT INTO BannedWords (bannedWord) VALUES (?)',array($word));
		$id = $db->Insert_ID();
		return new BannedWord($id, $word);
	}

}