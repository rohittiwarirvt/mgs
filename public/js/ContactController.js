/**
 *  Contact details
**/
app.controller('ContactController',  function($state, $http, $rootScope, $scope,$auth) {

    $scope.save = function(){
        $scope.submitted = true;
        $scope.enquiry={
        type: 'enquiry',
        customername: $scope.newcontact.name,
        email: $scope.newcontact.email,
        customerphone: $scope.newcontact.phonenumber,
    };
        $http.post('/api/contact',$scope.newcontact).success(function (data) {
            if(data.succ_msg)
            {   
               $scope.successmessages = data.succ_msg;
               $scope.errormessages = null;
            }
        else
            {   
               $scope.errormessages = data.error_msg;
               $scope.successmessages = null;
            }

            $http.post('api/send-email', $scope.enquiry).success(function (data) {
                $scope.success = data;

            })

        })
       
    };

    //$scope.reset();

    $scope.update = function(index){
       
    };

    $scope.delete = function(index){
        
    };


});