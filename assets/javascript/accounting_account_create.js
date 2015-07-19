/**
 * Form Controller (AngularJS Based)
 */
var app = angular.module('accountCreateApp', []);

app.controller("FormController", ['$scope', '$http', function($scope, $http) {
	
	// Variables
	$scope.parent_account = null;
	
	// Get the account information when changed
	$scope.$watch('parent_account', function(value) {
		$http.get('/accounting/account/get-next-available-number', {
			params: { parentid: $scope.parent_account }
		})
		.success(function(data, status, headers, config) { 
			console.log('success', data);
			$scope.account_number = 'success';
		})
		.error(function(data, status, headers, config) {
            console.log('error', data, status, headers, config);
        });
	}); 
	
	
	
}]);