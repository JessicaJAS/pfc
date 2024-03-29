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
	 * Página index
	 */
	public function index()
	{
		$data = array(
			'titlePage' => 'Fornecedores'
		);

		/*$this->load->dao('fornecedores/fornecedoresDao');
		$fornecedores = new fornecedoresDao();
		$data['fornecedores'] = $fornecedores->listar();
		*/
		$this->load->view('includes/header',$data);
		$this->load->view('fornecedores/home',$data);
		$this->load->view('includes/footer',$data);

	}


	/**
	 * Página de cadastro
	 */
	public function cadastrar()
	{
		$data = array(
			'titlePage' => 'Cadastrar fornecedores'
		);
		
		$this->load->view('includes/header',$data);
		$this->load->view('fornecedores/cadastro',$data);
		$this->load->view('includes/footer',$data);
	}


	/**
	 * Página de edição
	 */
	public function editar()
	{
		$data = array(
			'titlePage' => 'Editar fornecedores'
		);
		//ID
		$idFuncionario = intval($this->url->getSegment(3));
		
		//FUNCIONARIO MODEL
		$this->load->model('fornecedores/fornecedoresModel');
		$fornecedoresModel = new fornecedoresModel();
		$fornecedoresModel->setId($idFuncionario);

		//FUNCIONARIO DAO
		$this->load->dao('fornecedores/fornecedoresDao');
		$fornecedoresDao = new fornecedoresDao();
		$data['fornecedor'] = $fornecedoresDao->consultar($fornecedoresModel);
		
		//DATAFORMAT
		$this->load->library('dataFormat');
		$data['dataFormat'] = $this->dataFormat;

		$this->load->view('includes/header',$data);
		$this->load->view('fornecedores/editar',$data);
		$this->load->view('includes/footer',$data);
	}





	/*----------------------------
	- AÇÕES
	=============================*/
	/**
	 * Ação do cadastrar
	 */
	public function inserir()
	{
		$foto = isset($_FILES['foto']) ? $_FILES['foto'] : '';
		$razaoSocial = isset($_POST['razaoSocial']) ? filter_var($_POST['razaoSocial']) : '';
		$nomeFantasia = isset($_POST['nomeFantasia']) ? filter_var($_POST['nomeFantasia']) : '';
		$cnpj = isset($_POST['cnpj']) ? filter_var(trim($_POST['cnpj'])) : '';
		$cpf = isset($_POST['cpf']) ? filter_var($_POST['cpf']) : '';
		$pessoa = isset($_POST['pessoa']) ? filter_var($_POST['pessoa']) : '';
		$site = isset($_POST['site']) ? filter_var($_POST['site']) : '';
		$observacoes = isset($_POST['observacoes']) ? filter_var($_POST['observacoes']) : '';
		

		//endereço
		$cep = isset($_POST['cep']) ? filter_var(trim($_POST['cep'])) : '';
		$logradouro = isset($_POST['logradouro']) ? filter_var(trim($_POST['logradouro'])) : '';
		$numero = isset($_POST['numero']) ? filter_var(trim($_POST['numero'])) : '';
		$cidade = isset($_POST['cidade']) ? filter_var(trim($_POST['cidade'])) : '';
		$bairro = isset($_POST['bairro']) ? filter_var(trim($_POST['bairro'])) : '';
		$estado = isset($_POST['estado']) ? filter_var(trim($_POST['estado'])) : '';
        $pais = isset($_POST['pais']) ? filter_var(trim($_POST['pais'])) :'';
		//contato
		$nomeContato = isset($_POST['nomeContato']) ? filter_var($_POST['nomeContato']) : '';
		$telefones = isset($_POST['telefones']) ? filter_var_array($_POST['telefones']) : Array();
		$email = isset($_POST['email']) ? filter_var_array($_POST['email']) : Array();



		//validação dos dados
		$this->load->library('dataValidator');
		
		$this->dataValidator->set('Razao Social', $razaoSocial, 'razaoSocial')->is_required()->min_length(2);
		$this->dataValidator->set('Nome Fantasia', $nomeFantasia, 'nomeFantasia')->is_required()->min_length(2);
		$this->dataValidator->set('CNPJ', $cnpj, 'cnpj')->is_required()->is_required();
		$this->dataValidator->set('CPF', $cpf, 'cpf')->is_required();
		$this->dataValidator->set('Pessoa', $pessoa, 'pessoa')->is_required();
		$this->dataValidator->set('Logradouro', $logradouro, 'logradouro')->is_required();
		$this->dataValidator->set('Número', $numero, 'numero')->is_required()->is_num();
		$this->dataValidator->set('Bairro', $bairro, 'bairro')->is_required();
		$this->dataValidator->set('Cidade', $cidade, 'cidade')->is_required();
		$this->dataValidator->set('Estado', $estado, 'estado')->is_required();

		

		if ($this->dataValidator->validate())
		{
			//TELEFONES
			$telefonesList = Array();
			$this->load->model('telefoneModel');
			foreach ($telefones as $telefone)
			{
				$telefoneModel = new telefoneModel();
				$telefoneModel->setCategoria( $telefone['categoria'] );
				$telefoneModel->setNumero( $telefone['telefone'] );
				$telefoneModel->setOperadora( $telefone['operadora'] );
				$telefoneModel->setTipo( $telefone['tipo_telefone'] );
				array_push($telefonesList, $telefoneModel);
				unset($telefoneModel);
			}


			//EMAILS
			$emailList = Array();
			$this->load->model('emailModel');
			foreach ($emails as $email)
			{
				$emailModel = new emailModel();
				$emailModel->setEmail( $email['email'] );
				array_push($emailList, $emailModel);
				unset($emailModel);
			}



			//ENDEREÇO
			$this->load->model('enderecoModel');
			$enderecoModel = new enderecoModel();
			$enderecoModel->setCep($cep);
			$enderecoModel->setLogradouro($logradouro);
			$enderecoModel->setNumero($numero);
			$enderecoModel->setCidade($cidade);
			$enderecoModel->setBairro($bairro);
			$enderecoModel->setEstado($estado);
			$enderecoModel->setPais($pais);
			

			//FORNECEDOR
			$this->load->model('fornecedores/fornecedoresModel');
			$fornecedoresModel = new fornecedoresModel();
			$fornecedoresModel->setFoto($foto);
			$fornecedoresModel->setRazaoSocial($razaoSocial);
			$fornecedoresModel->setNomeFantasia($nomeFantasia);
			$fornecedoresModel->setCNPJ($cnpj);
			$fornecedoresModel->setCpf($cpf);
			$fornecedoresModel->setPessoa($pessoa);
			$fornecedoresModel->setSite($site);
			$fornecedoresModel->setObservacoes($observacoes);
			$fornecedoresModel->setEndereco($enderecoModel);
			$fornecedoresModel->setTelefones($telefonesList);
			$fornecedoresModel->setEmail($emailList);
			$fornecedoresModel->setStatus(status::ATIVO);
			$fornecedoresModel->setDataCadastro(date('Y-m-d h:i:s'));


			//FORNECEDOR DAO
			$this->load->dao('fornecedores/fornecedoresDao');
			$fornecedoresDao = new fornecedoresDao();
			echo $fornecedoresDao->inserir($fornecedoresModel);
		}else
	    {
			$todos_erros = $this->dataValidator->get_errors();
			echo json_encode($todos_erros);
	    }

	}



	/**
	 * Ação do editar
	 */
	/**
	 * Ação do cadastrar
	 */
	public function atualizar()
	{
		$idFornecedor = isset($_POST['id_fornecedor']) ? filter_var($_POST['id_fornecedor']) : '';
		$foto = isset($_FILES['foto']) ? $_FILES['foto'] : '';
		$razaoSocial = isset($_POST['razaoSocial']) ? filter_var($_POST['razaoSocial']) : '';
		$nomeFantasia = isset($_POST['nomeFantasia']) ? filter_var($_POST['nomeFantasia']) : '';
		$cnpj = isset($_POST['cnpj']) ? filter_var(trim($_POST['cnpj'])) : '';
		$cpf = isset($_POST['cpf']) ? filter_var($_POST['cpf']) : '';
		$pessoa = isset($_POST['pessoa']) ? filter_var($_POST['pessoa']) : '';
		$site = isset($_POST['site']) ? filter_var($_POST['site']) : '';
		$observacoes = isset($_POST['observacoes']) ? filter_var($_POST['observacoes']) : '';
		

		//endereço
		$cep = isset($_POST['cep']) ? filter_var(trim($_POST['cep'])) : '';
		$logradouro = isset($_POST['logradouro']) ? filter_var(trim($_POST['logradouro'])) : '';
		$numero = isset($_POST['numero']) ? filter_var(trim($_POST['numero'])) : '';
		$cidade = isset($_POST['cidade']) ? filter_var(trim($_POST['cidade'])) : '';
		$bairro = isset($_POST['bairro']) ? filter_var(trim($_POST['bairro'])) : '';
		$estado = isset($_POST['estado']) ? filter_var(trim($_POST['estado'])) : '';
        $pais = isset($_POST['pais']) ? filter_var(trim($_POST['pais'])) :'';
		//contato
		$nomeContato = isset($_POST['nomeContato']) ? filter_var($_POST['nomeContato']) : '';
		$telefones = isset($_POST['telefones']) ? filter_var_array($_POST['telefones']) : Array();
		$email = isset($_POST['email']) ? filter_var_array($_POST['email']) : Array();



		//validação dos dados
		$this->load->library('dataValidator');
		
		$this->dataValidator->set('Razao Social', $razaoSocial, 'razaoSocial')->is_required()->min_length(2);
		$this->dataValidator->set('Nome Fantasia', $nomeFantasia, 'nomeFantasia')->is_required()->min_length(2);
		$this->dataValidator->set('CNPJ', $cnpj, 'cnpj')->is_required()->is_required();
		$this->dataValidator->set('CPF', $cpf, 'cpf')->is_required();
		$this->dataValidator->set('Pessoa', $pessoa, 'pessoa')->is_required();
		$this->dataValidator->set('Logradouro', $logradouro, 'logradouro')->is_required();
		$this->dataValidator->set('Número', $numero, 'numero')->is_required()->is_num();
		$this->dataValidator->set('Bairro', $bairro, 'bairro')->is_required();
		$this->dataValidator->set('Cidade', $cidade, 'cidade')->is_required();
		$this->dataValidator->set('Estado', $estado, 'estado')->is_required();

		

		if ($this->dataValidator->validate())
		{
			//TELEFONES
			$telefonesList = Array();
			$this->load->model('telefoneModel');
			foreach ($telefones as $telefone)
			{
				$telefoneModel = new telefoneModel();
				$telefoneModel->setCategoria( $telefone['categoria'] );
				$telefoneModel->setNumero( $telefone['telefone'] );
				$telefoneModel->setOperadora( $telefone['operadora'] );
				$telefoneModel->setTipo( $telefone['tipo_telefone'] );
				array_push($telefonesList, $telefoneModel);
				unset($telefoneModel);
			}


			//EMAILS
			$emailList = Array();
			$this->load->model('emailModel');
			foreach ($emails as $email)
			{
				$emailModel = new emailModel();
				$emailModel->setEmail( $email['email'] );
				array_push($emailList, $emailModel);
				unset($emailModel);
			}



			//ENDEREÇO
			$this->load->model('enderecoModel');
			$enderecoModel = new enderecoModel();
			$enderecoModel->setCep($cep);
			$enderecoModel->setLogradouro($logradouro);
			$enderecoModel->setNumero($numero);
			$enderecoModel->setCidade($cidade);
			$enderecoModel->setBairro($bairro);
			$enderecoModel->setEstado($estado);
			$enderecoModel->setPais($pais);
			

			//FORNECEDOR
			$this->load->model('fornecedores/fornecedoresModel');
			$fornecedoresModel = new fornecedoresModel();
			$fornecedoresModel->setFoto($foto);
			$fornecedoresModel->setRazaoSocial($razaoSocial);
			$fornecedoresModel->setNomeFantasia($nomeFantasia);
			$fornecedoresModel->setCNPJ($cnpj);
			$fornecedoresModel->setCpf($cpf);
			$fornecedoresModel->setPessoa($pessoa);
			$fornecedoresModel->setSite($site);
			$fornecedoresModel->setObservacoes($observacoes);
			$fornecedoresModel->setEndereco($enderecoModel);
			$fornecedoresModel->setTelefones($telefonesList);
			$fornecedoresModel->setEmail($emailList);
			$fornecedoresModel->setStatus(status::ATIVO);
			$fornecedoresModel->setDataCadastro(date('Y-m-d h:i:s'));


			//FORNECEDOR DAO
			$this->load->dao('fornecedores/fornecedoresDao');
			$fornecedoresDao = new fornecedoresDao();
			echo $fornecedoresDao->inserir($fornecedoresModel);
		}else
	    {
			$todos_erros = $this->dataValidator->get_errors();
			echo json_encode($todos_erros);
	    }

	}

     /*
	 * Ãção de atualizar status
	 */
	public function atualizarStatus()
	{
		$idFornecedor = intval($_POST['id']);
		$status = filter_var($_POST['status']);

		//FUNCIONARIO MODEL
		$this->load->model('fornecedores/fornecedoresModel');
		$fornecedoresModel = new fornecedoresModel();
		$fornecedoresModel->setId( $idFornecedor );
		$fornecedoresModel->setStatus( $status );

		//FUNCIONARIO DAO
		$this->load->dao('fornecedores/fornecedoresDao');
		$fornecedoresDao = new fornecedoresDao();
		echo $fornecedoresDao->atualizarStatus($fornecedoresModel);

	}

	public function excluir()
	{
		$this->atualizarStatus();
	}

}