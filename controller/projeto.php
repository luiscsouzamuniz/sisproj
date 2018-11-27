<?php  

/**
* 
*/
$command  = (isset($_REQUEST['command'])) ? $_REQUEST['command'] : false ; 

class projeto
{
	private $Model_Projeto;

	function __construct()
	{
		if (!class_exists('Model_Projeto')) {
			require_once '../Autoload.php';
			$Autoload = new Autoload;
		}

		$this->Model_Projeto = new Model_Projeto;

	}

	public function listarCategorias()
	{
		return $this->Model_Projeto->listarCategoriaProjeto();
	}

	public function listarProjetosCurso($idCurso)
	{
		$sqlQuery = "SELECT * FROM proj_projeto where idCurso = $idCurso";
		$select   = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function exibirProjetoCompleto($idProjeto)
	{
		$sqlQuery = "SELECT * FROM proj_projeto p, proj_categoria_projeto cp  WHERE cp.idCategoria = p.idCategoria AND idProjeto = $idProjeto";
		$select   = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function selecionarAlunosProjeto($idProjeto)
	{
		$sqlQuery = "SELECT * FROM proj_projeto p, proj_inscricao_projeto ip WHERE p.idProjeto = ip.idProjeto AND ip.status = 1 AND ip.idProjeto = $idProjeto";

		$select  = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function selecionarInscricaoAluno($ra, $idProjeto)
	{
		$sqlQuery = "SELECT * FROM proj_projeto p, proj_inscricao_projeto ip WHERE p.idProjeto = ip.idProjeto AND ip.raAluno = '$ra' AND p.idProjeto = $idProjeto";

		$select  = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function listarInscricaoAluno($ra)
	{
		$sqlQuery = "SELECT * FROM proj_projeto p, proj_inscricao_projeto ip WHERE p.idProjeto = ip.idProjeto AND ip.raAluno = '$ra' AND ip.status = 1";

		$select  = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function listarProjetoProfResp($idProfessor)
	{
		if ($_SESSION['dados_professor']['sucesso']['coordenador'] == 1) {
			$API = new consumo_api;

			$cursosCoordenados = $API->listarCursosCoordenados($_SESSION['chave_professor']);



			$where = ' || ';

			$cursos = count($cursosCoordenados['sucesso']);
			$contador = 1;
			foreach ($cursosCoordenados['sucesso'] as $listaCursosCoordenados) {
				$where .= "idCurso = '".$listaCursosCoordenados['codigo']."'";

				if ($contador < $cursos) {
					$where .= ' || ';
					$contador++;
				}
			}

			$sqlQuery = "SELECT  DISTINCT p.idProjeto, p.titulo, p.idCurso, p.responsavel, p.dtInicio, p.dtTermino, cp.categoria FROM proj_categoria_projeto cp, proj_projeto p left join proj_alocacao_projeto ap ON (ap.idProjeto = p.idProjeto) WHERE (ap.idProfessor = $idProfessor || p.responsavel = $idProfessor $where) AND cp.idCategoria = p.idCategoria ";

			return $this->Model_Projeto->listarProjetos($sqlQuery);
		}else{
			$sqlQuery = "SELECT  DISTINCT p.idProjeto, p.titulo, p.idCurso, p.responsavel, p.dtInicio, p.dtTermino, cp.categoria FROM proj_categoria_projeto cp, proj_projeto p left join proj_alocacao_projeto ap ON (ap.idProjeto = p.idProjeto) WHERE (ap.idProfessor = $idProfessor || p.responsavel = $idProfessor) AND cp.idCategoria = p.idCategoria ";
			return $this->Model_Projeto->listarProjetos($sqlQuery);
		}

		
	}


	public function inscricao()
	{
		$idProjeto    = (isset($_REQUEST['idProjeto'])) ? $_REQUEST['idProjeto'] : false;
		$raAluno      = (isset($_REQUEST['raAluno']))   ? $_REQUEST['raAluno']   : false;
		$cpfAluno     = (isset($_REQUEST['cpfAluno']))  ? $_REQUEST['cpfAluno']  : false;
		$parametro    = (isset($_REQUEST['parametro'])) ? $_REQUEST['parametro'] : false;

		$page         = (isset($_REQUEST['page']))      ? $_REQUEST['page']      : false;

		if ($idProjeto && $raAluno && $cpfAluno) {
			$verificar = $this->selecionarInscricaoAluno($raAluno, $idProjeto);
			if (empty($verificar) && $parametro == 0) {
				#executa o cadastro
				$this->Model_Projeto->setIdProjeto($idProjeto);
				$cadastrar = $this->Model_Projeto->inscreverAlunoProjeto(strval($raAluno), strval($cpfAluno));
				if ($cadastrar) {
					echo 'Inscrito com sucesso!';
				}else{
					echo 'Houve um erro de inscrição, tente novamente!';
				}
			}elseif (!empty($verificar) && $parametro == 1){
				#deleta a inscricao
				$this->Model_Projeto->setIdProjeto($idProjeto);
				$desativar = $this->Model_Projeto->desativarAlunoProjeto(strval($raAluno), strval($cpfAluno));

				if ($desativar) {
					echo 'Inscrição cancelada com sucesso!';

					if ($page == 'visualizar-projeto' || $page == 'inscricoes') {
						
						$_SESSION['mensagem'] = array( 'texto' => 'Excluído com sucesso!.', 'classe' => 'success', 'tipo' => 'Sucesso!');

						header('location: '.URL.'admin/'.$page.'?projeto='.$idProjeto);
					}
				}else{
					echo 'Houve um erro na solicitação do cancelamento, tente novamente!';
				}
			}elseif(!empty($verificar) && $parametro == 0){
				#deleta a inscricao
				$this->Model_Projeto->setIdProjeto($idProjeto);
				$ativar = $this->Model_Projeto->ativarAlunoProjeto(strval($raAluno), strval($cpfAluno));

				if ($ativar) {
					echo 'Inscrito com sucesso!';
				}else{
					echo 'Houve um erro de inscrição, tente novamente!';
				}
			}
		}else{
			echo "ok";
		}

	}

	public function cadastrar()
	{
		$titulo    = (isset($_POST['titulo'])) ? $_POST['titulo'] : false;
		$categoria = (isset($_POST['categoria'])) ? $_POST['categoria'] : false;
		$curso     = (isset($_POST['curso'])) ? $_POST['curso'] : false;
		$dtInicio  = (isset($_POST['dtInicio'])) ? $_POST['dtInicio'] : false;
		$dtTermino = (isset($_POST['dtTermino'])) ? $_POST['dtTermino'] : false;

		if ($this->validarCampos($titulo, $categoria, $curso, $dtInicio, $dtTermino)) {
			
			$cadastrar = $this->Model_Projeto->cadastrarProjeto();

			if ($cadastrar) {
				$url = URL.'admin/cadastro-projetos';
				$_SESSION['mensagem'] = array( 'texto' => 'Cadastro realizado.', 'classe' => 'success', 'tipo' => 'Sucesso!');
			}else{
				$url = URL.'admin/cadastro-projetos';
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível cadastrar! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			}
		}else{
			$url = URL.'admin/cadastro-projetos';
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
		}

		header('location: '.$url);
	}

	public function cadastrarAlocacao()
	{
		$projeto      = (isset($_POST['projeto']))      ? $_POST['projeto'] 	 : false ;
		$professor    = (isset($_POST['professor']))    ? $_POST['professor'] 	 : false ;
		$funcao       = (isset($_POST['funcao']))       ? $_POST['funcao'] 		 : false ;
		$cargaHoraria = (isset($_POST['cargaHoraria'])) ? $_POST['cargaHoraria'] : false ;
		$valorHora    = (isset($_POST['valorHora']))    ? $_POST['valorHora']    : false ;

		if ($projeto && $professor && $funcao && $cargaHoraria && $valorHora) {
			$this->Model_Projeto->setIdProjeto($projeto);
			$alocacao = $this->Model_Projeto->cadastrarAlocacao($professor, $funcao, $cargaHoraria, $valorHora);

			if ($alocacao) {
				$url = URL.'admin/alocacao';
				$_SESSION['mensagem'] = array( 'texto' => 'Cadastro realizado.', 'classe' => 'success', 'tipo' => 'Sucesso!');
			}else{
				$url = URL.'admin/alocacao';
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível cadastrar! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			}
		}else{
			$url = URL.'admin/alocacao';
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
		}

		header('location: '.$url);

		
	}

	public function excluirAlocacao()
	{
		$idAlocacao    = (isset($_REQUEST['idAlocacao'])) ? $_REQUEST['idAlocacao'] : false ;
		$idProjeto     = (isset($_REQUEST['idProjeto']))  ? $_REQUEST['idProjeto']  : false ;

		if ($idAlocacao && $idProjeto) {
			$this->Model_Projeto->setIdProjeto($idProjeto);
			$excluir = $this->Model_Projeto->excluirAlocacao($idAlocacao);

			if ($excluir) {
				$_SESSION['mensagem'] = array( 'texto' => 'Excluído com  sucesso.', 'classe' => 'success', 'tipo' => 'Sucesso!');
				$url = URL.'admin/listar-projetos';

			}else{
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível excluir! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
				$url = URL.'admin/listar-projetos';

			}
		}else{
			$_SESSION['mensagem'] = array( 'texto' => 'Erro de requisição, tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			$url = URL.'admin/listar-projetos';

		}

		header('location: '.$url);
	}

	public function selecionarProfessoresAlocados($idProjeto)
	{
		$sqlQuery = "SELECT * FROM proj_alocacao_projeto WHERE idProjeto = $idProjeto";
		$select = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	private function validarCampos($titulo, $categoria, $curso, $dtInicio, $dtTermino)
	{
		if ($titulo && $categoria && $curso && $dtInicio && $dtTermino) {
			$this->Model_Projeto->setTitulo($titulo);
			$this->Model_Projeto->setIdCategoria($categoria);
			$this->Model_Projeto->setIdCurso($curso);
			$this->Model_Projeto->setDtInicio($dtInicio);
			$this->Model_Projeto->setDtTermino($dtTermino);
			$this->Model_Projeto->setResponsavel($_SESSION['dados_professor']['sucesso']['codigo']);
			return true;
		}else{
			return false;
		}
	}

	public function cadastrarSessaoProjeto()
	{
		$idProjeto = ($_POST['projeto'])  ? $_POST['projeto']  : false;
		$dtInicio  = ($_POST['dtInicio']) ? $_POST['dtInicio'] : false;
		$dtTermino = ($_POST['dtTermino'])? $_POST['dtTermino']: false;
		$nome      = ($_POST['nome'])     ? $_POST['nome']     : false;

		if ($nome && $idProjeto && $dtInicio && $dtTermino) {
			$cadastrar = $this->Model_Projeto->cadastrarSessaoProjeto($idProjeto,$dtInicio, $dtTermino, $nome);

			if ($cadastrar) {
				$_SESSION['mensagem'] = array( 'texto' => 'Cadastrado com  sucesso.', 'classe' => 'success', 'tipo' => 'Sucesso!');
				$url = URL.'admin/sessao-projeto';
			}else{
				$url = URL.'admin/sessao-projeto';
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível cadastrar! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			}
		}else{
			$url = URL.'admin/sessao-projeto';
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
		}

		header('location: '.$url);

	}

	//VAI MUDAR
	public function listarSessaoProjeto($idProjeto)
	{
		$sqlQuery = "SELECT sp.* FROM proj_sessao_projeto sp WHERE idProjeto = $idProjeto";

		$select = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function listarSessaoItemAluno($idSessao, $raAluno)
	{
		$sqlQuery = "SELECT spi.* FROM proj_sessao_projeto_item spi WHERE idSessao = $idSessao AND raAluno = $raAluno";

		$select = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function cadastrarSessaoProjetoItem()
	{
		$texto     = (isset($_POST['texto']))     ? $_POST['texto']             : false;
		$raAluno   = (isset($_POST['raAluno']))   ? strval($_POST['raAluno'])   : false;
		$idSessao  = (isset($_POST['idSessao']))  ? $_POST['idSessao']          : false;
		$idStatus  = (isset($_POST['idStatus']))  ? $_POST['idStatus']          : false;
		$idProjeto = (isset($_POST['idProjeto'])) ? $_POST['idProjeto']         : false;

		if ($texto && $raAluno && $idSessao && $idStatus) {
			$cadastrar = $this->Model_Projeto->cadastrarSessaoProjetoItem($texto, $raAluno, $idSessao, $idStatus);

			if ($cadastrar) {
				$_SESSION['mensagem'] = array( 'texto' => 'Cadastrado com  sucesso.', 'classe' => 'success', 'tipo' => 'Sucesso!');
				$url = URL.'aluno/visualizar-projeto?projeto='.$idProjeto;
			}else{
				$url = URL.'aluno/visualizar-projeto';
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível cadastrar! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			}
		}else{
			$url = URL.'aluno/visualizar-projeto';
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
		}

		header('location: '.$url);

	}

	public function alterarSessaoProjetoItem()
	{
		$texto     = (isset($_POST['texto']))     ? $_POST['texto']             : false;
		$raAluno   = (isset($_POST['raAluno']))   ? strval($_POST['raAluno'])   : false;
		$idSessao  = (isset($_POST['idSessao']))  ? $_POST['idSessao']          : false;
		$idStatus  = (isset($_POST['idStatus']))  ? $_POST['idStatus']          : false;
		$idProjeto = (isset($_POST['idProjeto'])) ? $_POST['idProjeto']         : false;
		$idItem    = (isset($_POST['idItem']))    ? $_POST['idItem']            : false;
		$page      = (isset($_POST['page']))      ? $_POST['page']              : false;

		if ($texto && $raAluno && $idSessao && $idStatus && $idItem) {
			$alterar = $this->Model_Projeto->alterarSessaoProjetoItem($texto, $raAluno, $idSessao, $idStatus, $idItem);

			if ($alterar) {
				$_SESSION['mensagem'] = array( 'texto' => 'Alterado com  sucesso.', 'classe' => 'success', 'tipo' => 'Sucesso!');
				$url = URL.$page.'?projeto='.$idProjeto;
			}else{
				$url = URL.$page.'?projeto='.$idProjeto;
				$_SESSION['mensagem'] = array( 'texto' => 'Não foi possível alterar! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			}
		}else{
			$url = URL.$page.'?projeto='.$idProjeto;
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
		}

		header('location: '.$url);
	}

	public function selecionarSessaoItem($idSessao, $idItem)
	{
		$sqlQuery = "SELECT sp.*, spi.idItem, spi.texto, spi.idStatus, spi.raAluno, spi.dtCriacao, p.titulo  FROM proj_projeto p, proj_sessao_projeto sp left join proj_sessao_projeto_item spi on (spi.idSessao = sp.idSessao) WHERE spi.idSessao = $idSessao AND spi.idItem = $idItem AND p.idProjeto = sp.idProjeto";

		$select = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}

	public function selecionarStatusItem($idStatus)
	{
		$sqlQuery = "SELECT * FROM proj_status_item WHERE idStatus = $idStatus";

		$select = $this->Model_Projeto->listarProjetos($sqlQuery);

		return $select;
	}
}

$projeto = new projeto;

if ($command) {
	$exec = $projeto->$command();
}

?>