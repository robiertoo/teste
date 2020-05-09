<?php 

namespace Classes;

class Padrao
{
	public function buscar($pdo, $tabela, $campos, $filtros = NULL, $grupos = NULL)
	{
		$sqlCampos = $sqlFiltros = $sqlGrupos = "";

		foreach ($campos as $campo) $sqlCampos .= ", $campo";

		if($filtros != NULL){
			foreach ($filtros as $filtro => $valor) $sqlFiltros .= " AND $filtro = :$filtro";
		}

		if($grupos != NULL){
			$sqlGrupos .= " GROUP BY";
			foreach ($grupos as $grupo) $sqlGrupos .= " $grupo,";
			$sqlGrupos = substr($sqlGrupos, 0, -1); 
		}

		$sql = "SELECT 1 $sqlCampos FROM $tabela WHERE 1 = 1 $sqlFiltros $sqlGrupos";
		$buscar = $pdo->prepare($sql);

		if($filtros != NULL){
			foreach ($filtros as $filtro => $valor)	$buscar->bindValue(":$filtro", $valor);
		}

		$buscar->execute();
		return $buscar;
	}

	public function salvar($pdo, $tabela, $campos)
	{
		$sqlCampos = $sqlValores = "";

		foreach ($campos as $campo => $valor) {
			$sqlCampos .= "$campo, ";
			$sqlValores .= ":$campo, ";
		}
		$sqlCampos = substr($sqlCampos, 0, -2);
		$sqlValores = substr($sqlValores, 0, -2);

		$sql = "INSERT INTO $tabela ($sqlCampos) VALUES ($sqlValores)";
		
		$salvar = $pdo->prepare($sql);

		foreach ($campos as $campo => $valor) $salvar->bindValue(":$campo", $valor);

		$salvar->execute();
		return $salvar;
	}

	public function atualizar($pdo, $tabela, $campos, $filtros = NULL)
	{
		$sqlCampos = $sqlValores = $sqlFiltros = "";

		foreach ($campos as $campo => $valor) {
			$sqlCampos .= "$campo, ";
			$sqlValores .= "$valor, ";
		}
		$sqlCampos = substr($sqlCampos, 0, -2);
		$sqlValores = substr($sqlValores, 0, -2);

		if($filtros != NULL){
			$sqlFiltros .= "WHERE ";
			foreach ($filtros as $filtro => $valor) {
				$sqlFiltros .= "$filtro = $valor";
			}
		}

		$sql = "UPDATE $tabela SET $sqlCampos = $sqlValores $sqlFiltros";
		$atualizar = $pdo->prepare($sql);
		$atualizar->execute();
		return $atualizar;
	}

	public function apagar($pdo, $tabela, $filtros)
	{
		$sqlFiltros = "";

		foreach ($filtros as $filtro => $valor) $sqlFiltros .= "$filtro = :$filtro, ";

		$sqlFiltros = substr($sqlFiltros, 0, -2);

		$sql = "DELETE FROM $tabela WHERE $sqlFiltros";
		$apagar = $pdo->prepare($sql);

		foreach ($filtros as $filtro => $valor) $apagar->bindValue(":$filtro", $valor);

		$apagar->execute();
		return $apagar;
	}
}
