<?php
session_start();

include_once("../stringconexion.inc");  // CORRE EN EL HOSTING  

//$turno_fecha = date("Y-m-d", substr($_GET["fecha"], 0, 10));
$funcion = $_GET["funcion"];
$turno_fecha=Date("Y-m-d");
//$turno_fecha=Date("Y-m-d",strtotime($turno_fecha."- 1 days"));
//echo $turno_fecha;
if ($funcion=="CargarLlamadas") {
    $sql = "SELECT * FROM tblTurnosTelefono WHERE turno_fecha='$turno_fecha' and turno_aceptado=1 ORDER BY turno_hora, turno_aceptado";
    //echo "<br>".$sql;
    $pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);
    $resultado = $pdo->prepare($sql); $resultado->execute();
    //$Ocupado = $resultado->fetchAll();
        
    $i=0;
    while($row=$resultado->fetch()) {
        switch($row["turno_hora"]) {
            case 1: $hora="16:00 - 16:20"; break;
            case 2: $hora="16:20 - 16:40"; break;
            case 3: $hora="16:40 - 17:00"; break;
            case 4: $hora="17:00 - 17:20"; break;
        }
        $i++;
        $datos[$i]['horario']=$hora;
        $datos[$i]['residente']=$row["turno_nombre_residente"];
    }
}

if ($funcion=="CargarVisitas") {
    $sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$turno_fecha' and turno_aceptado=1 ORDER BY turno_hora, turno_aceptado";
    //echo "<br>".$sql;
    $pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);
    $resultado = $pdo->prepare($sql); $resultado->execute();
    //$Ocupado = $resultado->fetchAll();
        
    $i=0;
    while($row=$resultado->fetch()) {
        switch($row["turno_hora"]) {
            case 1: $hora="10:00 - 10:20"; break;
            case 2: $hora="10:20 - 10:40"; break;
            case 3: $hora="10:40 - 11:00"; break;
            case 4: $hora="11:00 - 11:20"; break;
        }
        $i++;
        $datos[$i]['horario']=$hora;
        $datos[$i]['residente']=$row["turno_nombre"];
        $datos[$i]['dni']=$row["turno_dni"];
    }
}

$datos = json_encode($datos);
echo $datos;