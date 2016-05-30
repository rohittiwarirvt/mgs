angular.module('userApp').controller('RegistrationController', ['$rootScope', '$scope', '$auth', '$http','$state', '$uibModalInstance', '$q', 'Flash', 'mgsCommonFactory', '$document', '$timeout', function($rootScope, $scope, $auth, $http, $state, $uibModalInstance, $q, Flash, mgsCommonFactory, $document, $timeout){
    $scope.submitted = false;
    $scope.register = function(){
      $scope.submitted = true;
      var $source = localStorage.getItem('source');
      var userinfo = {
        first_name : $scope.register.firstname,
        username : $scope.register.username,
        email : $scope.register.email,
        password : $scope.register.password,
        phonenumber : $scope.register.phonenumber,
        termsnconditions : $scope.register.termsnconditions,
        source: $source,
      };

      if ($scope.registration.$valid) {
        $auth.signup(userinfo)
        .then(function(response) {
          $userinfo = response.data.response;
          if(response.data.message == 'success'){
            /*email */
            /*GA register user */


            // (This code for tracking user activity in GA)
              ga('create', 'UA-76409848-1', 'auto' , {userId: $userinfo.id} );
              ga('set', 'dimension2', $userinfo.id);
              ga('send', 'event', { eventCategory: 'Login', eventAction: 'Button Click', eventLabel: 'Sign-up'});
              _gaq.push(['trackPageView', 'login/register-here']);
              ga('send', 'pageview');

            /*GA register user end*/

            $rootScope.continueasguest =true;
          $scope.registrationParams = {
            type: 'registration',
            username: $userinfo.username,
            password: 'your password',
            firstname: $userinfo.first_name,
            email: $userinfo.email
          };
          $http.post('/api/send-email', $scope.registrationParams).success(function (data) {
              $scope.success_msg = data;
           })
          $scope.subscription={
              email: $userinfo.email,
            };
          $http.post('/api/subscribe/'+$userinfo.id, $scope.subscription).success(function (data) {
            if(data.succ_msg){
                $scope.messages = data.succ_msg;
            }
            else{
                 $scope.messages = data.error_msg;
            }
           })

          /*end email*/

          if ($document.find('.service-section').length && $source == 'PDP') {
            var user = JSON.stringify($userinfo);
            localStorage.setItem('user', user);
             submitPdp();
          }
          else {
              var $items = {
                phonenumber: $userinfo.phonenumber,
                id : $userinfo.id 
              };
              $state.go('otpverify', {items:$items});
          }
          localStorage.removeItem('source');
          $scope.closepopup();
           //   afterAuth();
            //$auth.login(userinfo).then(function(response){
            //});
          } else {
            mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
            $q.reject("Registration Failed");
          }
        }, function(error) {
          $rootScope.error =error;
          $rootScope.loginError = true;
        });
      }

    }
    $scope.closepopup = function () {
        $uibModalInstance.close();
    };

    var afterAuth = function(){
            $http.get('api/authenticate/user').then(function(response) {
            $rootScope.currentUser = response.data.user;
            var $user = JSON.stringify(response.data.user);
            localStorage.setItem('user', $user);
            $state.go('UserProfile');
            $scope.closepopup();

          }, function(error){
            $rootScope.loginError = true;
            $rootScope.loginErrorText = error.data.error;
          });

    };

    var submitPdp = function() {
            $timeout(function(){
              var ServiceSubmitButton = $document.find('#pdp-submit');
                if (ServiceSubmitButton.length) {
                   ServiceSubmitButton.click();
              }
            },150);
    };

  }]).
 directive('compareTo', function(){
    return {
      require : "ngModel",
      scope : {
        otherModelValue: "=compareTo",
      },
      link : function(scope, element, attributes, ngModel) {
        ngModel.$validators.compareTo = function (modelValue){
          return modelValue == scope.otherModelValue;
        }
        scope.$watch("otherModelValue", function(){
          return ngModel.$validate();
        });
      }
    };
  });
;
