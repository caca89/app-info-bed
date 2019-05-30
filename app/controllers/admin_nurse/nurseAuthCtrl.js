angular
.module('app')
.controller('nurseAuthCtrl', nurseAuthCtrl)

nurseAuthCtrl.$inject = ['$scope',  '$rootScope', '$location', '$timeout', '$localStorage', 'Data'];
function nurseAuthCtrl($scope, $rootScope, $location, $timeout, $localStorage, Data) {
	$scope.maintenance = false;
	// $scope.is_loading_page = false;
	// return;
	$scope.info 		= {show: false, status_text: ''};
	$scope.login 		= {};
	$scope.list_rooms 	= [];

	Data.post('admin_nurse/auth/list_rooms').then(function(response) {
		var message = typeof response.message!=='undefined' ? response.message : response.statusText;

		if(response.status==200 && response.status_text=='OK') {
			$scope.list_rooms = response.data;
		} else {
		  	alert(message);
		}
		$scope.is_loading_page = false;
	})

	$scope.doLogin = function (data) {
		$scope.info = {show: true, status_text: 'LOADING'};
		Data.post('admin_nurse/auth/login', data).then(function (response) {
			var status_text = typeof response.message!=='undefined' ? response.status_text : 'NO';
			var message 	= typeof response.message!=='undefined' ? response.message : response.statusText;
			var path		= $location.path();

			$scope.info.status_text = status_text;
			$scope.info.message 	= message;

			$scope.login = {};
			
			if(path=='/nurse-login') {
				if(response.status == 200 && response.status_text=='OK') {
					$localStorage.$default({
						bed_session_user: response.data,
						bed_logged_in: 1
					});
					
					$timeout(function() {
						$location.path('/admin-nurse/dashboard');
						window.location = '#!/admin-nurse/dashboard';
					}, 300)
				}
			}
		})
	};

}
