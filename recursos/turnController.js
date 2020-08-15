var misDatos = angular.module('datosApp', []);

misDatos.controller('turnController', function($scope, $http) {

    // CONTROLADOR DE TURNOS
    //--------------------------------

    $scope.InsertTurn = function() {

        // Controla la fecha de nacimiento
        if (!$scope.nacimiento) { return $scope.Mensaje = "Faltan datos ingresar la fecha de nacimiento"; }
        // Controla la fecha del turno
        if (!$scope.fecha) { return $scope.Mensaje = "Falta ingresar la fecha de solicitud del turno"; }
        // Controla que todos los campos estén completos
        if (!$scope.nombre) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como el nombre"; }
        // Si elije como TRUE la pregunta b, comienza a evaluar
        if (!$scope.dni) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como el DNI"; }
        if (!$scope.direccion) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como la dirección"; }
        if (!$scope.empresa) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como la empresa donde trabaja o desde casa"; }
        if (!$scope.telefono) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como el Teléfono"; }
        if (!$scope.cobertura) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como la cobertura médica o Ninguna"; }
        console.log(!$scope.afiliado);
        if (!$scope.afiliado) { return $scope.Mensaje = "Debe completar todos los datos marcados con asterisco tal como el número de afiliado o cero"; }

        if ($scope.b) {
            if ($scope.b_ciudades.length()) { return $scope.Mensaje = "Debe completar las ciudades que visitó."; }
            if ($scope.b_lugar.length()) { return $scope.Mensaje = "Debe completar el lugar que visitó."; }
            if ($scope.b_escala.length()) { return $scope.Mensaje = "Debe completar las escalas que visitó."; }
            if (!$scope.b_fecha) { return $scope.Mensaje = "Falta ingresar la fecha de finalización del viaje"; }
            $scope.Aprovar = 0;
            return $scope.Mensaje = "No puede realizar la visita por ser riesgoso.";
        }
        if ($scope.c) {
            if (!$scope.c_cuando) { return $scope.Mensaje = "Falta ingresar la fecha de la pregunta C)"; }
            $scope.Aprovar = 0;
            return $scope.Mensaje = "No puede realizar la visita por ser riesgoso. Espere unos días y vuelva a intentarlo";

        }
        if ($scope.d) {
            $scope.Aprovar = 0;
            $scope.Mensaje = "No puede realizar la visita por presentar síntomas similares a COVID-19";
            console.log($scope.Mensaje);
            return $scope.Mensaje;
        }
        if (!$scope.acepto) {
            $scope.Aprovar = 0;
            return $scope.Mensaje = "Debe aceptar la declaración jurada.";
        }
        if (!$scope.hora) {
            $scope.Aprovar = 0;
            return $scope.Mensaje = "Falta ingresar la hora del del turno.";
        }

        //$scope.b_fecha = new Date(document.getElementById('b_fecha').value).getTime();
        //$scope.c_cuando = new Date(document.getElementById('c_cuando').value).getTime();
        var nacimiento = Date.parse($scope.nacimiento);
        var b_fecha = Date.parse($scope.b_fecha);
        var c_cuando = Date.parse($scope.c_cuando);
        var fecha = Date.parse($scope.fecha);

        $http.get('InsertTurn.php' + '?turno_nombre=' + $scope.nombre +
                '&turno_dni=' + $scope.dni +
                '&turno_nacimiento=' + nacimiento +
                '&turno_direccion=' + $scope.direccion +
                '&turno_empresa=' + $scope.empresa +
                '&turno_telefono=' + $scope.telefono +
                '&turno_cobertura=' + $scope.cobertura +
                '&turno_afiliado=' + $scope.afiliado +
                '&a=' + $scope.a +
                '&b=' + $scope.b +
                '&b_ciudades=' + $scope.b_ciudades +
                '&b_fecha=' + b_fecha +
                '&b_lugar=' + $scope.b_lugar +
                '&b_escala=' + $scope.b_escala +
                '&c=' + $scope.c +
                '&c_cuando=' + c_cuando +
                '&d=' + $scope.d +
                '&acepto=' + $scope.acepto +
                '&turno_fecha=' + fecha +
                '&turno_hora=' + $scope.hora)
            .then(function(datos) {
                console.log(datos.data);
                $scope.CargarHorarios();
                $scope.Mensaje = datos.data;
            });
    }

    $scope.CargarHorarios = function() {
        fecha = new Date(document.getElementById('fecha').value).getTime();
        $http.get('recursos/DevolverDatos.php' + '?fecha=' + fecha)
            .then(function(datos) {
                $scope.horarios = datos.data;
                //$scope.Mensaje = datos.data.Mensaje;
                //console.log(datos.data);
            });
    }

});