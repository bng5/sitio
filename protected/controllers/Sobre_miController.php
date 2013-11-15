<?php

class Sobre_miController extends Controller {

	public function actionIndex() {
		header("Location: http://pablo.bng5.net/", null, 301);
	}
	public function actionGnupg() {
        $path = '';
        if($this->actionParams['id']) {
            $path = '/'.$this->actionParams['id'];
        }
		header("Location: http://pablo.bng5.net/gnupg{$path}", null, 301);
	}
	public function actionSsh_keys() {
		header("Location: http://pablo.bng5.net/ssh_keys", null, 301);
	}
	public function actionCv() {
		header("Location: http://uy.linkedin.com/in/pablobngs", null, 303);
	}

}