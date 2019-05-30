angular
.module('app')
.controller('dashboardGeneralCtrl', dashboardGeneralCtrl)

dashboardGeneralCtrl.$inject = ['$scope',  '$rootScope', '$state', '$timeout', '$localStorage', '$location', 'Data'];
function dashboardGeneralCtrl($scope, $rootScope, $state, $timeout, $localStorage, $location, Data) {
	$scope.maintenance = false;
	// $scope.is_loading_page = false;
	// return;
    var load_data;
    var load_slide;
    $scope.dashboard_general = {data: [], data_rooms: [], page: 0, total_page: 0};

    Data.post('dashboard/general').then(function(response) {
        var message = typeof response.message!=='undefined' ? response.message : response.statusText;

        $scope.dashboard_general.data       = response.data;
        $scope.dashboard_general.total_page = Object.keys(response.data).length;

        if(response.status==200 && response.status_text=='OK') {
            var page        = $scope.dashboard_general.page;
            var total_page  = $scope.dashboard_general.total_page;
            $scope.dashboard_general.data_rooms = $scope.dashboard_general.data[page];
            
            if(total_page > page) {
                load_slide = $timeout(function() { $scope.slideDashboardGeneral(); }, 10000);
            }

            load_data = $timeout(function() { $scope.reloadData(); }, 5000);

            $scope.is_loading_page = false;
        } else {
            $scope.reloadPage();
        }
    })

    $scope.slideDashboardGeneral = function() {
        var path        = $location.path();
        var page        = $scope.dashboard_general.page;
        var total_page  = $scope.dashboard_general.total_page;
        var new_page    = parseInt(page) + 1;
        new_page        = new_page >= total_page ? 0 : new_page;

        if(path=='/dashboard-general') {
            $scope.dashboard_general.data_rooms = $scope.dashboard_general.data[new_page];
            $scope.dashboard_general.page       = new_page;

            $timeout(function() { $scope.slideDashboardGeneral() }, 10000);
        } else {
            $scope.dashboard_general = {data: [], data_rooms: [], page: 0, total_page: 0};
        }
    }

    $scope.reloadData = function() {
        Data.post('dashboard/reload_dashboard_general').then(function(response) {
            var message = typeof response.message!=='undefined' ? response.message : response.statusText;
            var path    = $location.path();

            if(path=='/dashboard-general') {
                if(response.status==200 && response.status_text=='OK') {
                    $scope.dashboard_general.data       = response.data;
                    $scope.dashboard_general.total_page = Object.keys(response.data).length;
                }
                $timeout(function() { $scope.reloadData(); }, 5000);
            } else {
                $scope.dashboard_general = {data: [], data_rooms: [], page: 0, total_page: 0};
            }
        })
    }

    $scope.reloadPage = function() {
        $state.reload();
    }
}
