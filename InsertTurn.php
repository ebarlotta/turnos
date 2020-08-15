<?php
session_start();

include_once("stringconexion.inc");  // CORRE EN EL HOSTING    

$b_fecha = 1;
$b_ciudades = 0;
$b_lugar = 0;
$b_escala = 0;
//$c_cuando = '';

$turno_nombre = $_GET["turno_nombre"];
$turno_dni = $_GET["turno_dni"];
$turno_nacimiento = $_GET["turno_nacimiento"];
$turno_direccion = $_GET["turno_direccion"];
$turno_empresa = $_GET["turno_empresa"];
$turno_telefono = $_GET["turno_telefono"];
$turno_cobertura = $_GET["turno_cobertura"];
$turno_afiliado = $_GET["turno_afiliado"];
$turno_fecha = $_GET["turno_fecha"];
$turno_hora = $_GET["turno_hora"];
$turno_fecha = date("Y-m-d", substr($turno_fecha, 0, 10));

$turno_aceptado = 1;

$a = $_GET["a"];    // ¿Estuvo en los últimos 14 días fuera de la Provincia de Mendoza?
$b = $_GET["b"];	// ¿Estuvo en contacto con personas que hayan regresado a la Provincia de Mendoza en los últimos 14 días?
$c = $_GET["c"];
$d = $_GET["d"];
$acepto = $_GET["acepto"];

$fecha_actual = date('Y-m-d');

//echo "Fecha turno:" . $turno_fecha;
//echo "Fecha actual:" . $fecha_actual;
//		$turno_fecha = date("Y-m-d", substr($_GET["turno_fecha"], 0, 10));
//		$turno_fecha = date("Y-m-d", strtotime($turno_fecha));
//echo "Fecha Acrual" . $fecha_actual;
//echo "Fecha turno_fecha" . $turno_fecha;
if ($turno_fecha < $fecha_actual) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe seleccionar una fecha posterior a la fecha actual";
}

if (turnoOcupado($turno_fecha, $turno_hora)) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Este dia y horario ya se encuentra reservado";
}

if($_GET["b_ciudades"]<>"undefined") { $b_ciudades = $_GET["b_ciudades"]; } else { $b_ciudades=''; }
if($_GET["b_lugar"]<>"undefined") {$b_lugar = $_GET["b_lugar"];} else { $b_lugar=''; }
if($_GET["b_escala"]<>"undefined")  { $b_escala = $_GET["b_escala"]; } else { $b_escala=''; }
$c_cuando = $_GET["c_cuando"];

$turno_concretado = 0;
// Si el turno tuvo problemas. Igual guarda los datos
if ($turno_aceptado==0) {
	$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, c_cuando, acepto, d, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,'$c_cuando',$d,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

	$resultado = $pdo->prepare($sql);
	$resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	$datos['Mensaje'] = "El Turno NO ha sido aceptado";
}

// Si el turno no tiene problemas. Se guarda el turno.
if ($turno_aceptado == 1) {

	$turno_nacimiento = date("Y-m-d", substr($turno_nacimiento, 0, 9));
	//echo $_GET["turno_fecha"];
	//$turno_fecha = date("Y-m-d", substr($turno_fecha, 0, 9));
	if (!$b_fecha) { $b_fecha =''; } else {	$b_fecha = date("Y-m-d", substr($b_fecha, 0, 9)); }
	if ($c_cuando=="NaN") { $c_cuando =''; } else{ $c_cuando = date("Y-m-d", substr($c_cuando, 0, 9)); }
	
	$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, c_cuando, acepto, d, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,'$c_cuando',$d,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);
	//echo $sql;
	$resultado = $pdo->prepare($sql);
	$resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	switch ($turno_hora) {
		case 1: $horario="10:00 - 10:20"; break;
		case 2: $horario="10:20 - 10:40"; break;
		case 3: $horario="10:40 - 11:00"; break;
		case 4: $horario="11:20 - 11:40";  break;
	}
	$datos['Mensaje'] = "Turno Aceptado para el dia ". substr($turno_fecha,8,2)."-".substr($turno_fecha,5,2)."-".substr($turno_fecha,0,4)." en el horario de $horario";
}

//$datos = json_encode($datos);
echo $datos['Mensaje'];

function turnoOcupado($Fecha, $Hora)
{
	$sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$Fecha' and turno_hora=$Hora and turno_aceptado=1";
	//echo $sql;
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

	$resultado = $pdo->prepare($sql);
	$resultado->execute();
	$Ocupado = $resultado->fetchAll();
	//echo "Esta ocupado? ". count($Ocupado); 
	if (count($Ocupado)) {
		return true;
	} else {
		return false;
	}
}