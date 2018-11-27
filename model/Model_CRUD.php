<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Model_CRUD
{
	private $Model_Database;

	function __construct()
	{
		$this->Model_Database = new Database_Connect;
	}

	public function insert($tabela=null, $campos = null, $valores=null)
	{
		$mult = count($valores);

		$parametro = str_repeat(',?', $mult);
		$parametro = substr($parametro, 1);

		$query = "INSERT INTO $tabela(".implode(',', $campos).")VALUES($parametro)";

		

		$insert = $this->Model_Database->getConnect()->prepare($query);
		$contador = 1;
		foreach ($valores as $params) {
			$insert->bindValue($contador, $params);
			
			if ($contador == $mult) {
				break;
			}else{
				$contador++;
			}
			
		}
		$cadastro = $insert->execute();

		if ($cadastro) {
			return true;
		} else {
			return false;
		}
		

	}

	public function select($sqlQuery)
	{
		$select = $this->Model_Database->getConnect()->prepare($sqlQuery);
		$select->execute();

		$dados = $select->fetchAll(PDO::FETCH_OBJ);

		return $dados;
	}


	public function delete($tabela=null, $campos=null, $parametro=null)
	{
		$mult = count($campos);

		$query = "DELETE FROM $tabela WHERE "; 

		foreach ($campos as $param) {
			$query .= "$param = ? AND "; 
		}

		$query = substr($query,0, -4);

		$delete = $this->Model_Database->getConnect()->prepare($query);
		$contador = 1;

		foreach ($parametro as $valores) {
			$delete->bindValue($contador, $valores);
			
			if ($contador == $mult) {
				break;
			}else{
				$contador++;
			}
		}

		$deletar = $delete->execute();

		if ($deletar) {
			return true;
		}else{
			return false;
		}
	}

	public function update($tabela, $campos, $valores, $parametro=null)
	{
		$mult = count($campos);
		$query = "UPDATE $tabela SET ";

		foreach ($campos as $param) {
			$query .= "$param = ?, ";
		}

		$query = substr($query, 0, -2);
		$query .= " $parametro";
		
		$update = $this->Model_Database->getConnect()->prepare($query);
		$contador = 1;

		foreach ($valores as $valor) {
			$update->bindValue($contador, $valor);

			if ($contador == $mult) {
				break;
			}else{
				$contador++;
			}
		}
                
		$alterar = $update->execute();

		if ($alterar) {
			return true;
		}else{
			return false;
		}
	}
}

?>