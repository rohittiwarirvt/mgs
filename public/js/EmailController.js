/**
 *  Contact details
**/
app.controller('EmailController', function($state, $http, $rootScope, $scope,$auth, $stateParams) {

    $scope.messages = {}; 
   

    $scope.display = function (){

        $http.get('/api/templates').success(function(data){

            $scope.templates=data;
        });
    }

    $scope.add = function(index){
        $http.post('/api/email-template', $scope.template).success(function (data) {
            if(data.succ_msg){
             $scope.messages = data.succ_msg; 

            }
        
    
        });
    }

    $scope.addnew = function(index){
         $state.go('add-template');

    }

  
    $scope.display();

});