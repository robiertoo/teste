<?php 

namespace Classes;

class Padrao
{
	public function buscar($pdo, $tabela, $campos, $filtros = NULL, $grupos = NULL)
	{
		$sql = "SELECT 1";

		foreach ($campos as $campo) $sql .= ", $campo";

		$sql .= " FROM $tabela WHERE 1 = 1";

		if($filtros != NULL){
			foreach ($filtros as $filtro => $valor) $sql .= " AND $filtro = :$filtro";
		}

		if($grupos != NULL){
			$sql .= " GROUP BY";
			foreach ($grupos as $grupo) $sql .= " $grupo,";
			$sql = substr($sql, 0, -1); 
		}

		$buscar = $pdo->prepare($sql);

		if($filtros != NULL){
			foreach ($filtros as $filtro => $valor)	$buscar->bindValue(":$filtro", $valor);
		}

		$buscar->execute();
		return $buscar;
	}

	public function salvar($pdo, $tabela, $campos)
	{
		$sql = "INSERT INTO $tabela (";

		foreach ($campos as $campo => $valor) {
			$sql .= "$campo, ";
		}
		$sql = substr($sql, 0, -2);
		$sql .= ") VALUES (";

		foreach ($campos as $campo => $valor) {
			$sql .= ":$campo, "; 
		}

		$sql = substr($sql, 0, -2);
		$sql .= ")";
		$salvar = $pdo->prepare($sql);

		foreach ($campos as $campo => $valor) {	
			$salvar->bindValue(":$campo", $valor);			
		}

		$salvar->execute();
		return $salvar;
	}
}
