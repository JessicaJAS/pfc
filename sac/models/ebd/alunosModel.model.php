<?php
/*CLASSESMODEL*/
if(!defined('URL')) die('Acesso negado');
class alunosModel extends Controller{
	private $id;
	private $membro;
	private $classe;
	private $status;


	public function setId($id)
	{
		$this->id = $id;
	}

	public function setMembro($membro)
	{
		$this->membro = $membro;
	}

	public function setClasse($classe)
	{
		$this->classe = $classe;
	}

	public function setStatus($status)
	{
		$this->status = $status;
	}


	public function listar($condicao = '<>', $valor = 'Excluído')
	{
		$classe = '';
		if($this->classe != ''){
			$classe = ' AND A.id_classe = "'.$this->classe.'"';
		}

		$this->clear();
		$this->query("SELECT A.*, B.foto_membro as foto_aluno, B.nome_membro AS nome_aluno, B.sobrenome_membro AS sobrenome_aluno, C.nome_classe_ebd
						FROM alunos_ebd AS A 
						INNER JOIN membros AS B ON A.id_membro = B.id_membro
						INNER JOIN classes_ebd AS C ON A.id_classe = C.id_classe_ebd
						WHERE A.status_aluno $condicao '$valor'
						$classe
						ORDER BY B.nome_membro, B.sobrenome_membro
						");

		if($this->rowCount() > 0)
		{
			return $this->resultAll();
		}else{
			return false;
		}
	}




	public function getAluno($condicao = '<>', $valor = 'Excluído')
	{
		$this->clear();
		$this->query("SELECT A.*, B.*, C.nome_classe_ebd
						FROM alunos_ebd AS A 
						INNER JOIN membros AS B ON A.id_membro = B.id_membro
						INNER JOIN classes_ebd AS C ON A.id_classe = C.id_classe_ebd
						WHERE A.status_aluno $condicao '$valor'
						AND A.id_aluno = '".$this->id."'
						");
		if($this->rowCount() > 0){
			return $this->result();
		}else{
			return false;
		}
	}

	
	public function inserir()
	{

		$data = array(
			'id_membro' => $this->membro,
			'id_classe' => $this->classe,
			'status_aluno'=> $this->status,
			'data_cadastro_aluno' => date('Y-m-d H:i:s')
		);
		$this->clear();
		$this->setTabela('alunos_ebd');
		$this->insert($data);

		if($this->rowCount() > 0)
		{
			return true;
		}else
		{
			return false;
		}
	}

	public function atualizar()
	{
		$dataFormat = new dataFormat();
		$data = array(
			'nome_classe_ebd' => $this->nomeClasse,
			'faixa_etaria_min' => $this->faixaEtariaMin,
			'faixa_etaria_max'=> $this->faixaEtariaMax,
			'descricao_geral_curriculo' => $this->descricaoGeral,
			'id_departamento_ebd' => $this->departamento,
			'id_igreja' => $this->igreja
		);
		$this->clear();
		$this->setTabela('classes_ebd');
		$this->setCondicao('id_classe_ebd = "'.$this->id.'"');
		$this->update($data);
		if($this->rowCount() > 0)
		{
			return true;
		}else
		{
			return false;
		}
	}



	
	//atualizarStatus
	public function atualizarStatus()
	{
		$data = array(
			'status_classe_ebd' => filter_var($this->status)
		);

		$this->clear();
		$this->setTabela('alunos_ebd');
		$this->setCondicao('id_alunos_ebd = "'.$this->id.'"');
		$this->update($data);

		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}

	//atualizarStatus
	public function atualizarStatusAluno()
	{
		$data = array(
			'status_aluno' => filter_var($this->status)
		);

		$this->clear();
		$this->setTabela('alunos_ebd');
		$this->setCondicao('id_aluno = "'.$this->id.'"');
		$this->update($data);

		if($this->rowCount() > 0)
			return true;
		else
			return false;
	}
}