

/**
 * Created by andrea.terzani on 13/07/2015.
 */

app.controller('AuthController',  function($auth, $state,$http,$rootScope, $scope, $stateParams, $uibModalInstance, initializeRolePerms, RoleStore,$document, $timeout, $sce, Flash) {

    $scope.email='';
    $scope.password='';
    $scope.newUser={};
    $scope.loginError=false;
    $scope.loginErrorText='';
    $scope.messages= {};

    if($auth.isAuthenticated()) {
        var $user = JSON.parse(localStorage.getItem('user'));
        $scope.forgotParams={
        type: 'forgot-password',
        firstname: $user.first_name,
        email: $user.email,
        };
    }

        $scope.login = function() {

            var credentials = {
                username: $scope.username,
                password: $scope.password
            }

            $auth.login(credentials).then(function(response) {
                var data = response.data;
                if (!data.error){
                    $http.get('api/authenticate/user').then(function(response) {

                    /*GA Login code*/
                    ga('create', 'UA-76409848-1', 'auto' , {userId: response.data.user.id} ); // (This code should appear when someone is logged in)
                    if (response.data.user.roles['Individual Customer']){
                        ga('set', 'dimension2', response.data.user.id);
                    }
                    else if (response.data.user.roles['CSR']){
                        ga('set', 'dimension1', response.data.user.id);;
                    }
                    ga('send', 'event', { eventCategory: 'Login', eventAction: 'Button Click', eventLabel: 'Sign-in'});
                   _gaq.push(['trackPageView', 'login/book-now']);
                    ga('send', 'pageview');
                    /* GA login code end*/

                    $rootScope.currentUser = response.data.user;
                    $rootScope.loginError = false;
                    $rootScope.loginErrorText = '';
                    var user = JSON.stringify(response.data.user);
                    localStorage.setItem('user', user);
                    $source = localStorage.getItem('source');
                    if ($document.find('.service-section').length && $source=='PDP') {
                        submitPdp();
                    } else {
                      initializeRolePerms.init();
                      $state.go('home');
                    }
                    $scope.closepopup();
                    $rootScope.continueasguest =true;
                    localStorage.removeItem('source');
                    }, function(error){
                      $scope.loginError = true;
                      $scope.loginErrorText = $sce.trustAsHtml(error.data.error);
                    });
                } else {
                    $scope.loginError = true;
                    $scope.loginErrorText = $sce.trustAsHtml(data.error);
                    $scope.userNumber = data.phonenumber;
                    $scope.id = data.id;
                }
            }, function(error) {
                $scope.loginError = true;
                $scope.loginErrorText = $sce.trustAsHtml(error.data.error);

            });
        }

        $scope.forgotpassword = function(){
            $scope.submitted = true;
            if($scope.ForgotPassword.$valid){
                if($scope.forgot.email){
                    $http.post('password/email', $scope.forgot).success(function(data){
                        if(data.message){
                            $scope.successmessages = data.message;
                            $scope.errormessages =  null;
                        }
                        else{
                            $scope.errormessages = data.messages;
                            Flash.create('danger', $scope.errormessages);
                            $scope.successmessages = null;
                        }
                    })
                }
                if($scope.forgot.phonenumber){
                    $http.post('api/otp-forgot-password', $scope.forgot).success(function(data){
                        if(data.otp_success){
                            $scope.otpsuccess = data.otp_success;
                            $scope.otpfail = null;
                        }
                        else{
                            $scope.otpfail =  data.otp_fail;
                            Flash.create('danger', $scope.otpfail);
                            $scope.otpsuccess =  null;
                        }
                    })
                }
                $http.post('api/send-email', $scope.forgotParams).success(function(data){
                })
            }
        };

        $scope.closepopup = function () {
            $uibModalInstance.close();
        };

         $scope.openOTP = function (phonenumber, id) {
            $uibModalInstance.close();
            var $items = {
                phonenumber: phonenumber,
                id : id
            };
            $state.go('otpverify', {items:$items});
        };

        $scope.continueasguest = function () {
            _gaq.push(['trackPageView', 'login/continue-as-guest']);
            _paq.push(['trackEvent', 'Login', 'Button Click', 'Continue as Guest']);
            ga('create', 'UA-76409848-1', 'auto');
            ga('set', 'dimension3', 0);
            ga('send', 'event', { eventCategory: 'Login', eventAction: 'Button Click', eventLabel: 'Continue as Guest'});
            ga('send', 'pageview');
            $rootScope.continueasguest = true;
            $uibModalInstance.close();
             submitPdp();
       };

  var submitPdp = function() {
            $timeout(function(){
              var ServiceSubmitButton = $document.find('#pdp-submit');
                if (ServiceSubmitButton.length) {
                   ServiceSubmitButton.click();
              }
            },150);
       };

});


