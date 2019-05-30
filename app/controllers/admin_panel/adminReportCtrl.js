angular
.module('app')
.controller('adminReportCtrl', adminReportCtrl);

adminReportCtrl.$inject = ['$scope', '$rootScope', '$location', '$localStorage', '$timeout', 'Data'];
function adminReportCtrl($scope, $rootScope, $location, $localStorage, $timeout, Data) {
    var session_user = $localStorage.session_user || false;
    var user_id      = session_user.id || false;
    var group        = session_user.group;

    if(group > 0) {
        $location.path('/admin-nurse/dashboard');
        window.location = '#!/admin-panel/dashboard';
        return;
    }

    $scope.module = {
        title: 'Laporan',
        url: baseUrl + "/api/admin_panel/report"
    }

    $scope.search  = {};
    $scope.results = [];
    $scope.loading_search = false;

    /* SEARCH REPORT */
    $scope.searchReport = function (data) {
        $scope.loading_search = true;
        $scope.results = [];
        Data.post('admin_panel/report', data).then(function (response) {
            var message = typeof response.message!=='undefined' ? response.message : response.statusText;

            $scope.loading_search = false;
            if(response.status == 200 && response.status_text=='OK') {
                $scope.results = response.data;
            } else {
                alert(message);
            }
        })
    }

    $scope.backToSearch = function() {
        $scope.search  = {};
        $scope.results = [];
    }
    $scope.changeDateStart = function(data) {
        var i = {};
        angular.copy(data, i);
        if(typeof i.date_end==='undefined') {
          i.date_end = i.date_start;
          $scope.search.date_end = data.date_start;
        }

        var j = setupDate(i);
        if(j.indate > j.outdate) {
            i.date_end = i.date_start;
            $scope.search.date_end = i.date_start;
        }
    }
    /* EVENT CHANGE DATE END */
    $scope.changeDateEnd = function(data) {
        var i = {};
        angular.copy(data, i);
        var j = setupDate(i);
        if(j.outdate < j.indate) {
            i.date_start = i.date_end;
            $scope.search.date_start = i.date_end;
        }
    }

    $timeout(function() {$scope.is_loading_page = false}, 300);
}

function setupDate(data) {
    var a = data.date_start;
    var b = a.split('-');
    var c = b[2]+"-"+b[1]+"-"+b[0];
    var d = new Date(c);

    var w = data.date_end;
    var x = w.split('-');
    var y = x[2]+"-"+x[1]+"-"+x[0];
    var z = new Date(y);

    return {indate: d.getTime(), outdate: z.getTime()};    
}
