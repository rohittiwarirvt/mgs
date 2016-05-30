/**
 *  USer Profile details
**/
app.controller('UserProfileController',  function($state, $http, $rootScope, $scope,$auth, $window) {
    $scope.submitted = false;
    if($auth.isAuthenticated()) {
    var $user = JSON.parse(localStorage.getItem('user'));
    $scope.profile={
        id: $user.id,
    };
    }
    $scope.init = function (){
        $http.get('/api/userprofile/'+$scope.profile.id).success(function(data){
            $scope.users=data;
            $scope.profile={
                username: $scope.users.username,
                email: $scope.users.email,
                phonenumber: $scope.users.phonenumber,
                firstname: $scope.users.first_name,
                lastname: $scope.users.last_name,
                address1: $scope.users.address1,
                address2: $scope.users.address2,
                city: $scope.users.city,
                pincode: $scope.users.pincode,
            };
        })
    };

    $scope.userPanel = function(){
        $state.go('adminuserpanel');
    }

    $scope.thanks = function(){
        $state.go('thank-you');
    }

    $scope.resetpassword= function(){
        $scope.submitted = true;
        $http.post('/api/reset-password', $scope.updatepassword).success(function(data){
            if(data.succ_msg)
            {
               $scope.message = data.succ_msg;
            }
        })

    };

    $scope.update = function(index){
        $scope.submitted = true;
        if($scope.profileForm.$valid){
            $http.put('/api/userprofile/'+$scope.profile.id, $scope.profile).success(function(data){
                if(data.succ_msg)
                    {
                    $scope.successmessages = data.succ_msg;
                    localStorage.setItem('user', JSON.stringify(data.user));
                    $scope.errormessages = null;
                }
                else
                {
                    $scope.errormessages = data.error_msg;
                    $scope.successmessages = null;
                }
                $window.location.reload();
            })
        }
    };

    $scope.delete = function(index){

    };

    $scope.logout = function() {
        $auth.logout().then(function() {
        $rootScope.currentUser = null;
        $state.go('login');
    });
    }
    $scope.init();

});
