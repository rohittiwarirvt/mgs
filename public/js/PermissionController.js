app.controller('PermissionController', function($scope,$stateParams, $http, $rootScope, $auth, $state, $window) {

	$scope.permissions = [];
	$scope.messages={}; //place holder for the error messages
	$scope.succmessages={};

  $scope.getAllPermissions = function(){
  	$http.get('/api/permission').
  	success(function(data, status, headers, config) {
  		$scope.permissions = data;
  		var foo	=	$stateParams.message;
  		$scope.succmessages	=	foo;
  	});
	};

 $scope.getPermissionsByID = function(){
    var id = $stateParams.templateID;
    $http.get('/api/permission/' + id).
    success(function(data, status, headers, config) {
      $scope.permissions = data;
      var foo = $stateParams.message;
      $scope.succmessages = foo;
    });
  };

	$scope.save = function(){
    	$http.post('/api/permission',$scope.newPermission).success(function (data) {
			if(data.error_msg)
			{
				$scope.messages = data.error_msg;
			}
			else
			{
				$state.go('permission', {message: data.succ_msg});
			}
    	});
	};

    $scope.addnew	=	function(){
		$state.go('permission/add');
	}

	$scope.cancel = function(index){
		$state.go('permission');
	}

	$scope.delete = function(index){
		var deletePermission = $window.confirm('Are you absolutely sure you want to delete?');

    	if (deletePermission) {
	    	$http.delete('/api/permission/'+ $scope.permissions[index].id).success(function(){
	         		$scope.permissions.splice(index,1);
	         		$scope.succmessages	=	"Permission Deleted Successfully.";
	     	});
     	}
	};

	$scope.edit = function(){
		$http.get('/api/permission/'+ $stateParams.templateID).success(function(data){
            $scope.permissions=data;
        });
	}

	$scope.update = function(index){

        $http.put('/api/permission/'+ $scope.permissions.id,$scope.permissions).success(function (data){
            if(data.error_msg)
            {
                $scope.messages = data.error_msg;
            }
            else
            {
                $state.go('permission', {message: data.succ_msg});
            }
        });
    };
});
