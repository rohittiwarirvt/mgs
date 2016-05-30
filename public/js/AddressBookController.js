app.controller('AddressBookController',  function($state, $http, $rootScope, $scope,$auth) {

    $scope.messages = {}; 
    
    $scope.getCountries = function (){
        $http.get('/api/get_countries').success(function(data){
            $scope.countries=data;
            $scope.selectedCountry = $scope.countries[0];
        })
        
    };

    $scope.getStates = function (){
        $http.get('/api/get_states').success(function(data){
            $scope.states=data;
            $scope.selectedState = $scope.states[0];
        })
    };

    $scope.getCountries();
    $scope.getStates();

    $scope.display = function () {

        $http.get('/api/addressbook').success(function(data){
            $scope.details=data;
        })
    };

    $scope.save = function(){
        $http.post('/api/addressbook',$scope.addressbook).success(function (data) {
            if(data.error_msg)
            {   
               $scope.messages = data.error_msg;
            }
            else
            {
               $scope.messages = data.succ_msg;
            }

        })
       
    };

    $scope.update = function(index){
       
    };

    $scope.delete = function(index){
        
    };

    $scope.display();

});