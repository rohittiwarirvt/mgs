app.controller('AddQuoteController',[ '$rootScope', '$scope', '$state',  '$auth', '$log', 'serviceFactory',  '$stateParams','$location', '$filter', 'Flash', 'addQuoteFactory', 'quoteFactory',  'mgsCommonFactory', '$q', function($rootScope, $scope, $state,  $auth , $log, serviceFactory, $stateParams, $location, $filter , Flash, addQuoteFactory, quoteFactory, mgsCommonFactory, $q) {
  $scope.quote = $scope.quote || {};
  $scope.quote.price = 0;
  $scope.booktime = new Date();
  $scope.prodAttributes = $scope.prodAttributes || [];
  $scope.attributeOptions = $scope.attributeOptions || [];
  $scope.optionsNotPresent = true;
  $scope.materials = $scope.materials || [];
  var Today = new Date();
  $scope.quote.date  = $filter('date')(Today, 'd/M/yyyy');
  $scope.ismeridian = true;
  var timestamp = new Date().getTime();
  $scope.quote.appointmentTime = Today;
  $scope.quote.appointmentDate = Today;
  $scope.submitted = false;

  if($auth.isAuthenticated()) {
    $scope.$loggedInUser = JSON.parse(localStorage.getItem('user'));
  }
  $scope.getAttributeLabel = function(id) {
    id = parseInt(id);
    $scope.optionAttributeLabel = $scope.optionAttributeLabel || [];
    $scope.optionAttributeLabel[id] = _.find($scope.prodAttributes, { 'id': id});
  }

  $scope.getCustomerInfo = function(userparams){
  var userparams = {
          phonenumber :$scope.quote.phonenumber,
          email : $scope.quote.email
    };




  if (userparams.phonenumber || userparams.email) {
    serviceFactory.checkIfUserExists(userparams)
        .then(function(response){
            var response_data = response.data;
          if(response_data.message == 'success'){
              data = response_data.response;
              $scope.quote.email = data.email;
              $scope.quote.phonenumber = data.phonenumber;
              $scope.quote.address1 = data.address1 + ' ' + data.address2;
              $scope.quote.pincode = data.pincode;
              $scope.quote.firstName = data.first_name;
              $scope.quote.lastName = data.last_name;
               Flash.create('info', "User Exists");
            }
            else {
              Flash.create('info', "User does not exist");
            }
          }, function(error) {
            $log.error(error);
          });
  }
  else {
      Flash.create('danger', "Please Enter User Details");
  }



  };

  // date ka bhai log
  $scope.dateOptions = {
    dateDisabled: disabled,
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.units = ['no', 'feet', 'km', 'm', 'kg', 'pt', 'l', 'unit'   ];


  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  }

  $scope.datepicker = {
    opened: false
  };

  $scope.openDate = function() {
    $scope.datepicker.opened = true;
  };

  // services
  addQuoteFactory.getServices()
  .then(function(response){
    $log.info("services=====>");
    $log.info(response);
    $scope.services = response.data;
  }, function(error) {
    $log.error("error=======>" +error);
  });


  $scope.products = function(){
    $scope.serviceSpecs = [];
    $scope.serviceGroupBySpecs = [];
    $scope.attributesPackageModel = [];
    $scope.optionsPackageModel = {};
    $friendlyUrl = $scope.service.url;
    serviceFactory.getProduct($friendlyUrl)
    .then(function(response){

        $scope.$products = response.data;
         angular.forEach(response.data, initializeServiceStorage);

      },
       function(error){
        $log.error(error);
      });
  };

    var initializeServiceStorage = function(key, value) {
    $scope.attributesPackageModel[key.id] = [];
  };

    $scope.multipleAttributes =[];
    $scope.singleAttribute = [];
    $scope.buildAttributes = function(product) {
        $log.info(product);
      serviceFactory.getAttributes(product)
        .then(function(response){
          $log.info(response.data);

          $log.info(product);
          $scope.prodAttributes = addQuoteFactory.objUnion($scope.prodAttributes,response.data);
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
  $scope.optionsPackageModel = {};
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
    $scope.optionsModel[value.attribute_id].push({id:value.id, label: value.option_name  });
    $scope.optionsPackageModel[value.attribute_id] = [];
  };



  $scope.$watch('attributesPackageModel', function(){
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
    var $wrapper= $document.find('.subservices');
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
  };

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

  $scope.translationtexts ={ buttonDefaultText:'Select Package'};

  $scope.optionsExtraSettings = { showCheckAll:false, showUncheckAll:false,  scrollable : true, scrollableHeight : '250px',  smartButtonMaxItems : 3 };
  $scope.optionsTranslationTexts = $scope.optionsTranslationTexts || [];
  $scope.optionLabels = $scope.optionLabels  || {};
  $scope.optionsTranslationTexts[$scope.optionLabels.id] = { buttonDefaultText:$scope.optionLabels.label};

  // service charges
  $scope.$watch('attributesPackageModel', function() {
      populateServices();
  }, true);


  var populateServices = function () {
  var serviceInfo = [];
    angular.forEach($scope.attributesPackageModel, function(attributes, productId) {
       angular.forEach(attributes, function(attribute, key) {
            var serviceInfoObj = {
                  'service_id' : $scope.service.id,
                  'product_id' : productId,
                  'attribute_id' : attribute.id,
                  };
                serviceInfo.push(serviceInfoObj);
            });

       });

      angular.forEach($scope.optionsPackageModel, function(option, attributeId) {
          $log.info("==> option" + option);
          $log.info("===> attributeId" + attributeId);
          $log.info("===> serviceInof" + serviceInfo);
          $index = _.findIndex(serviceInfo, {'attribute_id' : attributeId});

          if ($index !== -1 && !_.isEmpty(option)) {
            $scope.optionsNotPresent = false;
            serviceInfo[$index].option = _.find($scope.attributeOptions, { 'id': option.id });
          }
      });

    createServiceCharges(serviceInfo);
  };

  var createServiceCharges = function(serviceInfoObj){
    var serviceCompleteInfo = [];

      angular.forEach(serviceInfoObj, function(serviceInfo, key) {
        var productObj = _.find($scope.$products, { 'id': serviceInfo.product_id });
        var attributeObj = _.find($scope.prodAttributes, { 'id': serviceInfo.attribute_id});
        var optionObj = _.find($scope.attributeOptions, { 'id': serviceInfo.option_id});

        if (!angular.isUndefined(productObj) ) {
          productObj.price = _.toNumber(productObj.price);
        }

        if ( !angular.isUndefined(attributeObj) ) {
          attributeObj.price = _.toNumber(attributeObj.price);
        }
          if ( !angular.isUndefined(optionObj) ) {
            optionObj.price = _.toNumber(optionObj.price);
          }
        var serviceInfoObj = {
          'product' : productObj,
          'attribute' :attributeObj ,
          'option' : optionObj,
        };

      serviceCompleteInfo.push(serviceInfoObj);

      });

      $log.info("*******serivcerender*******");
      $log.info($scope.$products);
      $log.info($scope.prodAttributes);
      $log.info($scope.attributeOptions);
      $log.info(serviceCompleteInfo);
      populateServiceData(serviceCompleteInfo);

  };

  var populateServiceData = function (data) {
      $subservice = [];
      var service_type = null;
      $scope.serviceIndex = 0;
      if (data){
         $scope.serviceSpecs =  addQuoteFactory.arrayOfObjMerge($scope.serviceSpecs, data);
      }
        angular.forEach($scope.serviceSpecs, function(service, key) {

          if (service.option ) {
            var $contextObj = $scope.serviceSpecs[key].option;
            $contextObj.quantity = isNaN(parseInt(service.option.quantity)) || service.option.quantity == 0 ? 1 : parseInt(service.option.quantity);
            $contextObj.unit =  service.option.unit;
            $contextObj.price = isNaN(parseInt($contextObj.price)) ? 0 : $contextObj.price;
          }
          else if(!service.attribute && service.product) {
            var $contextObj = $scope.serviceSpecs[key].product;
            $contextObj.price = isNaN(parseInt($contextObj.price)) ? 0 : $contextObj.price;
            $contextObj.quantity = isNaN(parseInt(service.product.quantity)) || service.product.quantity == 0  ? 1 : parseInt(service.product.quantity);
            $contextObj.unit =  service.product.unit;
          } else if(service.attribute) {
            var $contextObj = $scope.serviceSpecs[key].attribute;
            $contextObj.price = isNaN(parseInt($contextObj.price)) ? 0 : $contextObj.price;
            $contextObj.quantity = isNaN(parseInt(service.attribute.quantity)) || service.attribute.quantity == 0  ? 1 : parseInt(service.attribute.quantity);
            $contextObj.unit =  service.attribute.unit;
          }

          if(service.hasOwnProperty('option') && service.option) {
            $scope.optionsNotPresent = false;
          }

          //discount
        });

        if ($scope.serviceSpecs[0] && $scope.serviceSpecs[0].option) {
          $scope.serviceGroupBySpecs = _.groupBy( $scope.serviceSpecs, "attribute.attribute_name");

        } else {
          $scope.serviceGroupBySpecs = _.groupBy( $scope.serviceSpecs, "product.product_name");
        }

  };

  //

  // add quote
 $scope.addQuote = function(status) {
   $scope.submitted = true;

    if ($scope.serviceSpecs && !$scope.serviceSpecs.length) {
      $scope.servicenotselected = true;
    }
    else {
       $scope.servicenotselected = false;
    }
   if ($scope.customereDetail.$valid && $scope.customereDetail.$valid && !$scope.servicenotselected) {
      var userparams = {
              phonenumber : $scope.quote.phonenumber,
              email : $scope.quote.email,
              first_name : $scope.quote.firstName,
              address1 : $scope.quote.address1,
              pincode :  $scope.quote.pincode,
              last_name : $scope.quote.lastName
        };
      $scope.status_id = null;
      if (angular.isNumber(+status)) {
        $scope.status_id = status;
      }

      var params =  {
          user_information :JSON.stringify(userparams),
          appointment_date : $scope.quote.appointmentDate,
          appointment_time : $scope.quote.appointmentTime,
          status_id : $scope.status_id ,
          quote_source_id: 3,
          created_by : $scope.$loggedInUser.id,
          updated_by : $scope.$loggedInUser.id
      };

      calculatePrice();
      $log.info($scope.quote.price);
      params = angular.extend( params, {
                              quote_service_info : JSON.stringify($scope.serviceSpecs),
                              quote_price : $scope.quote.price,
                              quote_material_info : JSON.stringify($scope.materials),
                              quote_price : $scope.quote.price,
                              vat : $scope.VAT,
                              service_tax : $scope.serviceTax,
                              labour_charges :$scope.labourCharges
                               });
      userCreateOrUpdate(params);
    $state.go('quotelist');
    }

  };

  var userCreateOrUpdate = function(params) {
    var userparams = JSON.parse(params.user_information);
    serviceFactory.checkIfUserExists({ email : $scope.quote.email, phonenumber :$scope.quote.phonenumber})
        .then(function(response) {
          var response_data = response.data;
          if(response_data.message == 'success'){
            data = response_data.response;
            params = angular.extend(params, {'user_id' :data.id});
            return createQuoteCall(params);
          }
          else {

           var registerInfo = {
               username :$scope.quote.email,
               first_name : $scope.quote.name,
               password :'G3stUs3R',
               user_type : 'guest',
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
                      params = angular.extend(params, {'user_id' : user_data.id});
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
            $scope.error = error;
            $scope.loginError = true;
          });
        }
      },function(error){
          $log.error(error);
        });
  };

  var createVisitor = function (parameter) {
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

  var createQuoteCall = function (parameter) {
          $scope.submitted = true;
      return serviceFactory.createQuote(parameter)
      .then(function(response){
      if ( response.data.message == 'success') {
        var $data = response.data.response;
            return response.data.response;
         } else {
            mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
            $q.reject("Create Quotation Failed");
        }
      },
       function(error){
        $log.error(error);
      });
    };


  $scope.$watchGroup(['labourCharges', 'serviceTax', 'VAT',], function(newValue, oldValue) {
      calculatePrice();

      }, true);

  $scope.$watch('serviceSpecs', function(newValue, oldValue) {
         calculatePrice();
  }, true);


  $scope.$watch('materials', function(newValue, oldValue) {
             calculatePrice();
  }, true);

   var calculatePrice = function() {
    $scope.serviceTax = parseInt($scope.serviceTax);
    $scope.VAT = parseInt($scope.VAT);
    $scope.quote.price = 0 ;
    $scope.totalDiscount = 0;
    $scope.materialCharges = 0;
    $scope.labourCharges =   $scope.labourCharges || 0;
    $scope.serviceTax = $scope.serviceTax || 0;
    $scope.priceWithoutDiscount = 0;
    $scope.VAT = $scope.VAT || 0;
    var price = 0;
    var discount = 0;
    angular.forEach($scope.serviceSpecs, function(serviceInfo, key) {

    if (serviceInfo.option && !isNaN(serviceInfo.option.price )) {
          price = parseInt(serviceInfo.option.price);
          price = parseInt(serviceInfo.option.quantity)*price;
          $scope.priceWithoutDiscount = + $scope.priceWithoutDiscount + price;
        if (!!serviceInfo.option.setDiscount) {

          discount = price * parseInt(serviceInfo.option.discount)/100;
          max_discount = parseInt(serviceInfo.option.max_discount);
          if (discount > max_discount) {
            discount = max_discount;
          }

          $scope.totalDiscount = + $scope.totalDiscount + discount;

          if (price > 0) {
            price = price - discount;
          }
          else {
            price = 0;
          }
        }

    }
    else if(!serviceInfo.attribute && serviceInfo.product && !isNaN(serviceInfo.product.price)) {
          price = parseInt(serviceInfo.product.price);
          price = parseInt(serviceInfo.product.quantity)*price;
          $scope.priceWithoutDiscount = + $scope.priceWithoutDiscount + price;
        if(!!serviceInfo.product.setDiscount) {

          discount = price * parseInt(serviceInfo.product.discount)/100;
          max_discount = parseInt(serviceInfo.product.max_discount);
          if (discount > max_discount) {
            discount = max_discount;
          }
          $scope.totalDiscount = + $scope.totalDiscount + discount;
          if (price > 0) {
            price = price - discount;
          }
          else {
            price = 0;
          }
        }


    } else if (serviceInfo.attribute && !isNaN(serviceInfo.attribute.price))  {
          price = parseInt(serviceInfo.attribute.price);
          price = parseInt(serviceInfo.attribute.quantity)*price;
          $scope.priceWithoutDiscount = + $scope.priceWithoutDiscount + price;
        if (!!serviceInfo.attribute.setDiscount) {
          discount = price * parseInt(serviceInfo.attribute.discount)/100;
          max_discount = parseInt(serviceInfo.attribute.max_discount);
          if (discount > max_discount) {
            discount = max_discount;
          }
          $scope.totalDiscount = + $scope.totalDiscount + discount;
          if (price > 0) {
            price = price - discount;
          }
          else {
            price = 0;
          }
        }

    }

    toadd = isNaN(price) ? 0 : price;

    $scope.quote.price = + $scope.quote.price + toadd;
    if ($scope.quote.price < 0) {
      $scope.quote.price =0;
    }
  });



    // labour charges
    angular.forEach($scope.materials, function(material, key) {
      material.unit_price = isNaN(parseInt(material.unit_price)) ? "" : parseInt(material.unit_price);
      $scope.materials[key].material_total = isNaN(material.unit_price*material.material_quantity) ? 0 : material.unit_price*material.material_quantity;
      $scope.materialCharges = $scope.materialCharges + ($scope.materials[key].material_total);
    });

    $scope.labourCharges = parseInt($scope.labourCharges);
    if ( $scope.labourCharges > 0 ) {
      $scope.quote.price = + $scope.quote.price  + $scope.labourCharges;
    }

    $scope.materialCharges = parseInt($scope.materialCharges);
    if ($scope.materialCharges > 0) {
      $scope.quote.price = + $scope.quote.price  + $scope.materialCharges;
    }

    // serviceTax

    if ($scope.serviceTax > 0) {
      $scope.serviceTaxCalc = ($scope.labourCharges * $scope.serviceTax )/100;
      $scope.quote.price = + $scope.quote.price  + $scope.serviceTaxCalc;
    }
    else {
      $scope.serviceTaxCalc = 0;
    }

    if ($scope.VAT > 0) {
      $scope.VATCalc = (($scope.materialCharges + $scope.labourCharges) * $scope.VAT )/100;
      $scope.quote.price = + $scope.quote.price + $scope.VATCalc;
    } else {
      $scope.VATCalc= 0;
    }

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

 // material

  $scope.addNewMaterial = function() {
    var newItemNo = $scope.materials.length + 1;
    $scope.materials.push({});
  };

  $scope.removeMaterial = function() {
    var lastItem = $scope.materials.length-1;
    $scope.materials.splice(lastItem);
  };

}])
.factory('addQuoteFactory', ['$http','ADD_QUOTE_CONFIG', '$log',  function ($http, ADD_QUOTE_CONFIG,  $log){
  return {
      getServices : function() {
        return $http.get(ADD_QUOTE_CONFIG.getService);
      },
      objUnion : function(array1, array2, matcher) {
         var concated = array1.concat(array2)
         return _.uniq(concated, false, matcher);
      },
      arrayOfObjMerge : function(array1, array2) {
        _.forEach(array1, function(array1obj, key) {
          var $obj2 = {};
            if ( array1[key].attribute && _.find(array2, { 'attribute' : { 'id' :array1[key].attribute.id }})) {
              $findIndex = _.findIndex(array2, { 'attribute' : { 'id' :array1[key].attribute.id }});
              _.assignIn(array2[$findIndex], array1obj);
            }
             else if ( !array1[key].attribute &&  _.find(array2, { 'product' :{ 'id' : array1[key].product.id }})) {
              $findIndex =_.findIndex(array2, { 'product' :{ 'id' : array1[key].product.id }});
              _.assignIn(array2[$findIndex], array1obj);
             }
          });
        return array2;
      },
    };

}])
.constant('ADD_QUOTE_CONFIG', {
   getService: '/api/services',
});
