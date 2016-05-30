/**
 *  Contact details
**/
app.controller('ThankyouController',['$stateParams', '$scope', '$auth', '$log', '$http','$state','thankFactory', 'mgsCommonFactory' , '$q', 'Flash', 'initializeRolePerms', function($stateParams,  $scope, $auth, $log, $http, $state, thankFactory, mgsCommonFactory , $q, Flash, initializeRolePerms) {
  var $info = $stateParams.items;
  var $currentState = localStorage.getItem('stateparams');
  if ($info) {
    $params = JSON.stringify($info);
    localStorage.setItem('stateparams', $params);
  }
  else if( $currentState ) {
    $info = JSON.parse($currentState);
  }
  else {
    $state.go('404');
  }

  $scope.$on("$destroy", function(){
      localStorage.removeItem('stateparams');
  });


    $scope.login = $scope.login || {};

  $scope.isAuthenticated = function() {
    return $auth.isAuthenticated();
  }
  $scope.$on('$stateChangeSuccess', function() {
    google_conversion_id :'930654137';
    google_conversion_language :'en';
    google_conversion_format:'3';
    google_conversion_color:'ffffff';
    google_conversion_label:'y78ACOKdkGYQuc_iuwM';
    google_remarketing_only:false;
  });

  $scope.submitted = false;
  $scope.serviceName = $info.servicename;

  if ($info.usertype =='guest') {
    $scope.buttonText = 'Register';
    $scope.showForGot = false;
    $scope.contextualText = "Enter password to get registered and receive amazing offers";
  }
   else {
    $scope.buttonText = 'Login';
    $scope.showForGot = true;
    $scope.contextualText = "Please login to check your Quote Status";
   }

  $scope.login.username = $info.username;
  $scope.auth = function() {

    var credentials = {
                username: $scope.login.username,
                password: $scope.login.password,
    }


    $auth.login(credentials).then(function(response) {
                var data = response.data;
                if (!data.error){
                    $http.get('api/authenticate/user').then(function(response) {
                    $scope.currentUser = response.data.user;
                    $scope.loginError = false;
                    $scope.loginErrorText = '';
                    var user = JSON.stringify(response.data.user);
                    localStorage.setItem('user', user);
                     initializeRolePerms.init('no-reload');
                    }, function(error){
                      $scope.loginError = true;
                      $scope.loginErrorText = error.data.error;
                    });

                } else {
                   Flash.create('danger', data.error);
                    $q.reject("Login Failed");
                 }
            }, function(error) {
                $scope.loginError = true;
                $scope.loginErrorText = error.data.error;

            });
  };

  $scope.loginSubmit = function () {
    $scope.submitted = true;

    if ( $scope.thankslogin.$valid ) {
      if( $info.usertype  == 'guest'){
        var params = {
                  user_id: $info.user_id,
                  password: $scope.login.password,
        };
        thankFactory.updatePassword(params).then(function(response) {
          $scope.auth();
        }, function(error){

        })
      }
       else {
        $scope.auth();
       }
    }


  };
}])
.factory('thankFactory', ['$http','THANK_CONFIG', '$log',  function($http, THANK_CONFIG,  $log){
  return {
    updatePassword: function(params) {
      return $http({
        method : 'POST',
        url : THANK_CONFIG.getSetPassword ,
        data : params
      });
    }
  };
}])
.constant('THANK_CONFIG', {
   getSetPassword: '/api/setuserpassword'
})
.filter('capitalize', function() {
    return function(input, all) {
      var reg = (all) ? /([^\W_]+[^\s-]*) */g : /([^\W_]+[^\s-]*)/;
      return (!!input) ? input.replace(reg, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();}) : '';
    }
  });;


