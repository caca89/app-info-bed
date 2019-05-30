'use strict';

angular
.module('app')
.factory('Administration', function($http, $localStorage, $location, $timeout, $window, Data){
	return{
		permissionAdminNurse: function(path){
            var x =path.split("/");
            var y = x[1] || "";
			if(y!='admin-nurse') {
				$location.path('/nurse-login');
				$window.location = '#!/nurse-login';
			}
		},
		permissionAdmin: function(path){
            var x =path.split("/");
            var y = x[1] || "";
			if(y!='admin-panel') {
				$location.path('/admin-login');
				$window.location = '#!/admin-login';
			}
		}
	}
});
