<?php  

/**
* 
*/
class Model_Login
{
	private $Model_Unisepe;
	private $ra;
	private $senha;
	private $tipo;
	private $filial;

	function __construct()
	{
		$this->Model_Unisepe = new Model_Unisepe;
	}

	public function logar()
	{
		$ra    = $this->getRA();
		$senha = $this->getSenha();
		$tipo  = $this->getTipo();
		$filial = $this->getFilial();

		$login = $this->Model_Unisepe->autenticacao($ra,$senha,$tipo,$filial);

		if (!isset($login['erro'])) {
			return $login;
		}else{
			return false;
		}
		
	}

	public function teste()
	{
		print_r($_POST);
	}

	//GET
	public function getRA()
	{
		return $this->ra;
	}

	public function getSenha()
	{
		return $this->senha;
	}

	public function getTipo()
	{
		return $this->tipo;
	}

	public function getFilial()
	{
		return $this->filial;
	}

	//SET

	public function setRA($ra)
	{
		$this->ra = $ra; 
	}

	public function setSenha($senha)
	{
		$this->senha = $senha; 
	}

	public function setTipo($tipo)
	{
		$this->tipo = $tipo; 
	}

	public function setFilial($filial)
	{
		$this->filial = $filial; 
	}

	

}

?>