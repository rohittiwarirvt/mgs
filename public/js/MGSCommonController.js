/**
* Name: MGSCommonController.js
* A common JS controller file for Global JS functions.
*
**/

app.controller('MGSCommonController',  function($state, $http, $rootScope, $scope,$auth, $uibModal,  $filter, $location,itemService,$anchorScroll, initializeRolePerms, RoleStore, $window, Flash) {
    $scope.showDiv = 'userprofile';
    $scope.path  = $location.path();
    $rootScope.user = null;
    $scope.messages = {};
    var roles = RoleStore.getStore();
    if($auth.isAuthenticated()) {
    $user = JSON.parse(localStorage.getItem('user'));
    if ($user !=null){
        $rootScope.user = $user;
        $scope.subscription={
            email: $rootScope.user.email,
            id: $rootScope.user.id,
        };}
  }

    $scope.getCities = function (){
        $http.get('/api/get_cities').success(function(data){
            $scope.cities=data;
            $rootScope.selectedCity = $scope.cities[0];
            $rootScope.selectedCityId = $scope.selectedCity.city_id;
        })
    };

    $scope.subscribe = function(){
        $scope.submitted = true;
        $scope.params={
        type: 'subscription',
        email: $scope.subscription.email,
        };
        $http.post('/api/subscribe/'+$scope.subscription.id, $scope.subscription).success(function (data) {
            if(data.succ_msg)
            {
               $scope.successmessages = data.succ_msg;
               $scope.errormessages = null;
               Flash.create('success', $scope.successmessages);
               ga('send', 'event', { eventCategory: 'Subscribe', eventAction: 'Email', eventLabel: 'Footer Button'});
                $http.post('api/send-email', $scope.params).success(function(data){
                    $scope.params = data;
                })
            }
        else
            {
               $scope.errormessages = data.error_msg;
               Flash.create('danger', $scope.errormessages);
               $scope.successmessages = null;
            }
        })
    };
    $scope.getServices = function (){
        $http.get('/api/get_services').success(function(data){
            serviceList= [];
            angular.forEach(data, function(value, key) {
                serviceName = (value.url).split('/service/');
                value.service_url = serviceName[1];
                serviceList.push(value);
            });
            $scope.services = serviceList;
            serviceObject = _.find($scope.services,function(rw){return rw.url == $scope.path });
            $scope.Selectedservice = serviceObject;
        })
    };


    $scope.getService = function(service){
        if($scope.serviceBookForm.$valid)
          $state.go('service', { serviceName: service.service_url });
    };

    $scope.openlogin = function () {
        var modalInstance = $uibModal.open({
            templateUrl: 'js/tpl/login.html',
            controller: 'AuthController',
            windowClass: 'login-section',
        });

    }
    $scope.opensignup = function () {
        var modalInstance = $uibModal.open({
            templateUrl: 'js/tpl/register.html',
            controller: 'RegistrationController',
            windowClass: 'signup-section',
        });
    }
    $scope.openforgot = function () {
        var modalInstance = $uibModal.open({
            templateUrl: 'js/tpl/ForgotPassword.html',
            controller: 'AuthController',
            windowClass: 'forgot-section',
        });
    }
    $scope.openreset = function () {
        var modalInstance = $uibModal.open({
            templateUrl: 'js/tpl/ResetPassword.html',
            controller: 'AuthController',
            windowClass: 'reset-section',
        });
    }

    $rootScope.isAuthenticated = function() {
        return $auth.isAuthenticated();
    };

    $rootScope.myAccount = [
                {'state' : 'UserProfile', 'text' :'My Profile'},

        ];
    var exists = false;
    angular.forEach(['Admin', 'Master Admin', 'CSR'], function(value) {
        if(!exists) {
            exists = roles.hasOwnProperty(value)
        }

    });

    if (exists) {
      $rootScope.myAccount.push(
        {'state' : 'quotelist', 'text' :'Manage Quote'},
        {'state' : 'newquote', 'text' :'Add Quote'},
        {'state' : 'adminuserpanel', 'text' :'Manage User'},
        {'state' : 'adminadduser', 'text' :' Add User'}
        );
    }
    $rootScope.logout = function() {
        $window.location.reload();
        localStorage.removeItem('user');
        $auth.logout();
        $state.go('home');
        initializeRolePerms.init();
    };

    $scope.window = $(window);
        function checkWidth() {
        if ($scope.window.width() >= 768) {
            var pageSize = 12;
        }
        else {
            var pageSize = 6;
        }
        var pagesShown = 1;
        $scope.items = itemService.getAll();
        $scope.itemsLimit = function() {
            return pageSize * pagesShown;
        };
        $scope.hasMoreItemsToShow = function() {
            return pagesShown < ($scope.items.length / pageSize);
        };
        $scope.showMoreItems = function() {
            pagesShown = pagesShown + 1;
        };
    }
    $scope.checkWidth = checkWidth();
    $(window).resize(checkWidth);

    $scope.$watchCollection('$stateParams', function() {
       $anchorScroll();
    });

    // $scope.$on('USER_LOGGED_IN', function(event, data){

    //  $scope.$emit('Event Received');
    // });
})
.factory('spinnerInterceptor', function ($q, $rootScope) {
    var interceptor = {
        'request': function (config) {
         $rootScope.loading = 1;
        // Successful request method
            return config; // or $q.when(config);
        },
        'response': function (response) {
         $rootScope.loading = 0;
        // successful response
            return response; // or $q.when(config);
        },
        'requestError': function (rejection) {
            $rootScope.loading = 0;
            // an error happened on the request
            // if we can recover from the error
            // we can return a new request
            // or promise
            return response; // or new promise
                // Otherwise, we can reject the next
                // by returning a rejection
                // return $q.reject(rejection);
        },
        'responseError': function (rejection) {
            $rootScope.loading = 0;
            // an error happened on the request
            // if we can recover from the error
            // we can return a new response
            // or promise
            return rejection; // or new promise
                // Otherwise, we can reject the next
                // by returning a rejection
                // return $q.reject(rejection);
        }
    };
    return interceptor;
});
app.filter('htmlfilter', function($sce) {
    return function(val) {
        return $sce.trustAsHtml(val);
    };
});
/* Panel Collpase */
app.directive('resize', function($window) {
            return function(scope, element) {
                    scope.getWindowDimensions = function() {
                        return {
                            'h': $(window).height(),
                            'w': $(window).width()
                        };
                    };
                    scope.$watch(scope.getWindowDimensions, function(newValue, oldValue) {
                        scope.windowHeight = newValue.h;
                        //console.log("scope.windowHeight:"+scope.windowHeight);
                        scope.windowWidth = newValue.w;
                        scope.small = false;
                        //for Mobile  & smaller devices
                        if (scope.windowWidth < 768) {
                            scope.small = true;
                            $('.custom-collapse .panel-collapse').collapse('hide');
                            $('.custom-collapse .panel-title').attr('data-toggle', 'collapse');

                        } else {
                            scope.small = false;
                            $('.custom-collapse .panel-collapse').addClass('collapse in');
                            $('.custom-collapse .panel-collapse').collapse('show');
                            $('.custom-collapse .panel-title').attr('data-toggle', '');
                        }
                        scope.style = function() {
                            return {
                                'height': (newValue.h) + 'px',
                                'width': (newValue.w) + 'px'
                            };
                        };
                    }, true);
        $(window).bind('resize', function () {
            scope.$apply();
        });

    }
});
app.factory('itemService', function() {
    return {
        getAll : function() {
            var items = [];
            for (var i = 1; i < 25; i++) {
                items.push('Item ' + i);
            }
            return items;
        }
    };
})
.factory('mgsCommonFactory', ['$log', 'Flash',  function($log, Flash) {
  return {
    loopErrorMessage : function ($error) {
        $error = JSON.parse($error);
        angular.forEach($error, function (value, key) {
            angular.forEach(value, function (error) {
                Flash.create('danger', error);
            });

        });
    },
  };
  }])
