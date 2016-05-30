/**
 *  Contact details
**/
app.controller('PartnerController',  function($state, $http, $rootScope, $scope,$auth) {

    $scope.save = function(){
         $scope.submitted = true;
        $http.post('/api/partner',$scope.newpartner).success(function (data) {
           
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

        })
       
    };

$scope.getService = function(){
    $http.get('/api/service').success(function (data) {

        $scope.services = data;
        console.log($scope.services);
    })
};

 

    $scope.update = function(index){
       
    };

    $scope.delete = function(index){
        
    };


});