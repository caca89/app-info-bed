angular
.module('app')
.controller('headerAdminCtrl', headerAdminCtrl);

headerAdminCtrl.$inject = ['$scope', '$localStorage', '$location'];
function headerAdminCtrl($scope, $localStorage, $location) {
	$scope.$storage = $localStorage.bed_session_user || false;

	$scope.doLogout = function() {
		var conf = confirm('Exit?');
		if(conf) {
		  	$localStorage.$reset();
		  	$location.path('/admin-login');
		  	window.location = '#!/admin-login';
		}
	}
}