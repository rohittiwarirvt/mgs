app.controller('PermissionRoleController', function($scope,$stateParams, $http, $rootScope, $auth, $state) {

	$scope.permissionrole = [];
	$scope.messages={}; //place holder for the error messages
	$scope.succmessages={};

	$http.get('/api/permissionrole').
	success(function(data, status, headers, config) {
		$scope.permissions 	=	data.permissions;
		$scope.roles 		=	data.roles;
		$scope.permissionrole1 		=	data.permissionrole1;
		var foo	=	$stateParams.message;
		$scope.succmessages	=	foo;
	});
	$scope.update = function(permissionid, roleid, checkStatus){
        if(checkStatus==undefined)
          checkStatus1='';
        else
           checkStatus1=checkStatus;
		data = { 'permissionid': permissionid, 'roleid':roleid,'checkStatus':checkStatus1};
		$http.put('/api/permissionrole/update', data);
    };
	
});
