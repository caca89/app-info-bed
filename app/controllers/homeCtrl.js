angular
.module('app')
.controller('homeCtrl', homeCtrl)

homeCtrl.$inject = ['$scope',  '$rootScope', '$location', '$timeout'];
function homeCtrl($scope, $rootScope, $location, $timeout) {
	$timeout(function() {$scope.is_loading_page = false;}, 300);
}
