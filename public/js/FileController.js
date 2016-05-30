app.controller('ProfileSettingsController', ['$scope', 'fileUpload', function($scope, fileUpload){
            $scope.uploadFile = function(){
               var file = $scope.myFile;

               console.log('file is ' );
               console.dir(file);

               var uploadUrl = "http://dev.mgs.local/api/file";
               fileUpload.uploadFileToUrl(file, uploadUrl);
            };
         }]);

app.directive('fileModel', ['$parse', function ($parse) {
            return {
               restrict: 'A',
               link: function(scope, element, attrs) {
                  var model = $parse(attrs.fileModel);
                  var modelSetter = model.assign;

                  element.bind('change', function(){
                     scope.$apply(function(){
                        modelSetter(scope, element[0].files[0]);
                     });
                  });
               }
            };
         }]);
app.service('fileUpload', ['$http', function ($http) {
            this.uploadFileToUrl = function(file, uploadUrl){
               var fd = new FormData();
               fd.append('file', file);
              // console.log(file);
               $http.post(uploadUrl,JSON.stringify(file), {
                  transformRequest: angular.identity,
             //     headers: {'Content-Type': undefined}
               })

               .success(function(data){
              //  console.log(data);
               })

               .error(function(error){
                console.log(error);
               });
            }
         }]);
