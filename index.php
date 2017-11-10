<?php
header("Content-type:application/json");
$conexao = new mysqli('localhost','root','alisson299409','s3');


function get_id($conexao)
{
	$q = $conexao->query("select REPLACE(UUID(),'-','')");
	if($q) {
		$result = $q->fetch_row();
		return $result[0];
	}
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = get_id($conexao);
	$query = "insert into usuario(id, id_char, nome) value(GENERATE_BINARY_UUID('".$id."'),'".$id."', '".$_POST['nome']."')";
	if($conexao->query($query)) {
		$msg = 'Inserido com sucesso';
	}else{
		$msg = 'Erro na inserção';
	}

	echo json_encode($msg);
}else{

	$query = 'select id_char, nome from usuario';
	if(isset($_GET['id']) && !empty($_GET['id'])) 
		$query .= ' where id = GENERATE_BINARY_UUID("'.$_GET['id'].'")';
	
	$q = $conexao->query($query);
	$retorno = [];
	while($item = $q->fetch_all()) {
		$retorno[] = $item;
	}

	echo json_encode($retorno);
}

