app.controller('AdminUserController',  function($state, $http, $rootScope, $scope,$auth, $stateParams, $window) {

  $scope.submitted = false;
  $scope.selectedRoles = [];

  $scope.getUsers = function(value){
  $http({
      url: '/api/getUsers', 
      method: "GET",
      params: { role: value},
      headers: {'Content-Type': 'text/json'}
  }).success(function (data,status) {
    if(data.error_msg) {
      $scope.messages = data.error_msg;
    }
    else {
      $scope.users = data;
    }
  });
  }

  $scope.addUser = function(){
  	$state.go('adminadduser');
  };

  $scope.editUser = function(){
    $scope.user = {
     userid: $stateParams.userid,
    }
    $http.get('/api/adminuser/'+$scope.user.userid).
    success(function(data) {
      $scope.userinfo = data;
    })
  };

  $scope.delete = function(userId){
     $scope.deleteUser = $window.confirm('Are you sure you want to delete?');
     if($scope.deleteUser){
        $http.delete('/api/adminuser/'+userId).success(function(data) {
           if(data.succ_msg)
            {   
               $scope.successmessages = data.succ_msg;
            }
        })
     }

  };

  $scope.updateUser = function(){
    $scope.submitted =  true;
    if($scope.userEdit.$valid){
      $http.post('/api/adminuser/'+$scope.user.userid, $scope.userinfo).
      success(function(data) {
      if(data.succ_msg)
        {   
          $scope.successmessages = data.succ_msg;
         $scope.errormessages = null;
          $scope.errorMessage = "";
        }
        else
        {   
          $scope.errormessages = data.error_msg;
          $scope.successmessages = null;
        }
    })
  }
};


  $scope.registerUser = function(){
  	$scope.submitted = true;
  	if($scope.registration.$valid) {
       if($scope.register.selectedRoles.length > 1){
          $scope.successMessage = ""; 
          $scope.errorMessage = "For Phase 1 You can select only one role for a user";
          return 1;
        }
       $http.post('/api/adminAddUser',$scope.register).success(function (data) {
            if(data.status == "success"){   
               $scope.errorMessage = "";
               $scope.successMessage = data.message;
            }
            else{
               var messageObject = data.message;
               var errorMessage = "";
               for(var field in messageObject){
                  if(messageObject.hasOwnProperty(field))
                    errorMessage = errorMessage + messageObject[field];
                }
               $scope.successMessage = "";
               $scope.errorMessage = errorMessage;
            }
        })
  	}
  }

  $scope.getRoles = function(){
	$http.get('/api/role').success(function(data){
		$scope.roles = data;
      $http.get('/api/adminuserrole/'+$scope.user.userid).success(function(UserRole){
        $scope.userroles = UserRole;
      })
  }).error(function(data){
		$scope.errorMessage="Error in Fetching Roles";
	})
  }


});