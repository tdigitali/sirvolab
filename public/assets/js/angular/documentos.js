var app = angular.module('app' , [], function($interpolateProvider) {
    $interpolateProvider.startSymbol('<%');
    $interpolateProvider.endSymbol('%>');
});

app.controller('DocumentosController', function($scope) {
	$scope.documentos = [];
	$scope.deleteFiles = [];

	$scope.agregarArchivo = function(){
		var documento = {numero: $scope.documentos.length};
		$scope.documentos.push(documento);
	};

	$scope.deleteFileServer = function(id){
		$scope.deleteFiles.push(id);
		$("#otherFile" + id).hide();
	};
});