<?php  

/**
* 
*/
class Model_Projeto
{
	private $Model_CRUD;
	private $idProjeto;
	private $titulo;
	private $idCurso;
	private $dtInicio;
	private $dtTermino;
	private $responsavel;
	private $idCategoria;

	function __construct()
	{
		$this->Model_CRUD = new Model_CRUD;
	}

	public function listarCategoriaProjeto()
	{
		$sqlQuery = "SELECT * FROM proj_categoria_projeto ORDER BY idCategoria ASC";

		$dados = $this->Model_CRUD->select($sqlQuery);

		return $dados;
	}


	public function cadastrarProjeto()
	{
		$titulo      = $this->getTitulo();
		$idCategoria = $this->getIdCategoria();
		$idCurso     = $this->getIdCurso();
		$dtInicio    = $this->getDtInicio();
		$dtTermino   = $this->getDtTermino();
		$responsavel = $this->getResponsavel();
		$dtCriacao   = date('Y-m-d H:i:s');
		$status      = 1;

		$cadastrar = $this->Model_CRUD->insert(	'proj_projeto', 
												array(
													'titulo',
													'idCurso',
													'dtInicio',
													'dtTermino',
													'responsavel',
													'dtCriacao',
													'status',
													'idCategoria'
												), 
												array(
													$titulo,
													$idCurso,
													$dtInicio,
													$dtTermino,
													$responsavel,
													$dtCriacao,
													$status,
													$idCategoria
												));

		if ($cadastrar) {
			return true;
		}else{
			return false;
		}
	}
	
	
	public function listarProjetos($sqlQuery)
	{
		
		$select = $this->Model_CRUD->select($sqlQuery);

		return $select;
	}

	public function cadastrarAlocacao($professor, $funcao, $cargaHoraria, $valorHora)
	{
		$idProjeto = $this->getIdProjeto();
		$cadastrarAlocacao = $this->Model_CRUD->insert('proj_alocacao_projeto', 
														array(
															'idProfessor',
															$funcao,
															'idProjeto',
															'cargaHoraria',
															'valorHora'
														),
														array(
															$professor,
															1,
															$idProjeto,
															$cargaHoraria,
															$valorHora
														)
													);
		if ($cadastrarAlocacao) {
			return true;
		}else{
			return false;
		}
	}

	public function excluirAlocacao($idAlocacao)
	{
		$excluir = $this->Model_CRUD->delete(
											'proj_alocacao_projeto',
											array(
												'idProjeto',
												'idAlocacao'
											),
											array(
												$this->getIdProjeto(),
												$idAlocacao
											)
										);
		if ($excluir) {
			return true;
		}else{
			return false;
		}
	}

	public function inscreverAlunoProjeto($raAluno, $cpfAluno)
	{
		
		$cadastrar = $this->Model_CRUD->insert(
											'proj_inscricao_projeto',
											array(
												'idProjeto',
												'raAluno',
												'cpfAluno',
												'status',
												'dtCriacao'
											),
											array(
												$this->getIdProjeto(),
												strval($raAluno),
												strval($cpfAluno),
												1,
												date('Y-m-d H:i:s')
											)
										);

		if ($cadastrar) {
			return true;
		}else{
			return false;
		}
	}

	public function desativarAlunoProjeto($raAluno, $cpfAluno)
	{
		$idProjeto = $this->getIdProjeto();
		$cancelar = $this->Model_CRUD->update(
											'proj_inscricao_projeto',
											array(
												'status',
												'dtModificacao'
											),
											array(
												0,
												date('Y-m-d H:i:s')
											),

											"WHERE idProjeto = $idProjeto AND raAluno = '$raAluno' AND cpfAluno = '$cpfAluno'"
										);
		if ($cancelar) {
			return true;
		}else{
			return false;
		}
	}

	public function ativarAlunoProjeto($raAluno, $cpfAluno)
	{
		$idProjeto = $this->getIdProjeto();
		$ativar = $this->Model_CRUD->update(
											'proj_inscricao_projeto',
											array(
												'status',
												'dtModificacao'
											),
											array(
												1,
												date('Y-m-d H:i:s')
											),

											"WHERE idProjeto = $idProjeto AND raAluno = '$raAluno' AND cpfAluno = '$cpfAluno'"
										);
		if ($ativar) {
			return true;
		}else{
			return false;
		}
	}

	public function cadastrarSessaoProjeto($idProjeto,$dtInicio, $dtTermino, $nome)
	{
		
		$cadastrarSessaoProjeto = $this->Model_CRUD->insert(
															'proj_sessao_projeto',
															array(
																'idProjeto',
																'dtInicio',
																'dtTermino',
																'nome',
																'dtCriacao'
															),
															array(
																$idProjeto,
																$dtInicio,
																$dtTermino,
																$nome,
																date('Y-m-d H:i:s')
															)
														);

		if ($cadastrarSessaoProjeto) {
			return true;
		}else{
			return false;
		}
	}

	public function cadastrarSessaoProjetoItem($texto, $raAluno, $idSessao, $idStatus)
	{
		$cadastrarSessaoProjetoItem = $this->Model_CRUD->insert(
														'proj_sessao_projeto_item',
														array(
															'texto',
															'raAluno',
															'idSessao',
															'idStatus',
															'dtCriacao'
														),
														array(
															$texto,
															strval($raAluno),
															$idSessao,
															$idStatus,
															date('Y-m-d H:i:s')
														)
													);
		if ($cadastrarSessaoProjetoItem) {
			return true;
		}else{
			return false;
		}
	}

	public function alterarSessaoProjetoItem($texto, $raAluno, $idSessao, $idStatus, $idItem)
	{
		$alterar = $this->Model_CRUD->update(
											'proj_sessao_projeto_item',
											array(
												'texto',
												'idStatus',
												'dtModificacao'
											),
											array(
												$texto,
												$idStatus,
												date('Y-m-d H:i:s')
											),

											"WHERE idSessao = $idSessao AND idItem = $idItem AND raAluno = $raAluno"
										);

		if ($alterar) {
			return true;
		}else{
			return false;
		}
	}

	//get

	public function getIdProjeto()
	{
		return $this->idProjeto;
	}

	public function getTitulo()
	{
		return $this->titulo;
	}

	public function getIdCurso()
	{
		return $this->idCurso;
	}

	public function getDtInicio()
	{
		return $this->dtInicio;
	}

	public function getDttermino()
	{
		return $this->dtTermino;
	}

	public function getResponsavel()
	{
		return $this->responsavel;
	}

	public function getIdCategoria()
	{
		return $this->idCategoria;
	}

	//SET

	public function setIdProjeto($idProjeto)
	{
		$this->idProjeto = $idProjeto;
	}

	public function setTitulo($titulo)
	{
		$this->titulo = $titulo;
	}

	public function setIdCurso($idCurso)
	{
		$this->idCurso = $idCurso;
	}

	public function setDtInicio($dtInicio)
	{
		$this->dtInicio = $dtInicio;
	}

	public function setDttermino($dtTermino)
	{
		$this->dtTermino = $dtTermino;
	}

	public function setResponsavel($responsavel)
	{
		$this->responsavel = $responsavel;
	}

	public function setIdCategoria($idCategoria)
	{
		$this->idCategoria = $idCategoria;
	}

}

?>