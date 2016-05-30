app.controller('OtpVerificationController',  function($stateParams, $state, $http, $rootScope, $scope, $sce) {

    $scope.messages = {};
    var otpdata = {};

    var $otpinfo = $stateParams.items;
    var $currentState = localStorage.getItem('stateparams');
    if ($otpinfo) {
     $params = JSON.stringify($otpinfo);
     localStorage.setItem('stateparams', $params);
    }
    else if( $currentState ) {
     $otpinfo = JSON.parse($currentState);
    }
 
    $scope.$on("$destroy", function(){
      localStorage.removeItem('stateparams');
    });

    $scope.verifyOtp = function (){
        otpdata.items = $otpinfo.phonenumber; 
        otpdata.id = $otpinfo.id;
        otpdata.otp = $scope.otpverify.otp;
        $http.post('/api/verify-otp', otpdata).success(function(data){
        if (data.success){
                $scope.success_messages = $sce.trustAsHtml(data.success);
                $scope.regenerateOTP = false;
            }else {
                $scope.error_messages =  $sce.trustAsHtml(data.error);
            }
        })
    };

    $scope.regerateOTP = function (){
        otpdata.phonenumber = $otpinfo.phonenumber;
        $http.post('/api/generate-otp', otpdata).success(function(data){
            if (data.success){
                    $scope.success_messages = $sce.trustAsHtml(data.success);
                    $scope.regenerateOTP = true;
            }else {
                    $scope.error_messages =  $sce.trustAsHtml(data.error);
            }
        })
    };

});
