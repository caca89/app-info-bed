"use strict";

angular
.module('app')
.config(['$stateProvider', '$urlRouterProvider', '$ocLazyLoadProvider', 
    function($stateProvider, $urlRouterProvider, $ocLazyLoadProvider) {

    	$urlRouterProvider.otherwise('/home');

    	$ocLazyLoadProvider.config({
    		// Set to true if you want to see what and when is dynamically loaded
    		debug: true
 		 });

    	$stateProvider
    	.state('home', {
		    url: '/home',
		    templateUrl: 'views/pages/home.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/homeCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
    	.state('dashboard', {
    		url: '/dashboard',
    		abstract: true,
    		templateUrl: 'views/dashboard.html?v='+Date.now()
    	})
    	.state('dashboard.general', {
		    url: '-general',
		    templateUrl: 'views/pages/dashboard/general.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/dashboard/dashboardGeneralCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
    	.state('dashboard.nurse', {
		    url: '-nurse',
		    templateUrl: 'views/pages/dashboard/nurse.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/dashboard/dashboardNurseCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
	  	// .state('auth_nurse', {
		  //   url: '/nurse',
		  //   abstract: true,
		  //   templateUrl: 'views/pages/admin_nurse/auth.html?v='+Date.now(),
		  //   resolve: {
		  //     	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			 //        return $ocLazyLoad.load({
			 //          	files: ['app/controllers/admin_nurse/nurseAuthCtrl.js?v='+Date.now()]
			 //        });
		  //     	}]
    // 		}
    // 	})
	  	// .state('auth_nurse.login', {
		  //   url: '-login',
		  //   templateUrl: 'views/pages/admin_nurse/auth/login.html?v='+Date.now()
		  //   // controller: authCtrl,
	  	// })
    // 	.state('admin_nurse', {
    // 		url: '/admin-nurse',
    // 		abstract: true,
    // 		templateUrl: 'views/pages/admin_nurse/admin.html?v='+Date.now()
    // 	})
    // 	.state('admin_nurse.dashboard', {
		  //   url: '/dashboard',
		  //   templateUrl: 'views/pages/admin_nurse/dashboard/list.html?v='+Date.now(),
		  //   resolve: {
		  //     	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			 //        return $ocLazyLoad.load({
			 //          	files: ['app/controllers/admin_nurse/nurseDashboardCtrl.js?v='+Date.now()]
			 //        });
		  //     	}]
    // 		}
    // 	})
	  	.state('auth_admin', {
		    url: '/admin',
		    abstract: true,
		    templateUrl: 'views/pages/admin_panel/auth.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/admin_panel/adminAuthCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
	  	.state('auth_admin.login', {
		    url: '-login',
		    templateUrl: 'views/pages/admin_panel/auth/login.html?v='+Date.now()
		    // controller: authCtrl,
	  	})
    	.state('admin_panel', {
    		url: '/admin-panel',
    		abstract: true,
    		templateUrl: 'views/pages/admin_panel/admin.html?v='+Date.now()
    	})
    	.state('admin_panel.dashboard', {
		    url: '/dashboard',
		    templateUrl: 'views/pages/admin_panel/dashboard/list.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/admin_panel/adminDashboardCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
    	.state('admin_panel.report', {
		    url: '/report',
		    templateUrl: 'views/pages/admin_panel/report/list.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/admin_panel/adminReportCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
    	.state('admin_panel.user', {
		    url: '/user',
		    templateUrl: 'views/pages/admin_panel/user/list.html?v='+Date.now(),
		    resolve: {
		      	loadMyCtrl: ['$ocLazyLoad', function($ocLazyLoad) {
			        return $ocLazyLoad.load({
			          	files: ['app/controllers/admin_panel/adminUserCtrl.js?v='+Date.now()]
			        });
		      	}]
    		}
    	})
    }
])