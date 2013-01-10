<?php
session_cache_expire(2880);
session_start();
$musicxUrl ='http://www.musicx.com.br';
//$usuario_idface = $_SESSION["user_facebookid"];
//$usuario_idface = 100003304899793; //Cliff Burton
//$usuario_idface = 100000224555909; //Fábio Ferreira
$usuario_idface = 100000648434005; // Felipe Pires




if ($usuario_idface == NULL)
{
	header('Location: '.$musicxUrl.'/home/');	
	exit;
}

header("Content-Type: text/html; charset=utf-8",true);

// COMEÇA A CALCULAR O TEMPO DE EXECUÇÃO DA PAGINA
$timecomeco = microtime(true);


// define uma acao default
if (!$_GET['modulo']) {
    // isso implica que todos os controllers 
    // terao que ter um metodo chamado acaoPadrao
    $modulo = 'dashboard';
}else{
	$modulo = $_GET['modulo'];
}
require('./config/chamadas.php');



$conexao = mysql_connect('www.musicx.fm','musicx5_site','XmusicxX4312') or die (mysql_error());

$banco = mysql_select_db('musicx5_site', $conexao) or die(mysql_error());

$query = mysql_query("SELECT id FROM USUARIOS WHERE user_facebookid='$usuario_idface'") or die(mysql_error());
 
$array = mysql_fetch_array($query);

$usuario_id = $array["id"];
$_SESSION['usuario_id'] = $usuario_id;
/*-----------------------------------------------------------------*/
#$usuario_id = 1673996; //1;
/*-----------------------------------------------------------------*/

// instancia o modulo
eval('$instancia = new ' . $modulo . 'Controller('.$usuario_id.');');


if (!$_GET['acao']) {

}else{
	$acao = $_GET['acao'];
	// agora eu executo o metodo passado via url
	eval('$instancia->' . $acao.'();');
}


mysql_query("INSERT INTO LOG_DESEMPENHO (iduser, pag, ip, execucao, data) VALUES ('$usuario_id', '".$_SERVER['QUERY_STRING']."', '".$_SERVER['REMOTE_ADDR']."', '".(microtime(true)-$timecomeco)."','".$config['data']."')");
/*



echo '<!--
Musicx.FM
Ricardo Rebello Sierra
Tempo de Execução no Servidor em s:'.(microtime(true)-$timecomeco).'
-->';
*/
?>