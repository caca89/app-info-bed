"use strict";

angular
.module('app', [
  'ui.router',
  'ngSanitize',
  'oc.lazyLoad',
  'ngStorage',
  'ngIdle'
])
.run(['$rootScope', '$state', '$stateParams', '$urlRouter', '$localStorage', '$location', 'loginService', 'Administration', '$templateCache', 'Idle', 
    function($rootScope, $state, $stateParams, $urlRouter, $localStorage, $location, loginService, Administration, $templateCache, Idle) {
        // $rootScope.$on('$viewContentLoaded', function() {
        //     $templateCache.removeAll();
        // });
        // $rootScope.$on('$routeChangeStart', function(event, next, current) {
        //     if (typeof(current) !== 'undefined'){
        //         $templateCache.remove(current.templateUrl);
        //     }
        // });
        Idle.watch();
        $rootScope.$on('$locationChangeStart', function(evt) {
            var is_login     = loginService.isloggedIn();
            var session_user = $localStorage.bed_session_user || false;
            var path         = $location.path();

            if(is_login===1) {
                if(path=='' || path=='/' || path=='/nurse-login' || path=='/admin-login') {
                    if(session_user.group=="2") {
                        $location.path('/admin-nurse/dashboard');
                        window.location = '#!/admin-nurse/dashboard';
                    } else {
                        $location.path('/admin-panel/dashboard');
                        window.location = '#!/admin-panel/dashboard';
                    }
                } else {
                    if(session_user.group=="2") {
                        Administration.permissionAdminNurse(path);
                    } else {
                        Administration.permissionAdmin(path);
                    }
                }
            } else {
                $localStorage.$reset();
                if(session_user) {
                    if(session_user.group=="2") {
                        $location.path('/nurse-login');
                        window.location = '#!/nurse-login';
                    } else {
                        $location.path('/admin-login');
                        window.location = '#!/admin-login';
                    }
                } else {
                    var x =path.split("/");
                    var y = x[1] || "";
                    if(y=='admin-nurse' || y=='admin-panel') {
                        $location.path('/home');
                    }
                }
            }
        });
        $rootScope.$on('$locationChangeSuccess',function(evt){
            document.body.scrollTop = document.documentElement.scrollTop = 0;
        });
        $rootScope.is_loading_home = true;
        $rootScope.is_loading_page = true;
        $rootScope.$state = $state;
        return $rootScope.$stateParams = $stateParams;
    }
])
