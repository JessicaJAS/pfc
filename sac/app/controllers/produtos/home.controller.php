<?php
/**
*@author Wellington cezar, Diego Hernandes
*/
if(!defined('BASEPATH')) die('Acesso não permitido');
class home extends Controller{
	public function __construct(){
		parent::__construct();
	}


	/*---------------------------
	- PÁGINAS
	=============================*/


	/**
	*Página index
	*/
	public function index()
	{
		//$this->saveModules();
		//$this->saveAction();

		$data = array(
			'titlePage' => 'Produtos'
		);
		
		$this->load->view('includes/header',$data);
		$this->load->view('produtos/home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function cadastrar()
	{
		//$this->saveModules();
		//$this->saveAction();

		$data = array(
			'titlePage' => 'Cadastrar Produtos'
		);
		
		$this->load->view('includes/header',$data);
		$this->load->view('produtos/cadastro',$data);
		$this->load->view('includes/footer',$data);
	}

	public function editar()
	{
		//$this->saveAction();

		$data = array(
			'titlePage' => 'Editar produtos'
		);
		
		$this->load->view('includes/header',$data);
		$this->load->view('produtos/cadastro',$data);
		$this->load->view('includes/footer',$data);
	}

}


/**
*
*class: home
*
*location : controllers/home.controller.php
*/