<?php
session_start();

$_SESSION['user'] = 'root';
$_SESSION['password'] = '';
$turno_fecha = date("Y-m-d", substr($_GET["turno_fecha"], 0, 10));

$sql = "SELECT * FROM tblTurnos WHERE turno_fecha='$turno_fecha' and turno_aceptado=1 ORDER BY turno_hora, turno_aceptado";
//echo "<br>".$sql;
$pdo = new PDO('mysql:host=localhost;dbname=host67_hostal', $_SESSION['user'], $_SESSION['password']);

$resultado = $pdo->prepare($sql); $resultado->execute();
//$Ocupado = $resultado->fetchAll();
$datos[1][0]=1;
$datos[2][0]=2;
$datos[3][0]=3;
$datos[4][0]=4;
$datos[1][1]="10:00 - 10:20 Disponible";
$datos[2][1]="10:20 - 10:40 Disponible"; 
$datos[3][1]="10:40 - 11:00 Disponible"; 
$datos[4][1]="11:20 - 11:40 Disponible"; 

for($i=0 ; $i<4 ; $i++) {
    $row=$resultado->fetch();
    switch ($row["turno_hora"]) {
        case 1: $datos[1][1]="10:00 - 10:20 Reservado"; break;
        case 2: $datos[2][1]="10:20 - 10:40 Reservado"; break;
        case 3: $datos[3][1]="10:40 - 11:00 Reservado"; break;
        case 4: $datos[4][1]="11:20 - 11:40 Reservado"; break;
    }
}
/*
$datos[0][1]=1;
$datos[0][2]=2;
$datos[0][3]=3;
$datos[0][4]=4;
$datos[1][1]="10:00 - 10:20 Disponible";
$datos[1][2]="10:20 - 10:40 Disponible"; 
$datos[1][3]="10:40 - 11:00 Disponible"; 
$datos[1][4]="11:20 - 11:40 Disponible"; 

for($i=0 ; $i<4 ; $i++) {
    $row=$resultado->fetch();
    switch ($row["turno_hora"]) {
        case 1: $datos[1][1]="10:00 - 10:20 Reservado"; break;
        case 2: $datos[1][2]="10:20 - 10:40 Reservado"; break;
        case 3: $datos[1][3]="10:40 - 11:00 Reservado"; break;
        case 4: $datos[1][4]="11:20 - 11:40 Reservado"; break;
    }
}
*/
$datos = json_encode($datos);
echo $datos;

