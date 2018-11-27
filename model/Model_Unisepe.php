<?php

class Model_Unisepe {


	protected $api_url;
	
	function __construct(){
		$this->api_url = "############";
	}

	//------------------------------------------------------------ 
	//                      AUTENTICACAO          
	//------------------------------------------------------------
	public function Autenticacao($login,$senha,$tipo,$filial){	

		$api_url = $this->api_url;	
			
		$dados = array();
		$dados['login'] =  $login;
		$dados['senha'] =  $senha;
		$dados['tipo'] =  $tipo;
		$dados['filial'] = $filial;
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url."Autenticacao");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);	
		$result = json_decode(curl_exec($ch), true);	

		return $result;		
	}

	//------------------------------------------------------------ 
	//                       METHOD GET            
	//------------------------------------------------------------
	public function Get($recurso){

		$api_url = $this->api_url;	

		$ch = curl_init($api_url.$recurso);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = json_decode(curl_exec($ch),true);
		   
		return ($result);
	}
	 
	//------------------------------------------------------------ 
	//                       METHOD POST           
	//------------------------------------------------------------
	public function Post($recurso,$dados = array()){
		
		$api_url = $this->api_url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $api_url.$recurso);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $dados);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = json_decode(curl_exec($ch),true);	
		  
		return ($result);  
	}
	 
	//------------------------------------------------------------ 
	//                       METHOD PUT           
	//------------------------------------------------------------
	public function Put($recurso, $dados = array()){
	  	
	  	$api_url = $this->api_url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$api_url.$recurso);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = json_decode(curl_exec($ch),true);

		return $result;	 

	}
	
	//------------------------------------------------------------ 
	//                       METHOD DELETE            
	//------------------------------------------------------------
	public function Delete($recurso){

		$api_url = $this->api_url;	

		$ch = curl_init();
		$ch = curl_init($api_url.$recurso);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = json_decode(curl_exec($ch),true);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");		
		   
		return ($result);
	}




	/*
	public function Post($dados = array,$url,$metodo){		
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($dados));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);	

		return $result;			
	}


	function AtualizaSenha($chave,$cpf,$dtnasc,$senha,$resenha){		
		
		$postfields = array();
		$postfields['chave'] =  $chave;
		$postfields['cpf'] =  $cpf;
		$postfields['dtnasc'] =  $dtnasc;
		$postfields['senha'] = $senha;
		$postfields['resenha'] = $resenha;
		$method = "PUT";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"http://www.fvr.edu.br/unisepe/api/Senha/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$result = curl_exec($ch);	

		return $result;		
}
	*/

}
