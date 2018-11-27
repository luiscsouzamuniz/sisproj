<?php  
include '../Autoload.php';
$Autoload = new Autoload;
$command  = (isset($_REQUEST['command'])) ? $_REQUEST['command'] : false ;
/**
* 
*/
class login
{
	private $Model_Login;
	private $consumo_api;

	function __construct()
	{
		$this->Model_Login = new Model_Login;
		$this->consumo_api = new consumo_api;
	}

	public function logar()
	{
		$ra     = (isset($_POST['ra'])) ? $_POST['ra'] : false; 
		$senha  = (isset($_POST['senha'])) ? $_POST['senha'] : false; 
		$tipo   = (isset($_POST['tipo'])) ? $_POST['tipo'] : false; 
		$filial = '0003';

		if ($this->verificarDados($ra, $senha, $tipo, $filial)) {
			$login = $this->Model_Login->logar();
			
			if ($login) {
				$this->redirLogin($tipo, $login);
				
			}else{
				$_SESSION['mensagem'] = array( 'texto' => 'Usuário não encontrado.', 'classe' => 'danger', 'tipo' => 'Erro!');
				header('location: '.URL);
			}

		}else{
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, preencha todos os campos.', 'classe' => 'danger', 'tipo' => 'Erro!');
			header('location: '.URL);
		}
	}

	private function verificarDados($ra, $senha, $tipo, $filial)
	{
		if ($ra && $senha && $tipo && $filial) {
			$this->Model_Login->setRA($ra);
			$this->Model_Login->setSenha($senha);
			$this->Model_Login->setTipo($tipo);
			$this->Model_Login->setFilial($filial);

			return true;
		}else{
			return false;
		}
	}

	private function redirLogin($tipo, $dados)
	{
		if ($tipo == 'a') {
			$_SESSION['chave_aluno'] = $dados;
			$_SESSION['dados_aluno'] = $this->consumo_api->retornarAluno($_SESSION['chave_aluno']);
			$url = URL.'aluno';
			header('location:'.$url);
		}elseif ($tipo == 'p') {
			$_SESSION['chave_professor'] = $dados;
			$_SESSION['dados_professor'] = $this->consumo_api->retornarProfessor($_SESSION['chave_professor']);
			$url = URL.'admin';
			header('location:'.$url);
		}else{
			$url = URL.'';
			$_SESSION['mensagem'] = array( 'texto' => 'Houve um erro! Tente novamente.', 'classe' => 'danger', 'tipo' => 'Erro!');
			header('location:'.$url);
		}
	}

	public function logout()
	{
		if (isset($_SESSION['chave_professor'])) {
			unset($_SESSION['chave_professor']);
		}

		if (isset($_SESSION['chave_aluno'])) {
			unset($_SESSION['chave_aluno']);
		}

		header('location: '.URL);
	}
}

$login = new login;

$exec = $login->$command();

?>