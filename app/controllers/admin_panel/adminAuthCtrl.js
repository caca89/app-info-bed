angular
.module('app')
.controller('adminAuthCtrl', adminAuthCtrl)

adminAuthCtrl.$inject = ['$scope',  '$rootScope', '$location', '$timeout', '$localStorage', 'Data'];
function adminAuthCtrl($scope, $rootScope, $location, $timeout, $localStorage, Data) {
	$scope.maintenance = false;
	// return;
	$scope.info 		= {show: false, status_text: ''};
	$scope.login 		= {};

	$scope.doLogin = function (data) {
		$scope.info = {show: true, status_text: 'LOADING'};
		Data.post('admin_panel/auth/login', data).then(function (response) {
			var status_text = typeof response.message!=='undefined' ? response.status_text : 'NO';
			var message 	= typeof response.message!=='undefined' ? response.message : response.statusText;
			var path		= $location.path();

			$scope.info.status_text = status_text;
			$scope.info.message 	= message;

			$scope.login = {};
			
			if(path=='/admin-login') {
				if(response.status == 200 && response.status_text=='OK') {
					$localStorage.$default({
						bed_session_user: response.data,
						bed_logged_in: 1
					});
					
					$timeout(function() {
						$location.path('/admin-panel/dashboard');
						window.location = '#!/admin-panel/dashboard';
					}, 300)
				}
			}
		})
	};

	$timeout(function() { $scope.is_loading_page  = false; }, 300);
}
