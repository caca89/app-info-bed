angular
.module('app')
.controller('adminUserCtrl', adminUserCtrl);

adminUserCtrl.$inject = ['$scope', '$rootScope', '$location', '$localStorage', '$timeout', 'Data'];
function adminUserCtrl($scope, $rootScope, $location, $localStorage, $timeout, Data) {
    var session_user = $localStorage.session_user || false;
    var user_id      = session_user.id || false;
    var group        = session_user.group;

    if(group > 0) {
        $location.path('/admin-nurse/dashboard');
        window.location = '#!/admin-panel/dashboard';
        return;
    }

  	$scope.module = {
    		title: 'Pengguna',
    		url: baseUrl + "/api/admin_panel/user",
        form: "views/pages/admin_panel/user/form.html?v="+Date.now()
  	}
  	$scope.loading_form = {is_loading_form: false, message: ''};
  	$scope.form = {is_show: false, title: '', data: {}, method: ''};

  	/* init table */
  	$scope.tables = {
    		columns: {
      			id: {name: 'id', title: 'No.', width: '25px'},
      			name: {name: 'name', title: 'Nama', width: 'auto'},
            username: {name: 'username', title: 'Nama Pengguna', width: 'auto'},
            status: {name: 'status', title: 'Status', width: '75px'},
      			actions: {name: 'actions', title: '', width: '200px'}
    		}
  	}
	 angular.element(document).ready(function() {
      	$scope.dataTable = $('#data-grid').DataTable({
          	"serverSide": true,
          	"ajax": {
              	url: $scope.module.url,
              	type: 'GET',
              	headers: {
                    "User-Id": user_id,
              	}
          	},
          	"columns": [
  	          	{"data": "id", "name": "id"},
  	          	{"data": "name", "name": "name"},
                {"data": "username", "name": "username"},
                {"data": "status", "name": "status"},
  	          	{"data": "actions", "name": "actions"}
          	],
          	"columnDefs": [ { orderable: false, targets: [0,3,4] } ],
          	"order": [[ 1, "asc" ]]
      	});

        angular.element('#data-grid').on('click', 'a.edit', function(e) {
            e.preventDefault();
            var id = $(this).attr('data-id');
            $scope.edit(id);
        })
      	angular.element('#data-grid').on('click', 'a.reset-pass', function(e) {
        		e.preventDefault();
        		var id = $(this).attr('data-id');
        		$scope.resetPass(id);
      	})
	 })

  	/* CREATE */
  	$scope.create = function() {
    		var title = 'Tambah ' + $scope.module.title;
    		$scope.form = {is_show: true, title: title, data: {}, method: 'create'};
        $scope.info = {show: false};
    		$scope.loading_form = {is_loading_form: true, message: 'Loading...'};
    		$timeout(function() {$scope.loading_form.is_loading_form = false}, 200);
  	}

    /* EDIT */
    $scope.edit = function (id) {
        var title = 'Edit ' + $scope.module.title;
        var data = {};

        $scope.loading_form = {is_loading_form: true, message: 'Loading...'};
        $scope.info = {show: false};
        Data.post('admin_panel/user/get_data', {id:id}).then(function(response) {
            var message     = typeof response.message!=='undefined' ? response.message : response.status_text;

            if(response.status==200 && response.status_text=='OK') {
                angular.copy(response.data, data);
                $scope.form = {is_show: true, title: title, data: data, method: 'update'};
                $timeout(function() {$scope.loading_form.is_loading_form = false}, 0);
            } else {
                alert(message);
            }
        })
    }

    /* RESET PASSWORD */
    $scope.resetPass = function (id) {
        var conf = confirm('Are you sure want to reset password this user?');
        if(conf) {
            Data.post('admin_panel/user/reset_password', {id:id}).then(function(response) {
                var message = typeof response.message!=='undefined' ? response.message : response.status_text;
                alert(message);
            })
        }
    }

    /* CLOSE FORM */
    $scope.formClose = function() {
        $scope.form = {is_show: false, title: '', data: {}, method: ''};
    }

    /* SAVE */
    $scope.save = function (data, method) {
        $scope.info = {show: true, status_text: 'LOADING'};
        Data.post('admin_panel/user/save/'+method, data).then(function (response) {
            var status_text  = typeof response.message!=='undefined' ? response.status_text : 'NO';
            var message     = typeof response.message!=='undefined' ? response.message : response.status_text;
    
            $scope.info.status_text = status_text;
            $scope.info.message    = message;

            if(response.status == 200 && response.status_text=='OK') {
                $scope.dataTable.ajax.reload( null, false );
                if(method == 'create') {
                    $scope.form.data = {};
                }
            }
        })
    }

    $timeout(function() {$scope.is_loading_page = false}, 300);
}