.directive('integer', function(){
    return {
        require: 'ngModel',
        link: function(scope, ele, attr, ctrl){
            ctrl.$parsers.unshift(function(viewValue){
                return parseInt(viewValue, 10);
            });
        }
    };
})
.directive('ngMin', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMin, function(){
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var minValidator = function(value) {
              var min = scope.$eval(attr.ngMin) || 0;
              if (!isEmpty(value) && value < min) {
                ctrl.$setValidity('ngMin', false);
                return undefined;
              } else {
                ctrl.$setValidity('ngMin', true);
                return value;
              }
            };

            ctrl.$parsers.push(minValidator);
            ctrl.$formatters.push(minValidator);
        }
    };
})
.directive('ngMax', function() {
    return {
        restrict: 'A',
        require: 'ngModel',
        link: function(scope, elem, attr, ctrl) {
            scope.$watch(attr.ngMax, function(){
                ctrl.$setViewValue(ctrl.$viewValue);
            });
            var maxValidator = function(value) {
              var max = scope.$eval(attr.ngMax) || Infinity;
              if (!isEmpty(value) && value > max) {
                ctrl.$setValidity('ngMax', false);
                return undefined;
              } else {
                ctrl.$setValidity('ngMax', true);
                return value;
              }
            };

            ctrl.$parsers.push(maxValidator);
            ctrl.$formatters.push(maxValidator);
        }
    };
})
.directive('mgsPaintingValid', ['$log', '$timeout', function( $log, $timeout) {
    return {
     restrict : 'EA',
     link : function (scope, element, attr) {
        $log.info(element);
        $timeout(function(){
            if (scope.serviceName == 'painting'){
                scope.PaintingValidate();
            }


            },150);
     }
    };
}])
.directive('mgsSaveEdit', ['$log', '$timeout', function( $log, $timeout) {
    return {
     restrict : 'EA',
     scope : {
        disabled: "=",
        updatequote: "&"
     },
     link : function (scope, element, attr) {
         $log.info(element);
        scope.disabledLink= true;
        var toggleDisabled = function() {
           scope.disabledLink = scope.disabledLink ? false : true;
        }

        element.find('.edit-link').on('click', function(){
            scope.disabled = false;
            toggleDisabled();
        });
        element.find('.save-link').on('click', function(){
            scope.disabled= true;
            toggleDisabled();
            scope.updatequote();
        });
     },
     template: "<span class='pull-right edit-link'  ng-show='disabledLink'>Edit</span> "+
            "<span class='pull-right save-link'  ng-show='!disabledLink'>Save</span>"
    };
}])
.service('initializeRolePerms', ['$rootScope', '$http', 'RoleStore', 'PermissionStore', '$window', '$rootScope', '$urlRouter',
 function($rootScope, $http, RoleStore, PermissionStore, $window, $rootScope, $urlRouter) {

    this.init = function(arg){
     $http
          .get('/api/get-role-with-perms')
          .then(function(response){
              if(response.data.message == 'success'){

                  PermissionStore.clearStore();
                  RoleStore.clearStore();
                  roleWithPerms = response.data.response;
                  angular.forEach(roleWithPerms, function(permissions, key) {
                    var permissionArray = permissionArray || [];
                    angular.forEach(permissions, function(permission) {
                        PermissionStore
                              .definePermission(permission.name, function () {
                                return true;
                              });
                    permissionArray.push(permission.name);

                    });
                    RoleStore.defineRole(key, permissionArray);
                  });
                var permissions = PermissionStore.getStore();
                var roles = RoleStore.getStore();
                if (arg != 'no-reload') {
                $window.location.reload();
                }

              }

          })
          .then(function(response){
             $urlRouter.sync();
            $urlRouter.listen();
            $rootScope.appReady = true;
          });
       };
}]);




function isEmpty(value) {
  return angular.isUndefined(value) || value === '' || value === null || value !== value;
}
app.directive('mgsTrackevent', function($timeout) {
    return {
        restrict: "A",
        link: function (scope, element, attr) {
            element.on('click', onClickServiceEvent);
            function onClickServiceEvent() {
                var label = element.find('h4.ng-binding').text();
                ga('send','event','link','click',label);
            }
        }
    }
});
