<?php  

/**
* Classe de carregamento das outras classes presentes no sistema.
* @author Luis Carlos de Souza Muniz
*/

class Autoload
{
	
	function __construct()
	{
		spl_autoload_register(array($this, 'carregar_classes'));
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		define('BASE', __DIR__);
		define('URL','/interno/sisproj/');
		$date = '';
		if (date('m') <= 6) {
			$date = date('Y').'1';
		}else{
			$date = date('Y').'2';
		}
		define('AASS', $date);
		
	}

	private function carregar_classes($class)
	{
		if (file_exists(__DIR__.'/model/'.$class.'.php')) {
			include __DIR__.'/model/'.$class.'.php';
		}elseif (file_exists(__DIR__.'/controller/'.$class.'.php')){
			include __DIR__.'/controller/'.$class.'.php';
		}
	}

	public function sessionAluno()
	{
		if (!isset($_SESSION['chave_aluno'])) {
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, entre com o login e senha.', 'classe' => 'danger', 'tipo' => 'Aviso!');
			header('location: '.URL);
		}
	}

	public function sessionProfessor()
	{
		if (!isset($_SESSION['chave_professor'])) {
			$_SESSION['mensagem'] = array( 'texto' => 'Por favor, entre com o login e senha.', 'classe' => 'danger', 'tipo' => 'Aviso!');
			header('location: '.URL);
		}
	}
}

?>