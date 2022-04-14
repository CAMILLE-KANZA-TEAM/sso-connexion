<?php

/**
 * 
 * 
 */
class LoginController extends BaseController {


	// Initialisation du contructeur par défaut
	public function __construct()
    {
    	parent::__construct();
   	}

	// Page d'accueil des posts
	public function index($params=array()) {

		// on choisi la template à appeler
		$template = $this->twig->load('login/index.html');

		// Puis on affiche avec la méthode render
		echo $template->render([]);
	}

}