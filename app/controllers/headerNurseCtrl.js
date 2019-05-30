angular
.module('app')
.controller('headerNurseCtrl', headerNurseCtrl);

headerNurseCtrl.$inject = ['$scope', '$localStorage', '$location', 'loginService'];
function headerNurseCtrl($scope, $localStorage, $location, loginService) {
	$scope.$storage = $localStorage.bed_session_user || false;
	$scope.doLogout = function() {
		var conf = confirm('Exit?');
		if(conf) {
		  	$localStorage.$reset();
		  	$location.path('/nurse-login');
		  	window.location = '#!/nurse-login';
		}
	}
}