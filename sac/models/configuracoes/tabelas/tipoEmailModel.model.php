<?php
/**
*@author Wellington cezar (programador jr) - wellington.infodahora@gmail.com
*/
if(!defined('URL')) die('Acesso negado');
class tipoEmailModel extends Controller{
	private $id;
	private $nome;
	private $status;

	public function __construct(){
		parent::__construct();
	}
	
	public function setId($id){
		$this->id = $id;
	}

	public function setNome($nome){
		$this->nome = $nome;
	}
	public function setStatus($status)
	{
		$this->status = $status;
	}


	/**
	*Retorna a lista de estado civil
	*/
	public function listar($status = '')
	{
		if($status != '')
			$status = ' AND status_tipo_email = "Ativo"';

		$this->clear();
		$this->setTabela('tipo_email');
		$this->setCondicao("status_tipo_email <> 'Excluído' $status");
		$this->select();
		$tipo_email = $this->resultAll();
		return $tipo_email;
	}

	public function getTipoemail($id)
	{
		$this->clear();
		$this->setTabela('tipo_email');
		$this->setCondicao("id_tipo_email = '".$id."'");
		$this->select();
		$tipo_email = $this->result();
		return $tipo_email;
	}


	public function inserir()
	{
		$data = array(
			'nome_tipo_email' => filter_var($this->nome),
			'status_tipo_email' => filter_var($this->status),
			'data_cadastro_tipo_email' => date('Y-m-d H:i:s')
		);

		$this->clear();
		$this->setTabela('tipo_email');
		$this->insert($data);

		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}



	public function atualizar()
	{
		$data = array(
			'nome_tipo_email' => filter_var($this->nome),
			'status_tipo_email' => filter_var($this->status)
		);

		$this->clear();
		$this->setTabela('tipo_email');
		$this->setCondicao('id_tipo_email = "'.$this->id.'"');
		$this->update($data);

		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}


	public function atualizarStatus()
	{
		$data = array(
			'status_tipo_email' => filter_var($this->status)
		);

		$this->clear();
		$this->setTabela('tipo_email');
		$this->setCondicao('id_tipo_email = "'.$this->id.'"');
		$this->update($data);

		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}

	public function deletar()
	{
		$this->clear();
		$this->setTabela('tipo_email');
		$this->setCondicao('id_tipo_email="'.$this->id.'"');
		$this->delete();
		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}


	
}

/**
*
*class: tipoEmailModel
*
*location : models/configuracoes/tabelas/tipoEmailModel.model.php
*/