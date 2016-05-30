app.controller('RoleController', function($scope,$stateParams, $http, $rootScope, $auth, $state, $window) {

	$scope.roles = [];
	$scope.messages={}; //place holder for the error messages
	$scope.succmessages={};


	$scope.getRoleByID = function(){
			var id = $stateParams.templateID;
			$http.get('/api/role/' + id).
			success(function(data, status, headers, config) {
				$scope.role = data;
				var foo	=	$stateParams.message;
				$scope.succmessages	=	foo;
			});
	};

	$scope.getAllRoles = function(){
		$http.get('/api/role').
		success(function(data, status, headers, config) {

			$scope.roles = data;
			var foo	=	$stateParams.message;
			$scope.succmessages	=	foo;
		});
};

$scope.save = function(){
    	$http.post('/api/role',$scope.newRole).success(function (data) {
			if(data.error_msg)
			{
				$scope.messages = data.error_msg;
			}
			else
			{
				$state.go('role', {message: data.succ_msg});
			}
    	});
	};

	$scope.logout = function() {
		$auth.logout().then(function() {
		    localStorage.removeItem('user');
		    $rootScope.authenticated = false;
		    $rootScope.currentUser = null;
		});
    }

    $scope.addnew	=	function(){
		$state.go('role/add');
	}

	$scope.cancel = function(index){
		$state.go('role');
	}

	$scope.delete = function(index){
    	var deleteRole = $window.confirm('Are you absolutely sure you want to delete?');
    	if (deleteRole) {
    		$http.delete('/api/role/'+ index).success(function(){
         		$scope.roles.splice(index,1);
         		$scope.succmessages	=	"Role Deleted Successfully.";
     		});
     	}
	};

	$scope.edit = function(){
		$http.get('/api/role/'+ $stateParams.templateID).success(function(data){
            $scope.newRole=data;
        });
	}

	$scope.update = function(index){
	    $http.put('/api/role/'+ $scope.role.id,$scope.role).success(function (data){
	        if(data.error_msg)
	        {
	            $scope.messages = data.error_msg;
	        }
	        else
	        {
	            $state.go('role', {message: data.succ_msg});
	        }
	    });
	};

});
