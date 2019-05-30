'use strict';

angular
.module('app')
.factory('sessionService', ['$http', function($http){
	return {
		set:function(key,value){
			return localStorage.setItem(key,value);
		},
		get:function(key){
			return localStorage.getItem(key);
		},
		destroy:function(key){
			return localStorage.removeItem(key);
		},
		reset:function(){
			angular.forEach(localStorage, function (item,key) {
	          	return localStorage.removeItem(key);
	      	});
		},
		setAll:function(data){
			angular.forEach(data, function (item, key) {
				if(key != '__ci_last_regenerate') {
		          	return localStorage.setItem(key, item);
				}
	      	});
		}
	};
}]);
