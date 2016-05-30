/**
 *  Contact details
**/
app.controller('UnsubscribeController',  function($state, $http, $rootScope, $scope,$auth, $stateParams) {

    $scope.messages = {}; 

    $scope.unsubscribe = {
    email: $stateParams.email
    }   

    $scope.display = function (){

        $http.get('/api/unsubscribe').success(function(data){

            $scope.subscriptions=data;
        })
    };

    $scope.delete = function(index){
        $http.post('/api/unsubscribe', $scope.unsubscribe).success(function (data) {
            if(data.succ_msg){
             $scope.successmessages = data.succ_msg; 

            }
        
    
        });
    }

  
    $scope.display();

});