'use strict';

angular
.module('app')
.factory('loginService', function($http, $localStorage, $location, $timeout, Data){
	return{
		login: function(data_login, scope){
			scope.info = {show: true, status_text: 'LOADING'};
			Data.post('login', data_login).then(function (response) {
				var status_text = typeof response.message!=='undefined' ? response.status_text : 'NO';
				var message 	= typeof response.message!=='undefined' ? response.message : response.statusText;

				scope.info.status_text 	= status_text;
				scope.info.message 		= message;

				scope.login = {};
				
				if(response.status == 200 && response.status_text=='OK') {
					$localStorage.$default({
						mca_session_user: response.data.user,
						mca_user_id: response.data.user.id,
						mca_api_key: response.data.api_key,
						mca_logged_in: 1
					});
					if(response.data.user.group=="2") {
						$timeout(function() {
							$location.path('/kasir');
							// window.location = '#!/operator/loket';
						}, 300)
					} else {
						$timeout(function() {
							$location.path('/admin/dashboard');
							// window.location = '#!/admin/dashboard';
						}, 300)
					}
				}
			})
		},
		logout: function(){
			Data.get('logout').then(function (response) {
            	var session_user = $localStorage.session_user || false;
                if(session_user.group=="2") {
                    $location.path('/nurse-login');
                    window.location = '#!/nurse-login';
                } else {
                    $location.path('/admin-login');
                    window.location = '#!/admin-login';
                }
				$localStorage.$reset();
      		});
		},
		isloggedIn: function(){
			var session_user = $localStorage.bed_session_user || false;
			var logged_in 	 = $localStorage.bed_logged_in || false;

			if(logged_in!==false && session_user!==false) {
				var last_login = session_user.last_login;
				var d1 = new Date();
				var d2 = new Date(last_login);
				var date_current = d1.getDate();
				var date_login 	 = d2.getDate();
				// var rangeTime = d1.getTime() - d2.getTime();
				// return (date_first==date_last) ? 1 : 0;				
				return (date_current==date_login) ? 1 : 0;				
			} else {
				return 0;
			}
		}
	}
});
