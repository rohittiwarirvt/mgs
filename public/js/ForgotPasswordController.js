app.controller('ForgotPasswordController',  function($auth, $state,$http,$rootScope, $scope, $stateParams, $timeout) {

  $scope.passwordreset = {
    token: $stateParams.token,
    email: $stateParams.email,
    };

  $scope.userResetPassword = function(){
    $scope.submitted = true;
    $http.post('password/reset', $scope.passwordreset).success(function(data){
        if(data.success == true){
            ga('send', 'event', { eventCategory: 'Forget Password', eventAction: 'Button Click', eventLabel: 'Password Reset'});
            $scope.successmessage = data.message;
        }
        else{
            $scope.errormessage = data.message;
        }

    })
    $timeout(function () {
      $state.go('home');
    }, 2000);
};

});
