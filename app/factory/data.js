angular
.module('app')
.factory("Data", ['$http', '$localStorage', 
    function ($http, $localStorage) { 
        var session_user = $localStorage.bed_session_user || false;
        var user_id      = session_user.user_id || false;
        // This service connects to our REST API
        return {
            get: function (q) {
                return $http({
                    method: 'get',
                    headers: {
                        "No-Induk": user_id
                    },
                    url: 'api/' + q,
                }).then(function (response) {
                    return response.data;
                }).catch(function(response) { return response; });
            },
            post: function (q, object) {
                return $http({
                    method: 'post',
                    headers: {
                        "No-Induk": user_id
                    },
                    url: 'api/' + q,
                    data: object
                }).then(function (response) {
                    return response.data;
                }).catch(function(response) { return response; });
            }
        }
}]);