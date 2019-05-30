angular
.module('app')
.controller('dashboardNurseCtrl', dashboardNurseCtrl)
.config(function(IdleProvider, KeepaliveProvider) {
	IdleProvider.idle(15);
	IdleProvider.timeout(15);
	KeepaliveProvider.interval(15);
});

dashboardNurseCtrl.$inject = ['$scope',  '$rootScope', '$state', '$timeout', '$localStorage', '$location', 'Data', 'Idle'];
function dashboardNurseCtrl($scope, $rootScope, $state, $timeout, $localStorage, $location, Data, Idle) {
	$scope.maintenance = false;
	// $scope.is_loading_page = false;
	// return;
	var load_data;
	$scope.dashboard_nurse 	= {list_rooms: [], list_classes: [], list_beds: []};
	$scope.loading 			= {room: "", classes: ""};
	$scope.selected 		= {room: "", classes: ""};
	$scope.click_room 		= true;
	$scope.click_classes 	= true;
	$scope.is_bed_show 		= "";

	Data.post('dashboard/nurse').then(function(response) {
		var message = typeof response.message!=='undefined' ? response.message : response.statusText;

		$timeout(function() { $scope.is_bed_show = "bed-show"; }, 1);
		$scope.is_loading_page = false;
		if(response.status==200 && response.status_text=='OK') {
		  	$scope.dashboard_nurse 	= response.data;
			$scope.selected.room  	= response.data.list_rooms[0].no_kamar;
			$scope.selected.classes = response.data.list_classes[0].nama_klas;
		  	
		  	load_data = $timeout(function() { $scope.reloadData(); }, 5000);
		} else {
			alert(message);
		  	$scope.reloadPage();
		}
	})
	$scope.clickRoom = function(room) {
		if($scope.loading.room!==room && $scope.click_room===true) {

			$scope.loading 		 = {room: "start", classes: ""};
			$scope.is_bed_show   = "";
			$scope.selected.room = room;
			$scope.click_room  	 = false;

			$timeout.cancel(load_data);
	  		var _room;
	  		_room = angular.copy(room, _room);

		  	Data.post('dashboard/select_room', {room: room}).then(function(response) {
				var message = typeof response.message!=='undefined' ? response.message : response.statusText;

	      		var path = $location.path();
	            if(path=='/dashboard-nurse') {
					$scope.click_room = true;
					if(response.status==200 && response.status_text=='OK') {
						if($scope.selected.room==room) {
							$scope.selected.room  		  	  	= room;
							$scope.selected.classes  		  	= response.data.list_classes[0].nama_klas;
							$scope.dashboard_nurse.room_name 	= response.data.room_name;
							$scope.dashboard_nurse.list_classes = response.data.list_classes;
							$scope.dashboard_nurse.list_beds 	= response.data.list_beds;

							$scope.loading.room = "";
							$scope.is_bed_show 	= "bed-show";

							load_data = $timeout(function() { $scope.reloadData(); }, 5000);
						}
					} else {
						alert(message);
						$scope.clickRoom(room);
					}
				}
		  	})
		}
	}
  	$scope.clickClasses = function(room, classes) {
	  	if($scope.selected.room===room && $scope.selected.classes!==classes && $scope.click_classes===true) {
			$timeout.cancel(load_data);
			$scope.loading 		 	= {room: "", classes: "start"};
			$scope.is_bed_show 		= "";
			$scope.selected.classes = classes;
			$scope.click_classes	= false;

		  	Data.post('dashboard/select_classes', {room: room, classes: classes}).then(function(response) {
		      	var message = typeof response.message!=='undefined' ? response.message : response.statusText;

	      		var path = $location.path();
	            if(path=='/dashboard-nurse') {
					if($scope.selected.room==room && $scope.selected.classes==classes) {
			      		if(response.status==200 && response.status_text=='OK') {
			        	  	$scope.dashboard_nurse.list_beds = response.data;
		                }

		                load_data = $timeout(function() { $scope.reloadData(); }, 5000);
		                $scope.is_bed_show 		= "bed-show";
		                $scope.loading.classes 	= "";
		                $scope.click_classes	= true;
	                }
		      	}
		  	})
	  	}
  	}

  	$scope.reloadData = function() {
  		var room 	= "";
  		var classes = "";
  		room 	= angular.copy($scope.selected.room, room);
  		classes = angular.copy($scope.selected.classes, classes);

	  	Data.post('dashboard/reload_dashboard_nurse', {room: room, classes: classes}).then(function(response) {
	      	var message = typeof response.message!=='undefined' ? response.message : response.statusText;

      		var path = $location.path();
            if(path=='/dashboard-nurse') {
				if($scope.selected.room==room && $scope.selected.classes==classes) {
		      		if(response.status==200 && response.status_text=='OK') {
		        	  	$scope.dashboard_nurse.list_beds = response.data;
	                }
		          	load_data = $timeout(function() { $scope.reloadData(); }, 5000);
                }
	      	}
	  	})
  	}

    $scope.reloadPage = function() {
        $state.reload();
    }

    Idle.watch();
    $scope.idle = 15;
    $scope.$on('IdleStart', function() {
    	console.log('idle start');
    	$timeout.cancel(load_data);
      	$location.path('home');
      	window.location = '#!/home';
	    Idle.unwatch();
    });
    $scope.$on('IdleEnd', function() {
    });

    $scope.$watch('idle', function(value) {
      if (value !== null) Idle.setIdle(value);
    });

}
