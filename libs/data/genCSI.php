<?php
require_once '../../libs/data/genElements/myList.php';
require_once '../../libs/data/genElements/text.php';
require_once '../../libs/data/genElements/click.php';

class genCSI {

	private $eoss;
	private $file;

	public $myList;
	public $text;
	public $click;

	public function __construct($eoss) {
		$this->eoss=$eoss;
		$this->file='../../app/layout.html';
		$this->myList=new myList;
		$this->text=new text;
		$this->click=new click;
	}
	public function setFile($dir) {
		$this->file=$dir;
		$this->csiAnalyze=new CSIAnalyze($dir);
		$this->eoss->setGenCsi();
	}
}
