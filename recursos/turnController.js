var misDatos = angular.module('datosApp', []);

misDatos.controller('turnController', function($scope, $http) {

    // CONTROLADOR DE TURNOS
    //--------------------------------

    $scope.InsertTurn = function() {

        $scope.turno_nacimiento = new Date(document.getElementById('turno_nacimiento').value).getTime(); //El elemento necesita  ng-model y id
        $scope.b_fecha = new Date(document.getElementById('b_fecha').value).getTime();
        $scope.c_cuando = new Date(document.getElementById('c_cuando').value).getTime();
        $scope.turno_fecha = new Date(document.getElementById('turno_fecha').value).getTime();

        $http.get('InsertTurn.php' + '?turno_nombre=' + $scope.turno_nombre +
                '&turno_dni=' + $scope.turno_dni +
                '&turno_nacimiento=' + $scope.turno_nacimiento +
                '&turno_direccion=' + $scope.turno_direccion +
                '&turno_empresa=' + $scope.turno_empresa +
                '&turno_telefono=' + $scope.turno_telefono +
                '&turno_cobertura=' + $scope.turno_cobertura +
                '&turno_afiliado=' + $scope.turno_afiliado +
                '&a=' + $scope.a +
                '&b=' + $scope.b +
                '&b_ciudades=' + $scope.b_ciudades +
                '&b_fecha=' + $scope.b_fecha +
                '&b_lugar=' + $scope.b_lugar +
                '&b_escala=' + $scope.b_escala +
                '&c=' + $scope.c +
                '&c_cuando=' + $scope.c_cuando +
                '&d=' + $scope.d +
                '&acepto=' + $scope.acepto +
                '&turno_fecha=' + $scope.turno_fecha +
                '&turno_hora=' + $scope.turno_hora)
            .then(function(datos) {
                //$scope.init();
            });
    }

});