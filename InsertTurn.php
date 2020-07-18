	<?php
	
	$turno_nombre=$_GET["turno_nombre"];
    $turno_dni=$_GET["turno_dni"];
    
	$turno_nacimiento=Date("Y-m-d",substr($_GET["turno_nacimiento"],0,10));
	$turno_nacimiento = date("Y-m-d",strtotime($turno_nacimiento."+ 1 days")); 
	
	$turno_direccion=$_GET["turno_direccion"];
	$turno_empresa=$_GET["turno_empresa"];
	$turno_telefono=$_GET["turno_telefono"];
	$turno_cobertura=$_GET["turno_cobertura"];
	$turno_afiliado=$_GET["turno_afiliado"];
	$a=$_GET["a"];
	$b=$_GET["b"];
	$b_ciudades=$_GET["b_ciudades"];
	
	$b_fecha=date("Y-m-d",substr($_GET["b_fecha"],0,10));
	$b_fecha=date("Y-m-d",strtotime($b_fecha."+ 1 days"));
	
	$b_lugar=$_GET["b_lugar"];
	$b_escala=$_GET["b_escala"];
	$c=$_GET["c"];
	$d=$_GET["d"];
	
	$d_cuando=date("Y-m-d",substr($_GET["d_cuando"],0,10));
	$d_cuando=date("Y-m-d",strtotime($d_cuando."+ 1 days"));
	
	$e=$_GET["e"];
	$acepto=$_GET["acepto"];
	
	$turno_fecha=date("Y-m-d",substr($_GET["turno_fecha"],0,10));
	$turno_fecha=date("Y-m-d",strtotime($turno_fecha."+ 1 days"));
	
	$turno_hora=$_GET["turno_hora"];
	
	$sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, d, d_cuando, e, acepto, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,$d,'$d_cuando',$e,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	echo $sql;
	
	include_once("stringconexion.inc");
	$resultado = $GLOBALS['pdo']->prepare($sql);
	//$resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	$datos = $resultado->fetchAll();
	$datos['Mensaje2'] = $sql;
	
	$datos = json_encode($datos);
	
	echo $datos;
	