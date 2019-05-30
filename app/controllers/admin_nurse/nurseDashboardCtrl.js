angular
.module('app')
.controller('nurseDashboardCtrl', nurseDashboardCtrl);

nurseDashboardCtrl.$inject = ['$scope', '$rootScope', '$localStorage', '$timeout', '$location', 'Data', 'Idle'];
function nurseDashboardCtrl($scope, $rootScope, $localStorage, $timeout, $location, Data, Idle) {
  	var load_data;
    var session_user = $localStorage.bed_session_user || false;
    var sess_room    = session_user.room || "";

    $scope.module    = {
        title: sess_room,
        url: baseUrl + "/api/master/brand"
    }

    $scope.loading_form = {is_loading_form: false, message: ''};
    $scope.form         = {is_show: false, title: '', ask: '', data: {}, method: ''};
    $scope.info         = {status_text: 'LOADING', message: ''};
    $scope.admin_nurse  = {list_classes: [], list_beds: []};
    $scope.loading      = {classes: ""};
    $scope.selected     = {classes: ""};
    $scope.click_classes= true;
    $scope.is_bed_show  = "";

    Data.post('admin_nurse/dashboard', {room: sess_room}).then(function(response) {
        var message = typeof response.message!=='undefined' ? response.message : response.statusText;

        var path = $location.path();
        if(path=='/admin-nurse/dashboard') {
            if(response.status==200 && response.status_text=='OK') {
                $scope.selected.classes = response.data.list_classes[0].nama_klas;
                $scope.admin_nurse      = response.data;
                load_data = $timeout(function() { $scope.reloadData(); }, 5000);
            } else {
                alert(message);
            }
            $timeout(function() { $scope.is_bed_show = "bed-show"; }, 1);
            $scope.is_loading_page = false;
        }
    })

    /* update status bed */
    $scope.updateStatusBed = function (data, set_status_bed) {
        $timeout.cancel(load_data);
        // $scope.$on("$destroy", function (event)  
        // {
        //     alert('test');
        // });  
		var title = 'Ubah Status Bed';
        var ask   = "";

        if(set_status_bed==0) {
            ask = "Apakah anda yakin pasien sudah meninggalkan bed?";
        } else if(set_status_bed==5) {
            ask = "Apakah anda yakin bed "+data.no_bed+" rusak?";
        } else {
            ask = "Apakah anda ingin memperbaiki bed "+data.no_bed+"?";
        }

        data.set_status_bed = set_status_bed;
        $scope.form = {is_show: true, title: title, ask: ask, data: data, method: 'update'};
        $scope.loading_form.is_loading_form = false;

    }

    /* click class*/
    $scope.clickClasses = function(classes) {
        if($scope.selected.classes!==classes && $scope.click_classes===true) {
            var room = sess_room;
            $scope.loading.classes  = "start";
            $scope.selected.classes = classes;
            $scope.is_bed_show      = "";
            $scope.click_classes    = false;

            Data.post('admin_nurse/dashboard/select_classes', {room: room, classes: classes}).then(function(response) {
                var message = typeof response.message!=='undefined' ? response.message : response.statusText;

                var path = $location.path();
                if(path=='/admin-nurse/dashboard') {
                    if($scope.selected.classes==classes) {
                        if(response.status==200 && response.status_text=='OK') {
                            $scope.admin_nurse.list_beds = response.data;
                        }
                    }
                    $scope.is_bed_show      = "bed-show";
                    $scope.loading.classes  = "";
                    $scope.click_classes    = true;
                }
            })
        }
    }

    /* CLOSE FORM */
    $scope.formClose = function() {
        load_data = $timeout(function() { $scope.reloadData(); }, 5000);
        $scope.form = {is_show: false, title: '', data: {}, method: ''};
    }

    /* SAVE */
    $scope.save = function (data) {
        delete data.info_pasien;
        // delete data.info_booking;

        $scope.loading_form.is_loading_form = true;
        $scope.info = {status_text: 'LOADING', message: 'Sedang menyimpan, mohon tunggu...'};

        Data.post('admin_nurse/dashboard/save', data).then(function (response) {
            var status_text = typeof response.message!=='undefined' ? response.status_text : 'NO';
            var message     = typeof response.message!=='undefined' ? response.message : response.statusText;
    
            $scope.info.status_text = status_text;
            $scope.info.message     = message;

            var path = $location.path();
            if(path=='/admin-nurse/dashboard') {
                if(response.status == 200 && response.status_text=='OK') {
                    $scope.renewStatusBed(data);
                }
            }
        })
    }

    $scope.renewStatusBed = function(data) {
        var kode_ruangan    = data.kode_ruangan;
        var status_bed      = data.status_bed;
        var set_status_bed  = data.set_status_bed;
        var classes         = $scope.selected.classes;
        var list_beds       = $scope.admin_nurse.list_beds;

        angular.forEach(list_beds, function(value, key) {
            if(value.kode_ruangan==kode_ruangan) {
                $scope.admin_nurse.list_beds[key].status_bed = set_status_bed;
                return;
            }
        })
    }

    /* reload data */
    $scope.reloadData = function() {
        var classes = "";
        classes = angular.copy($scope.selected.classes, classes);

        Data.post('admin_nurse/dashboard/reload_data', {room: sess_room, classes: classes}).then(function(response) {
            var message = typeof response.message!=='undefined' ? response.message : response.statusText;

            var path = $location.path();
            if(path=='/admin-nurse/dashboard') {
                if(response.status==200 && response.status_text=='OK') {
                    $scope.admin_nurse.list_beds = response.data;
                }
                if($scope.form.is_show===false) {
                    load_data = $timeout(function() { $scope.reloadData(); }, 5000);
                }
            }
        })
    }

    $scope.idle = 30;
    $scope.$on('IdleStart', function() {
        console.log('idle start');
        $timeout.cancel(load_data);
        $localStorage.$reset();
        $location.path('nurse-login');
        window.location = '#!/nurse-login';
    });

    $scope.$watch('idle', function(value) {
      if (value !== null) Idle.setIdle(value);
    });

}