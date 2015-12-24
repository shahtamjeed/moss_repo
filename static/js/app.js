//angular.module('app', [])
//  .controller('getData', ['$scope', '$http', function($scope, $http) {
//    $scope.user    = {};
//    $scope.results = [];
//
//    $scope.search = function(url) {
//      $http.get(url, { params: user },
//        function(response) { $scope.results = response; },
//        function(failure) { console.log("failed :(", failure); });
//    });
//  });


var app = angular.module('app', []);

app.controller('getData', function($scope, $http) {

    $http.get("src/api.php").then(function(response) {
		$scope.results = response.data;

	}, function(failure) {
		console.log(failure);
	});
});
