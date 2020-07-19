	<?php
	session_start();
	$_SESSION['user']='host67';
	$_SESSION['password']='h50RV267';
	
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
	
	$turno_aceptado=1;
	
	//echo "Turno aceptado:$turno_aceptado";
	//echo "<br>Fecha b_fecha:" . date("Y-m-d",strtotime($b_fecha."+ 14 days"));
	//echo "<br>Fecha Actual:" . date("Y-m-d");
	//echo "<br>Fecha:" . (date("Y-m-d",strtotime($b_fecha."+ 14 days")) > date("Y-m-d"));
	//echo "<br>$b";
	
	if($b) {
	    if((date("Y-m-d",strtotime($b_fecha."+ 14 days")) < date("Y-m-d"))) { 
	        echo "se rechaza por; " . date("Y-m-d",strtotime($b_fecha."+ 14 days")) > date("Y-m-d") ;
	        $turno_aceptado=0; 
	    }
	}
	
	if($c) {
	    $turno_aceptado=0;
	}
	
	if($d) {
	    $turno_aceptado=0;
	}
	
	if($c) {
	    $turno_aceptado=0;
	}
	
	if(!$acepto) {
	    $turno_aceptado=0;
	}
	
	if(turnoOcupado($turno_fecha,$turno_hora)) {
	    $datos['Mensaje']="Este dia y horario ya se encuentra reservado";
	    $datos = json_encode($datos);
	    echo $datos;
	} else { 
	    echo "Controla turno aceptado:$turno_aceptado.";
	    if($turno_aceptado) {
	        $sql = "INSERT INTO tblTurnos (turno_nombre, turno_dni, turno_nacimiento, turno_direccion, turno_empresa, turno_telefono, turno_cobertura, turno_afiliado, a, b, b_ciudades, b_fecha, b_lugar, b_escala, c, d, d_cuando, e, acepto, turno_fecha, turno_hora, turno_aceptado, turno_concretado) VALUES ( '$turno_nombre','$turno_dni','$turno_nacimiento','$turno_direccion','$turno_empresa','$turno_telefono','$turno_cobertura', '$turno_afiliado',$a,$b,'$b_ciudades','$b_fecha','$b_lugar','$b_escala',$c,$d,'$d_cuando',$e,$acepto,'$turno_fecha','$turno_hora',$turno_aceptado,$turno_concretado)";
	        //echo $sql;
	        	        
	        $pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);
	        
	        $resultado = $pdo->prepare($sql);
	        // $resultado->execute();                                ////  COMENTADO PARA QUE NO TENGA EFECTO
	        //$datos = $resultado->fetchAll();
	        $datos['Mensaje'] = "Turno Aceptado para el dia $turno_fecha a las $turno_hora" ;
	        //echo "datos".$datos;
	        $rows=json_encode($datos);
	        echo $rows;
	    }
	}
	
	/*if(turnoFueraFecha($turno_fecha,$turno_hora)) {
	    $datos['Mensaje']="Debe seleccionar un turno con al menos un d&iacute;a de anticipaci&oacute;n";
	}
	*/

	function turnoOcupado($Fecha, $Hora) {
        $sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$Fecha' and turno_hora=$Hora";
        //echo $sql."<br>";
        
        $pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

        $resultado = $pdo->prepare($sql); $resultado->execute();
        $Ocupado = $resultado->fetchAll();
        //echo "<br>Cantidad: " . count($Ocupado);
        //echo "Ocupado:".$Ocupado['id']."<br>";
        //$rows=json_encode($Ocupado);
        //echo "<br>".$rows;
        
        //echo "Filas:". $rows["id"];
        if (count($Ocupado)) { return true; } else { return false;}
	}
	
	function turnoFueraFecha($Fecha, $Hora) {
	    if(($Fecha)<0) { return true; } else { return false;}
	    echo "Fecha";
	}