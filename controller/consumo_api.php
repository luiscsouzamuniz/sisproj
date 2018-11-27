<?php 

/**
* 
*/
class consumo_api
{
	public $API;

	function __construct()
	{
		$this->API = new Model_Unisepe;
	}

    // ------------------------------- PROFESSOR ------------------------------

    //-- RETORNA DADOS DO PROFESSOR QUE REALIZOU LOGIN
    public function retornarProfessor($chave = array())
    {
        $retornarDados = $this->API->Post('Professor/Dados/',$chave);

        return $retornarDados;
    }

    //-- LISTA TODOS PROFESSORES ATIVOS NA INSTITUIÇÃO
    public function retornarProfessores($dados = array())
    {
        $retornarDados = $this->API->Post('Professor/ListaProfessores/',$dados);

        return $retornarDados;
    }

    //-- SELECIONA PROFESSOR PELO CÓDIGO
    public function selecionarProfessor($dados = array())
    {
        $retornarDados = $this->API->Post('Professor/ListaProfessor/',$dados);

        return $retornarDados;
    }

    // --  LISTA DE CURSOS POR COORDENADOR
    public function listarCursosCoordenados($chave = array())
    {
        $retornarDados = $this->API->Post('Professor/Cursos/Coordenados/',$chave);
        return $retornarDados;
    }

    // ------------------------------- ALUNO ---------------------------------

    //-- RETORNA ALUNO QUE REALIZOU LOGIN
	public function retornarAluno($chave = array())
	{
		$retornarDados = $this->API->Post("Aluno/Dados/",$chave);

		return $retornarDados;
	}

    //-- RETORNA DADOS DO ALUNO POR CPF
    public function retornaAlunoCPF($dados = array()) {
        $retornarDados = $this->API->Post('Aluno/DadosConsulta/',$dados);

        return $retornarDados;
    }

    //-- DADOS PUBLICOS ALUNO
    public function dadosAlunos($dados)
    {
        $retornarDados = $this->API->Post('Aluno/DadosPublico/', $dados);
        return $retornarDados;
    }

    // --------------------------------- CURSOS -------------------------------
	

    //-- RETORNA DISCIPLINAS DOS PROFESSORES
	public function retornarCursos($dados = array())
	{
		$retornarDados = $this->API->Post('Curso/Cursos_coordenados/',$dados);

		return $retornarDados;
	} 
    
    //-- RETORNA CURSOS
    function listarCursos($dados = array()) {
        $retornarDados = $this->API->Post('Curso/Listar/',$dados);

        return $retornarDados;
    }



    

    

    
}

 ?>