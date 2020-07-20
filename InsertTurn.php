<?php
session_start();
$_SESSION['user'] = 'root';
$_SESSION['password'] = '';

$turno_nombre = $_GET["turno_nombre"];
$turno_dni = $_GET["turno_dni"];

$turno_nacimiento = date("Y-m-d", substr($_GET["turno_nacimiento"], 0, 10));
$turno_nacimiento = date("Y-m-d", strtotime($turno_nacimiento));

$turno_direccion = $_GET["turno_direccion"];
$turno_empresa = $_GET["turno_empresa"];
$turno_telefono = $_GET["turno_telefono"];
$turno_cobertura = $_GET["turno_cobertura"];
$turno_afiliado = $_GET["turno_afiliado"];

$turno_aceptado = 1;

$a = $_GET["a"];    // ¿Estuvo en los últimos 14 días fuera de la Provincia de Mendoza?

$b = $_GET["b"];	// ¿Estuvo en contacto con personas que hayan regresado a la Provincia de Mendoza en los últimos 14 días?

echo "<br>Pregunta b:".$b;
if($b=="undefined") { $turno_aceptado = 0; $datos['Mensaje'] = "Debe responder la pregunta B."; }
if($b=="true") { $turno_aceptado = 0; $datos['Mensaje'] = "Debe responder la pregunta BBBB."; }


echo $datos['Mensaje'];
$b_fecha = ''; $b_ciudades=''; $b_lugar=''; $b_escala = ''; $c_cuando='';

if ($b<>"false") {
	$b_ciudades = $_GET["b_ciudades"];
	if (!isset($b_ciudades)) {
		$datos['Mensaje'] = "Debe completar el dato de ciudades visitadas";
		$turno_aceptado = 0;
	}

	$b_fecha = $_GET["b_fecha"];
	if ($b_fecha) {
		$b_fecha = date("Y-m-d", substr($_GET["b_fecha"], 0, 10));
		$b_fecha = date("Y-m-d", strtotime($b_fecha));
	} else {
		$datos['Mensaje'] = "Debe completar el dato de fecha en la que visitó dichas ciudades";
		$turno_aceptado = 0;
	}

	$b_lugar = $_GET["b_lugar"];
	if (!isset($b_lugar)) {
		$datos['Mensaje'] = "Debe completar el dato de lugares visitados";
		$turno_aceptado = 0;
	}

	$b_escala = $_GET["b_escala"];
	if (!isset($b_escala)) {
		$datos['Mensaje'] = "Debe completar el dato de las escalas en lugares visitados";
		$turno_aceptado = 0;
	}
}

$c = $_GET["c"]; // ¿Ha estado en contacto con personas con un diagnóstico confirmado de coronavirus en los últimos 14 días?
if ($c<>"false") {
	$c_cuando = $_GET["c_cuando"];
	/*if (isset($c_cuando)) {
		$c_cuando = date("Y-m-d", substr($_GET["c_cuando"], 0, 10));
		$c_cuando = date("Y-m-d", strtotime($c_cuando));
		if ((date("Y-m-d", strtotime($c_cuando . "+ 14 days")) < date("Y-m-d"))) {
			echo "se rechaza por; " . date("Y-m-d", strtotime($c_cuando . "+ 14 days")) > date("Y-m-d");
			$datos['Mensaje'] = "No puede realizar la visita por estar en contacto con personas que han tenido COVID-19";
			$turno_aceptado = 0;
		}
	} else {*/
		//$datos['Mensaje'] = "Debe completar el cuadro de cuándo ha estado en contacto con personas que han tenido COVID-19";
		$datos['Mensaje'] = "No puede realizar la visita por estar en contacto con personas que han tenido COVID-19";
		$turno_aceptado = 0;
	//}
}

$d = $_GET["d"]; //¿Usted o alguna persona de su grupo conviviente o persona de trato frecuente, presentó en los últimos 14 días
if ($d) {
	$datos['Mensaje'] = "No puede realizar la visita por presentar síntomas compatibles con COVID-19";
	$turno_aceptado = 0;
}

$acepto = (bool) $_GET["acepto"];

if (!$acepto) {
	$datos['Mensaje'] = "Debe aceptar los términos de la declaración jurada";
	$turno_aceptado = 0;
}

$turno_fecha = date("Y-m-d", substr($_GET["turno_fecha"], 0, 10));
$turno_fecha = date("Y-m-d", strtotime($turno_fecha));

$turno_hora = $_GET["turno_hora"];



if($b<>"true" && $c=="false" && $d==0 && $acepto=="true" && $turno_fecha<>"NaN" && $turno_hora<>"undefined") {
	echo "<br>Turno aceptado:".$turno_aceptado;
}

if ($turno_aceptado) {
	if (turnoOcupado($turno_fecha, $turno_hora)) {
		$datos['Mensaje'] = "Este dia y horario ya se encuentra reservado";
		$turno_aceptado = 0;
	} else {
		//echo "Controla turno aceptado:$turno_aceptado.";
		$turno_concretado = 0;


		

		$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, d, d_cuando, e, acepto, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,'$c_cuando',$d,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
		echo "<br>" . $sql;

		$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

		$resultado = $pdo->prepare($sql);
		// $resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
		//$datos = $resultado->fetchAll();
		$datos['Mensaje'] = "Turno Aceptado para el dia $turno_fecha a las $turno_hora";
	}
}


$datos = json_encode($datos);
echo $datos;

/*if(turnoFueraFecha($turno_fecha,$turno_hora)) {
	    $datos['Mensaje']="Debe seleccionar un turno con al menos un d&iacute;a de anticipaci&oacute;n";
	}
	*/

function turnoOcupado($Fecha, $Hora)
{
	$sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$Fecha' and turno_hora=$Hora";
	echo "<br>" . $sql;

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

function turnoFueraFecha($Fecha, $Hora)
{
	if (($Fecha) < 0) {
		return true;
	} else {
		return false;
	}
	echo "Fecha";
}
