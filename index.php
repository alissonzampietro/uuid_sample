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

function get_user($id = NULL, $conexao)
{
	$query = 'select id_char, nome from usuario';

	if($id!=NULL) {
		$query .= ' where id = GENERATE_BINARY_UUID("'.$id.'")';
	}else{
		if(isset($_GET['id']) && !empty($_GET['id'])) 
			$query .= ' where id = GENERATE_BINARY_UUID("'.$_GET['id'].'")';
	}

	$q = $conexao->query($query);
	$usuarios = [];
	while($item = $q->fetch_all()) {
		$usuarios[] = $item;
	}
	return $usuarios;
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
	$id = get_id($conexao);
	$query = "insert into usuario(id, id_char, nome) value(GENERATE_BINARY_UUID('".$id."'),'".$id."', '".$_POST['nome']."')";
	if($conexao->query($query)) {
		$msg = get_user($id, $conexao);
	}else{
		$msg = 'Erro na inserção';
	}

	echo json_encode($msg);
}else{
	$usuario = get_user(NULL, $conexao);
	echo json_encode($usuario);
}

