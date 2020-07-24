<?php
session_start();
$_SESSION['user'] = 'root';
$_SESSION['password'] = '';

$turno_nombre = $_GET["turno_nombre"];
$turno_dni = $_GET["turno_dni"];

if ($_GET["turno_nacimiento"] <> "NaN") {
	$turno_nacimiento = $_GET["turno_nacimiento"];
	$turno_nacimiento = date("Y-m-d", substr($turno_nacimiento, 0, 10));
	$turno_nacimiento = date("Y-m-d", strtotime($turno_nacimiento));
} else {
	$turno_nacimiento = '';
}

$turno_direccion = $_GET["turno_direccion"];
$turno_empresa = $_GET["turno_empresa"];
$turno_telefono = $_GET["turno_telefono"];
$turno_cobertura = $_GET["turno_cobertura"];
$turno_afiliado = $_GET["turno_afiliado"];

$turno_aceptado = 1;

$a = $_GET["a"];    // ¿Estuvo en los últimos 14 días fuera de la Provincia de Mendoza?

$b = $_GET["b"];	// ¿Estuvo en contacto con personas que hayan regresado a la Provincia de Mendoza en los últimos 14 días?

if ($b == "undefined") {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe responder la pregunta B.";
}
if ($b == "true") {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Deberá esperar unos días e intente volver a sacar el turno.";
}
$b_fecha = '';
$b_ciudades = '';
$b_lugar = '';
$b_escala = '';
$c_cuando = '';
$c = $_GET["c"];
if ($c == "undefined") {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe responder la pregunta C.";
}
if ($c <> 0) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "No puede realizar la visita por estar en contacto con personas que han tenido COVID-19.";
}
$d = $_GET["d"];
if ($d == "undefined") {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe responder la pregunta D.";
}
if ($d == 1) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "No puede realizar la visita por estar en contacto con personas que han tenido COVID-19";
}
$acepto = $_GET["acepto"];
if ($acepto == "false") {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe aceptar los términos de la declaración jurada";
}
$turno_fecha = $_GET["turno_fecha"];
$fecha_actual = date('Y-m-d');

if (!isset($turno_fecha)) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe seleccionar una fecha para el turno.";
} else {
	if ($_GET["turno_fecha"] <> "NaN") {
		$turno_fecha = date("Y-m-d", substr($_GET["turno_fecha"], 0, 10));
		$turno_fecha = date("Y-m-d", strtotime($turno_fecha));
	} else {
		$datos['Mensaje'] = "Debe seleccionar una fecha para el turno";
	}
}

if ($turno_fecha < $fecha_actual) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Debe seleccionar una fecha posterior a la fecha actual";
}
$turno_hora = $_GET["turno_hora"];
if (turnoOcupado($turno_fecha, $turno_hora)) {
	$turno_aceptado = 0;
	$datos['Mensaje'] = "Este dia y horario ya se encuentra reservado";
}

$b_ciudades = $_GET["b_ciudades"];
if (!isset($_GET["b_fecha"])) {
	$b_fecha = date("Y-m-d", substr($_GET["b_fecha"], 0, 10));
} else {
	$b_fecha = NULL;
}
$b_fecha = date("Y-m-d", strtotime($b_fecha));
$b_lugar = $_GET["b_lugar"];
$b_escala = $_GET["b_escala"];
if (!isset($_GET["c_cuando"])) {
	$c_cuando = date("Y-m-d", substr($_GET["c_cuando"], 0, 10));
} else {
	$c_cuando = NULL;
}
$c_cuando = date("Y-m-d", strtotime($c_cuando));

if ($turno_nombre == "undefined" or $turno_dni == "undefined" or $turno_direccion == "undefined" or $turno_empresa == "undefined" or $turno_telefono == "undefined" or $turno_cobertura == "undefined" or $turno_afiliado == "undefined" or !isset($turno_nacimiento) or $turno_nacimiento == "NaN") {
	$datos['Mensaje'] = "Faltan completar datos!";
	$turno_aceptado = 0;
}

$turno_concretado = 0;
// Si el turno tuvo problemas. Igual guarda los datos
if ($turno_aceptado == 0) {
	$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, c_cuando, acepto, d, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,'$c_cuando',$d,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

	$resultado = $pdo->prepare($sql);
	$resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	$datos['Mensaje'] = "El Turno NO ha sido aceptado";
}

// Si el turno no tiene problemas. Se guarda el turno.
if ($turno_aceptado == 1) {

	$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, c_cuando, acepto, d, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,'$c_cuando',$d,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

	$resultado = $pdo->prepare($sql);
	$resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	//$datos = $resultado->fetchAll();
	switch ($turno_hora) {
		case 1: $horario="10:00 - 10:20"; break;
		case 2: $horario="10:20 - 10:40"; break;
		case 3: $horario="10:40 - 11:00"; break;
		case 4: $horario="11:20 - 11:40";  break;
	}
	$f = date("d-m-Y", $turno_fecha);
	$datos['Mensaje'] = "Turno Aceptado para el dia $f en el horario de $horario";
}


$datos = json_encode($datos);
echo $datos;

function turnoOcupado($Fecha, $Hora)
{
	$sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$Fecha' and turno_hora=$Hora and turno_aceptado=1";
	//echo $sql;
	$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

	$resultado = $pdo->prepare($sql);
	$resultado->execute();
	$Ocupado = $resultado->fetchAll();
	if (count($Ocupado)) {
		return true;
	} else {
		return false;
	}
}