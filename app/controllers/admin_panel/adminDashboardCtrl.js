angular
.module('app')
.controller('adminDashboardCtrl', adminDashboardCtrl);

adminDashboardCtrl.$inject = ['$scope', '$rootScope', '$localStorage', '$timeout', '$location', 'Data'];
function adminDashboardCtrl($scope, $rootScope, $localStorage, $timeout, $location, Data) {
    var session_user = $localStorage.bed_session_user || false;
    var user_id      = session_user.id || false;

    $scope.module    = {
        title: "Dashboard",
        url: baseUrl + "/api/admin_panel/dashboard"
    }

    $scope.date_now          = "";
    $scope.average_time 	 = [];
    $scope.patient_out_daily = [];
    Data.post('admin_panel/dashboard').then(function(response) {
        var message = typeof response.message!=='undefined' ? response.message : response.statusText;

        var path = $location.path();
        if(path=='/admin-panel/dashboard') {
            if(response.status==200 && response.status_text=='OK') {
                $scope.average_time 	 = response.data.average_time;
                $scope.patient_out_daily = response.data.patient_out_daily;
                $scope.date_now          = date_now();
            } else {
                alert(message);
            }
            $timeout(function() { $scope.is_bed_show = "bed-show"; }, 1);
            $scope.is_loading_page = false;
        }
    })

    $timeout(function() { $scope.is_loading_page = false; }, 1);

    function date_now() {
        var date = new Date;
        var d = parseInt(date.getDate());
        var m = parseInt(date.getMonth()) + 1;
        d = d < 10 ? "0"+d : d;
        m = m < 10 ? "0"+m : m;
        var y = date.getFullYear();
        return d+"/"+m+"/"+y;
    }
}