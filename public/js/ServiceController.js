app.controller('ServiceController',[ '$rootScope', '$scope', '$state', '$auth', '$log', 'serviceFactory',  '$stateParams','$location', '$uibModal', 'addQuoteFactory' , 'mgsCommonFactory', '$q', '$document', '$timeout', function($rootScope, $scope, $state, $auth , $log, serviceFactory, $stateParams, $location, $uibModal, addQuoteFactory, mgsCommonFactory , $q, $document, $timeout) {
    var $friendlyUrl = $location.path();
    $scope.pdpClass = $stateParams.serviceName;
    $scope.serviceName = $stateParams.serviceName.replace(/\-/g,' ');
    $scope.quote = [];
    $scope.SelectedServiceObj = [];
    $scope.booktime = new Date();
    $scope.showMeridian = true;
    $scope.prodAttributes = $scope.prodAttributes || [];
    $scope.attributeOptions = $scope.attributeOptions || [];
    $scope.noteOptions = [
      {name: 'Ask the representative to call me', text: 'Ask the representative to call me'},
      {name: 'Ask the representative to call me at morning time', text: 'Ask the representative to call me at morning time'},
      {name: 'Ask the representative to call me at evening time', text: 'Ask the representative to call me at evening time'},
      {name: 'Need more information', text: 'Need more information'},
      {name: 'Other', text: ''}
    ];

  var getLoggedInUser = function(){

    $userData = localStorage.getItem('user');
    if($auth.isAuthenticated() && $userData) {
    $rootScope.continueasguest = true;
      return $user = JSON.parse($userData);
    } else if($userData) {
          $rootScope.continueasguest = true;
      $user = JSON.parse(localStorage.getItem('user'));
      localStorage.removeItem('user');
      return  $user;
    }
  };


  var autoDataPopulate = function() {
    $user = getLoggedInUser();
    if ($user) {
      $scope.quote.name = $user.first_name;
      $scope.quote.phonenumber =  $user.phonenumber;
      $scope.quote.email = $user.email;
      $scope.quote.address1 = $user.address1;
      $scope.quote.pincode = $user.pincode;
    }

  }

  autoDataPopulate();

  $scope.products = function(){
    serviceFactory.getProduct($friendlyUrl)
    .then(function(response){
        $scope.$products = response.data;
         angular.forEach(response.data, initializeServiceStorage);

      },
       function(error){
        $log.error(error);
      });
  };


  var getService = function(params) {
      serviceFactory.getService(params)
       .then(function(response){
        $scope.service = response.data;
        $log.info(response.data);
        return response.data;
      },
       function(error){
        $log.error(error);
      });
    };

  $scope.service = getService({'url' :$friendlyUrl});
 // $log.info(service);

  $scope.serviceFaq = function(){
    serviceFactory.getServiceFaq($friendlyUrl)
    .then(function(response){
     $scope.faqs = response.data;
    });
  }

  $scope.serviceFeature = function(){
    var $friendlyUrl = $location.path();
    serviceFactory.getServiceFeature($friendlyUrl)
    .then(function(response){
     $scope.feature = response.data;

    });
  }

  $scope.serviceImage = function(){
    var $friendlyUrl = $location.path();
    serviceFactory.getServiceImage($friendlyUrl)
    .then(function(response){
     $scope.heroimages = response.data;

    });
  }

    $scope.multipleAttributes =[];
    $scope.singleAttribute = [];
    $scope.buildAttributes = function(product) {
        $log.info(product);
      serviceFactory.getAttributes(product)
        .then(function(response){
          $log.info(response.data);
          $scope.prodAttributes = addQuoteFactory.objUnion($scope.prodAttributes,response.data);
           $log.info(product);
          if(response.data.length){

            $scope.multipleAttributes[product] = true;
            $scope.singleAttribute[product] = false;
          } else {
            $scope.singleAttribute[product] = true;
            $scope.multipleAttributes[product] = false;
          }

          angular.forEach(response.data, createAtrributesArray);



        },
        function(error){
          $log.error(error);
        });

  };

  $scope.attributesModel = [];
  $scope.attributesPackageModel = [];
  $scope.optionsPackageModel = [];
  $scope.optionsModel = [];

  var createAtrributesArray = function( value, key) {
    $log.info(key);
    $log.info(value);
    $scope.attributesModel[value.pivot.product_id] = $scope.attributesModel[value.pivot.product_id] || [];


    $scope.attributesModel[value.pivot.product_id].push({id:value.id, label: value.attribute_name, 'description' : value.attribute_description, 'price' : value.price });
    serviceFactory.getOptions(value).then(function(response){
      $log.info('options');
      $log.info(response.data);
      $scope.attributeOptions = addQuoteFactory.objUnion($scope.attributeOptions,response.data);
      var option = response.data;
      if(option) {
        angular.forEach(response.data, createOptionsArray);
      }

    }, function(error) {

    });
  };

  var createOptionsArray = function(value, key) {
    $log.info("=========> " +key);
    $scope.optionsModel[value.attribute_id] = $scope.optionsModel[value.attribute_id] || [];
    $scope.optionsModel[value.attribute_id].push({id:value.id, label: value.option_name, 'description' : value.option_description, 'price' : value.price   });
    $scope.optionsPackageModel[value.attribute_id] = [];
  };



  $scope.$watch('attributesPackageModel', function(newvalue){
      showOptions();
      if ($scope.serviceName == 'painting') {
        $interior = _.find($scope.attributesPackageModel[14], { id: 118});
        if ($interior) {
          showPaintDropDown('remove');
        }
        else {
          showPaintDropDown('add');
        }
      }
  }, true);



  var showPaintDropDown = function(arg){
    $document.find('.form-group .product-attr').each(function(){
        var _this = $(this);
        if( !_this.hasClass('prod-id-14')) {
          arg == 'remove' ?  _this.removeClass('hideme') : _this.addClass('hideme');
        }
    });
  };

   $scope.PaintingValidate = function() {
    var $wrapper= $document.find('.quote-form-left');
    $thirdelement = $wrapper.find('.form-group:eq(3) ').length;
    $log.info($thirdelement);
    if ($thirdelement) {
      showPaintDropDown('add');
    };
  }

  var showOptions = function(){
    $log.info($scope.attributesPackageModel);
      $scope.optionsToShow =  $scope.optionsToShow || {};
      angular.forEach($scope.optionsModel, function(value, key) {
        var toSearch = {'id': key};
        var $isPresent = false;
        angular.forEach($scope.attributesPackageModel, function(value1, key1) {
          $log.info($scope.attributesPackageModel[key1].length);
             var $sel = _.find($scope.attributesPackageModel[key1], toSearch );
            $log.info($sel);
            $log.info(value1);
            if ($sel) {
              $isPresent = true;
              $scope.optionsToShow[key] = $scope.optionsModel[key];
            }
            else if($scope.optionsToShow[key] && !$isPresent) {
              delete $scope.optionsToShow[key];
            }

            $scope.optionLabels = _.find($scope.attributesModel[key1],toSearch);
            $log.info($scope.optionLabels);
            if($scope.optionLabels) {
              $scope.optionsTranslationTexts[$scope.optionLabels.id] = { buttonDefaultText:$scope.optionLabels.label};
            }

        });
      });

    if(_.isEmpty($scope.optionsToShow)) {
      $scope.toshowOptions = false;
    } else {
      $scope.toshowOptions = true;
    }
  };
  $scope.showOptions = showOptions();
  $log.info($scope.attributesModel);
  var initializeServiceStorage = function(key, value) {
    $scope.attributesPackageModel[key.id] = [];
  };

  // for single select list
   $scope.toggleSelection = function toggleSelection(values) {
     idx = $scope.isExist($scope.attributesPackageModel[values], { 'id' : values});

    // is currently selected
    if (idx) {
      $scope.attributesPackageModel[values] = [];
    }
    // is newly selected
    else {
      $scope.attributesPackageModel[values].push({ 'id' : values});
    }
    $log.info($scope.attributesPackageModel);
  };

  $scope.translationtexts ={ buttonDefaultText:'Select Package'};

  $scope.optionsExtraSettings = { showCheckAll:false, showUncheckAll:false,  scrollable : true, scrollableHeight : '250px', smartButtonMaxItems : 3, selectionLimit: 1};
  $scope.optionsTranslationTexts = $scope.optionsTranslationTexts || [];
  $scope.optionLabels = $scope.optionLabels  || {};
  $scope.optionsTranslationTexts[$scope.optionLabels.id] = { buttonDefaultText:$scope.optionLabels.label};
  // quotation form
  $scope.ismeridian = true;
  var Today = new Date();
  var timestamp = new Date().getTime();
  $scope.appointmentTime = Today;
  $scope.appointmentDate = Today;
  $scope.hstep = 1;
  $scope.mstep = 15;

  $scope.extrasettings = [];
  var defaultExtraSettings = { showCheckAll:false, showUncheckAll:false,  scrollable : true, scrollableHeight : '250px', smartButtonMaxItems : 3 };
  $scope.makeSingleSelectionForProducts = function(product) {
      var productId = product.id;
      if (product.display_type == 'radio') {
        $scope.extrasettings[productId] = angular.extend({}, defaultExtraSettings, { selectionLimit: 1 });
      }
      else {
        $scope.extrasettings[productId] = defaultExtraSettings;
    }
  };

  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  }

  $scope.dateOptions = {
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.datepicker = {
    opened: false
  };

  $scope.openDate = function() {
    $scope.datepicker.opened = true;
  };



 $scope.isExist = function(array, object) {
   idx = _.find(array, object);
     if(idx) {
       return true;
     }
     else {
       return false;
     }
 };

  var servicesSelectedBoolean = function() {

    if (!$scope.SelectedServiceObj.length) {
      $scope.servicenotselected = true;
    }
    else {
       $scope.servicenotselected = false;
    }
  }
  var populateServiceObj = function() {
        var serviceInfo = [];
    $log.info($scope.optionsPackageModel);
     angular.forEach($scope.attributesPackageModel, function(attributes, productId) {
       angular.forEach(attributes, function(attribute, key) {
            var serviceInfoObj = {
                  'service_id' : $scope.service.id,
                  'product' : _.find($scope.$products, { 'id': productId }),
                  'attribute' : _.find($scope.prodAttributes, { 'id': attribute.id}),
                  };
                serviceInfo.push(serviceInfoObj);
            });

       });

      angular.forEach($scope.optionsPackageModel, function(option, attributeId) {
          $log.info("==> option" + option);
          $log.info("===> attributeId" + attributeId);
          $log.info("===> serviceInof" + serviceInfo);
          $index = _.findIndex(serviceInfo, {'attribute':{'id' : attributeId}});

          if ($index !== -1 && !_.isEmpty(option)) {
            //var $test = _.find($scope.attributeOptions, { 'id': option.id });
            serviceInfo[$index].option = _.find($scope.attributeOptions, { 'id': option[0].id });
          }
      });
      $scope.SelectedServiceObj = serviceInfo;
  }

  $scope.submitted = false;
  $scope.getQuote = function(){
    $scope.submitted = true;
    if(!$scope.quote.specialInstructions){
      $scope.quote.specialInstructions = $scope.quote.other;
    }

    var userparams = {
          phonenumber :$scope.quote.phonenumber,
          email : $scope.quote.email
    };

    var userparamsExtended =    {
          first_name : $scope.quote.name,
          address1 : $scope.quote.address1,
          pincode : $scope.quote.pincode,
    };
    userparamsExtended = angular.extend(userparamsExtended, userparams);
    var appointmentDate = $scope.appointmentDate;
    var appointmentTime = $scope.appointmentTime;
    var quoteSourceId = 1;
    var specialInstructions  = $scope.quote.specialInstructions;

    populateServiceObj();
    servicesSelectedBoolean();
    serviceInfo = $scope.SelectedServiceObj;
    serviceInfo = JSON.stringify(serviceInfo);
    $log.info(serviceInfo);
    userinfo = JSON.stringify(userparamsExtended);

    $scope.showContinueAsGuest = $scope.quotation.$valid && !$scope.servicenotselected && !$rootScope.continueasguest;
    if ($scope.showContinueAsGuest) {
      localStorage.setItem('source','PDP');
      $timeout(function() {
        $document.find('.continueasguest-click').click();
      },150);
    }

    if ($scope.quotation.$valid && !$scope.servicenotselected && $rootScope.continueasguest) {
      $log.info(appointmentDate);
      $log.info(appointmentTime);
      $log.info(serviceInfo);
      $log.info(userinfo);
      $log.info($scope.attributesPackageModel);
      params = {
        'user_information': userinfo,
        'quote_service_info': serviceInfo,
        'appointment_date':appointmentDate,
        'appointment_time':appointmentTime,
        'quote_source_id': 1,
        'special_instruction': specialInstructions
       };
    var custom_user;
    custom_user = getLoggedInUser();
       if (custom_user){

         $scope.usertype = custom_user.user_type;
         params = angular.extend(params, {'user_id' :custom_user.id, 'created_by' : custom_user.id, 'updated_by' : custom_user.id });
         $scope.username = custom_user.username;

        return createQuoteCall(params);
       }
       else {
          serviceFactory.checkIfUserExists(userparams)
            .then(function(response) {
              var data = response.data;
              if(data.message == 'success'){
                data = data.response;
                $rootScope.currentUser = data;
                $log.info(response.data);
                $scope.usertype = response.data.user_type;
                params = angular.extend(params, {'user_id' :data.id, 'created_by' : data.id, 'updated_by' : data.id });
                $scope.username = data.username;
                return createQuoteCall(params);
              }
              else {

               var registerInfo = {
                   username :$scope.quote.email,
                   first_name : $scope.quote.name,
                   password :'G3stUs3R',
                   user_type : 'guest',
                   'status' : 1,
                   source: 'PDP',
                   };
                   $scope.usertype = "guest";
                    $scope.username = $scope.quote.email;

                 registerInfo  = angular.extend(registerInfo, userparams);
                  $auth.signup(registerInfo)
                    .then(function(response) {
                      $log.info('response');
                      if(response.data.message == 'success'){
                          $rootScope.currentUser = response.data.response;
                          var user_data = response.data.response;
                          params = angular.extend(params, {'user_id' : user_data.id,  'created_by' : user_data.id, 'updated_by' : user_data.id });
                          return createQuoteCall(params).then(function(data){
                              var visitor = {
                                'user_id' : user_data.id,
                                'quote_id' : data.id,
                                'customer_id' : $scope.quote.email + '|' + timestamp,
                             }
                              $log.info(visitor);
                              return createVisitor(visitor);

                          });
                      } else {
                        mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
                        $q.reject("Registration Failed");
                      }


              }, function(error){
                $scope.error =error;
                $scope.loginError = true;
              })
              }
            },
             function(error){
              $log.error(error);
            });

       }
    }

  };

  $scope.getAttributeLabel = function(id) {
    id = parseInt(id);
    $scope.optionAttributeLabel = $scope.optionAttributeLabel || [];
    $scope.optionAttributeLabel[id] = _.find($scope.prodAttributes, { 'id': id});
  }
  var createQuoteCall = function(parameter){
      return serviceFactory.createQuote(parameter)
      .then(function(response){
         if ( response.data.message == 'success') {
            var $data = response.data.response;
            $log.warn(parameter);
            var email_params = {
              type :'quote-submit',
              email : $scope.quote.email,
              userid :$data.user_id,
              username : $scope.quote.email
            };
            //serviceFactory.sendEmail(email_params);

            var thanksParams = {
              'quote_id':$data.id,
              'user_id' : $data.user_id,
            }
          ga('send', 'event', { eventCategory: 'Appointment ', eventAction: 'Book Service', eventLabel: $scope.service.service_name});
            openThankYou(thanksParams);
            return response.data.response;
         } else {
            mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
            $q.reject("Create Quotation Failed");
        }
      },
       function(error){
        $log.error(error);
      });
    }

  var createVisitor = function(parameter) {
    $log.info(parameter);
     return serviceFactory.createVisitor(parameter).then(function(response){
        if(response.data.message == 'success'){
          return response.data.response;
        } else {
          mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
          $q.reject("Create Visitor Failed");
        }
      },
       function(error){
        $log.error(error);
      });
  };

  var openThankYou = function(parameters) {
    var $items = {
            username: $scope.username,
            servicename : $scope.serviceName ,
            usertype : $scope.usertype,
            user_id: parameters.user_id,
          };
    $state.go('thank-you', {items:$items});
  };



}])
.factory('serviceFactory', ['$http','SERVICE_CONFIG', '$log',  function($http, SERVICE_CONFIG,  $log){
  return {
    getProduct : function (friendlyUrl) {
       return $http.get(SERVICE_CONFIG.getServiceProduct, {
                  params: {
                  url: friendlyUrl
                  }
                });
    },
    getAttributes : function(product) {
       return $http.get(SERVICE_CONFIG.getProductAttributes,  {
                  params: {
                  product_name: product
                  }
                });
    },
    getOptions : function(attribute) {
      return $http.get(SERVICE_CONFIG.getAttributesChoice,  {
                  params: attribute
                });
    },
    createQuote :function(param) {
       return $http({method: "post",
                    url: SERVICE_CONFIG.createQuote ,
                    data: param
                  });
    },
    checkIfUserExists :function(param) {
       return $http.get(SERVICE_CONFIG.checkIfUserExists,  {
                  params: param
                });
    },

    getServiceFaq :function(friendlyUrl) {
       return $http.get(SERVICE_CONFIG.getServiceFaq,  {
                  params: {
                    url: friendlyUrl
                  }
                });
    },
    getService :function(param) {
       return $http.get(SERVICE_CONFIG.getService,  {
                  params: param
                });
    },
    createVisitor : function(params) {
      return $http({
                    method: "post",
                    url: SERVICE_CONFIG.getVisitor ,
                    data: params
                  });
    },
    getServiceFeature :function(friendlyUrl) {
       return $http.get(SERVICE_CONFIG.getServiceFeature,  {
                  params: {
                    url: friendlyUrl
                  }
                });
    },
    getServiceImage :function(friendlyUrl) {
       return $http.get(SERVICE_CONFIG.getServiceImage,  {
                  params: {
                    url: friendlyUrl
                  }
                });
    },
    sendEmail : function(params) {
      return $http({
                    method: "post",
                    url: SERVICE_CONFIG.sendEmailApi ,
                    data: params
                  });
    },
  };

}])
.constant('SERVICE_CONFIG', {
   getServiceProduct: '/api/services-with-product',
   getProductAttributes: 'api/product-with-attributes',
   getAttributesChoice: '/api/attributes-with-options',
   createQuote: 'api/quotes',
   checkIfUserExists : '/api/authenticate/user-exists',
   registerUser : '/api/register',
   getServiceFaq: '/api/service-faq',
   getService:'/api/service',
   getVisitor: '/api/visitor',
   getServiceFeature: '/api/service-feature',
   getServiceImage: '/api/service-image',
   sendEmailApi: '/api/send-email'
});
