app.controller('QuoteDetailsController',[ '$rootScope', '$scope', '$state',  '$auth', '$log', 'quoteFactory',  '$stateParams','$location', 'serviceFactory', 'addQuoteFactory', '$filter', 'Flash', 'mgsCommonFactory', '$document', function($rootScope, $scope, $state,  $auth , $log, quoteFactory, $stateParams, $location, serviceFactory,  addQuoteFactory, $filter, Flash, mgsCommonFactory, $document) {
  $scope.optionsNotPresent = true;
  $scope.customer = $scope.customer || {};
  $scope.service = $scope.service || {};
  $scope.serviceObj = $scope.serviceObj || {};
  $scope.quote = $scope.quote || {};
  $scope.prodAttributes = $scope.prodAttributes || [];
  $scope.attributeOptions = $scope.attributeOptions || [];
  $rootScope.initialized = false;
  $scope.ismeridian = true;
  $scope.materials = $scope.materials || [];
  $scope.submitted = false;
  $scope.disabledContact = true;
  $scope.disabledService= true;
  $scope.firstInit = false;

  if ($auth.isAuthenticated()) {
      $userData = localStorage.getItem('user');
      $scope.loggedInUser = JSON.parse($userData);
  }

  function disabled(data) {
    var date = data.date,
      mode = data.mode;
    return mode === 'day' && (date.getDay() === 0 || date.getDay() === 6);
  }
  $scope.getAttributeLabel = function(id) {
    id = parseInt(id);
    $scope.optionAttributeLabel = $scope.optionAttributeLabel || [];
    $scope.optionAttributeLabel[id] = _.find($scope.prodAttributes, { 'id': id});
  }

  $scope.dateOptions = {
    formatYear: 'yy',
    maxDate: new Date(2020, 5, 22),
    minDate: new Date(),
    startingDay: 1
  };

  $scope.units = ['no', 'feet', 'km', 'm', 'kg', 'pt', 'l', 'unit'   ];

  $scope.datepicker = {
    opened: false
  };

  $scope.openDate = function() {
    $scope.datepicker.opened = true;
  };

    // material info
  var getMaterialInfoInit = function() {
      quoteFactory.getMaterialInfo({'id' : $stateParams.quoteId})
        .then(function(response) {
          if(response.data.message == 'success'){
              $scope.materials = response.data.response;

          } else {
            mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
            $q.reject("Registration Failed");
          }

      },
      function(error){
       $log.error(error);
      });
  };

  getMaterialInfoInit();

  $scope.quotedetails = function () {
    $scope.quote_id = $stateParams.quoteId;
    quoteFactory.getQuote({'id' : $scope.quote_id})
    .then(function(response) {
      var data = response.data.response;
      populateContactDetails(data);
        $scope.quote.price = data.quote_price;
        $scope.serviceTax = data.service_tax;
        $scope.VAT = data.vat;
        $scope.labourCharges = data.labour_charges;
        $scope.quote_user= data.user;
    },
    function(error){
      $log.error(error);
    });



    quoteFactory.getServiceInfo({'id' : $scope.quote_id})
    .then(function(response) {
        var data_service = response.data;
        var  $serviceJsonget = JSON.stringify(data_service);
         quoteFactory.getServiceDetails({'service_info' : $serviceJsonget})
          .then(function(response){
            var data_service_details = response.data;
            $scope.serviceObj = data_service_details[0].service;
            $scope.service.id = data_service_details[0].service.id;
            $scope.selectedService = data_service_details[0].service[0];
            $scope.serviceSpecs = data_service_details;
            $scope.products();


          }, function(error){
            $log.info(response);
          });
      }, function(error) {
      $log.error(error);
    });
  };

  //  calculator
  $scope.products = function(){
    $friendlyUrl = $scope.serviceObj[0].url;
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
        $scope.prodCounter = 0;
      serviceFactory.getAttributes(product)
        .then(function(response){
          $log.info(response.data);
          $log.info(product);
          $scope.prodCounter = + $scope.prodCounter + 1;
          $scope.prodAttributes = addQuoteFactory.objUnion($scope.prodAttributes,response.data);
          if(response.data.length){
            $scope.multipleAttributes[product] = true;
            $scope.singleAttribute[product] = false;
            angular.forEach(response.data, createAtrributesArray);

          } else {
            $scope.singleAttribute[product] = true;
            $scope.multipleAttributes[product] = false;

          }

          if ($scope.$products.length == $scope.prodCounter) {
            populateServiceData();
          }

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
    $scope.attributeCounter = 0;
    $scope.attributesModel[value.pivot.product_id].push({id:value.id, label: value.attribute_name, 'description' : value.attribute_description, 'price' : value.price });
    serviceFactory.getOptions(value).then(function(response){
      $log.info('options');
      $log.info(response.data);
      $scope.attributeCounter = + $scope.attributeCounter + 1;
      $scope.attributeOptions = addQuoteFactory.objUnion($scope.attributeOptions,response.data);
      var option = response.data;
      if(option) {
        angular.forEach(response.data, createOptionsArray);
      }
      if ($scope.prodAttributes.length == $scope.attributeCounter) {
            populateCalculatorData();
           showOptions();

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
    if ($rootScope.initialized) {
          populateServices();
    }
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
    $log.info($scope.attributesPackageModel);
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

  $scope.optionsExtraSettings = { showCheckAll:false, showUncheckAll:false,  scrollable : true, scrollableHeight : '250px', smartButtonMaxItems : 3, selectionLimit: 1};
  $scope.optionsTranslationTexts = $scope.optionsTranslationTexts || [];
  $scope.optionLabels = $scope.optionLabels  || {};
  $scope.optionsTranslationTexts[$scope.optionLabels.id] = { buttonDefaultText:$scope.optionLabels.label};

  var populateContactDetails = function($data){
      $log.info($data);
      $scope.quote.price =  $data.quote_price ? $data.quote_price : '0';
      $scope.quote.date  = $data.created_at;
      $scope.utcTime = ' UTC';
      $scope.quote.id = $data.id;
      $scope.quote.status_id = $data.status_id;
      $scope.quote.status = JSON.parse($data.status);
      $user_info = JSON.parse($data.user_information);
      $log.info($user_info);
      $scope.customer.name = $user_info.first_name;
      $scope.customer.phonenumber = $user_info.phonenumber;
      $scope.customer.pincode = $user_info.pincode;
      $scope.customer.email = $user_info.email;
      $scope.customer.address1 = $user_info.address1;
      $scope.utcTime = ' UTC';
      $scope.service.appointmentviewDate = $data.appointment_date;
      $scope.service.appointmentviewTime = $data.appointment_time;

      if($data.appointment_date && $data.appointment_time) {
          $data.appointment_date = $data.appointment_date.replace(/-/g, '/');
          $data.appointment_time = $data.appointment_time.replace(/-/g, '/');
      }
      $scope.service.appointmentDate = new Date($data.appointment_date+$scope.utcTime);
      $scope.service.appointmentTime = new Date($data.appointment_time+$scope.utcTime);
  };
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
          $index = _.findIndex(serviceInfo, {'attribute':{'id' : attributeId}});

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
      var serviceInfoObj = {
        'product' : _.find($scope.$products, { 'id': serviceInfo.product_id }),
        'attribute' : _.find($scope.prodAttributes, { 'id': serviceInfo.attribute_id}),
        'option' : _.find($scope.attributeOptions, { 'id': serviceInfo.option_id}),
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

      if (data){
         $scope.serviceSpecs =  addQuoteFactory.arrayOfObjMerge($scope.serviceSpecs, data);
      }

      if (!$rootScope.initialized){
        populateCalculatorData();
      }

    serviceSpecsInitializer();

  };

  var serviceSpecsInitializer = function() {
    angular.forEach($scope.serviceSpecs, function(service, key) {
    $log.info(service);

    if (service.option ) {
      var $contextObj = $scope.serviceSpecs[key].option;

      $contextObj.price = parseInt(service.option_value)  && !$scope.firstInit ? parseInt(service.option_value) : $contextObj.price;
      $contextObj.quantity = parseInt(service.quantity) && !$scope.firstInit  ? parseInt(service.quantity): $contextObj.quantity ;
      $contextObj.quantity = angular.isUndefined($contextObj.quantity) || parseInt($contextObj.quantity) == 0 ? 1 : $contextObj.quantity;
      $contextObj.unit =  !service.unit  ? $contextObj.unit : service.unit ;
      $contextObj.setDiscount = angular.isUndefined($contextObj.setDiscount) ? !!(parseInt(service.discount)) : $contextObj.setDiscount ;
      if ($contextObj.setDiscount) {
        $contextObj.discount = isNaN(parseInt(service.discount)) || parseInt(service.discount) == 0 ? $contextObj.discount : parseInt(service.discount);
      }

    }
    else if(!service.attribute && service.product) {
      var $contextObj = $scope.serviceSpecs[key].product;
      $contextObj.price = parseInt(service.product_value)  && !$scope.firstInit ? parseInt(service.product_value) : $contextObj.price;
        $contextObj.quantity = parseInt(service.quantity) && !$scope.firstInit  ? parseInt(service.quantity): $contextObj.quantity ;
      $contextObj.quantity = angular.isUndefined($contextObj.quantity) || parseInt($contextObj.quantity) == 0 ? 1 : $contextObj.quantity;
      $contextObj.unit =  !service.unit  ? $contextObj.unit : service.unit ;
      $contextObj.setDiscount = angular.isUndefined($contextObj.setDiscount) ? !!(parseInt(service.discount)) : $contextObj.setDiscount ;
      if ($contextObj.setDiscount) {
        $contextObj.discount = isNaN(parseInt(service.discount)) || parseInt(service.discount) == 0 ? $contextObj.discount : parseInt(service.discount);
      }
    } else if(service.attribute) {
      var $contextObj = $scope.serviceSpecs[key].attribute;
      $contextObj.price = parseInt(service.attribute_value)  && !$scope.firstInit ? parseInt(service.attribute_value) : $contextObj.price;
      $contextObj.quantity = parseInt(service.quantity) && !$scope.firstInit  ? parseInt(service.quantity): $contextObj.quantity ;
      $contextObj.quantity = angular.isUndefined($contextObj.quantity) || parseInt($contextObj.quantity) == 0 ? 1 : $contextObj.quantity;
      $contextObj.unit =  !service.unit ? $contextObj.unit : service.unit ;
      $contextObj.setDiscount = angular.isUndefined($contextObj.setDiscount) ? !!(parseInt(service.discount)) : $contextObj.setDiscount ;
      if ($contextObj.setDiscount) {
        $contextObj.discount = isNaN(parseInt(service.discount)) || parseInt(service.discount) == 0 ? $contextObj.discount : parseInt(service.discount);
      }
    }

    if(service.hasOwnProperty('option') && service.option) {
      $scope.optionsNotPresent = false;
    }

    //discount



      });
          $scope.firstInit  = true;
    if ($scope.serviceSpecs.length) {
      if ($scope.serviceSpecs[0].hasOwnProperty('option') && $scope.serviceSpecs[0].option) {
        $scope.serviceGroupBySpecs = _.groupBy( $scope.serviceSpecs, "attribute.attribute_name");
      } else {
        $scope.serviceGroupBySpecs = _.groupBy( $scope.serviceSpecs, "product.product_name");
      }
    } else {
      $scope.serviceGroupBySpecs = {};
    }

  }


 $scope.updateQuote = function(status) {
  $scope.submitted = true;
  if ($scope.customereDetail.$valid && $scope.ServiceDetail.$valid) {
    var userparams = {
            phonenumber : $scope.customer.phonenumber,
            email : $scope.customer.email,
            first_name : $scope.customer.name,
            address1 : $scope.customer.address1,
            pincode :  $scope.customer.pincode,
      };
    $scope.disabled = true;
    $scope.status_id = null;
    if (angular.isNumber(+status)) {
      $scope.status_id = status;
    }
    var params =  {
        user_information :JSON.stringify(userparams),
        appointment_date : $scope.service.appointmentDate,
        appointment_time : $scope.service.appointmentTime,
        status_id : $scope.status_id ,
        id : $scope.quote_id,
        quote_material_info : JSON.stringify($scope.materials)
    };


    calculatePrice();

    $log.info($scope.quote.price);
    $scope.loggedInUser
     params = angular.extend(params, {  service_info : JSON.stringify($scope.serviceSpecs),
                                        quote_price : $scope.quote.price,
                                        vat : $scope.VAT,
                                        service_tax : $scope.serviceTax,
                                        labour_charges :$scope.labourCharges
                                     });
     if ( $auth.isAuthenticated() ) {
      var updated_by = { updated_by : $scope.loggedInUser.id};
      angular.extend(params, updated_by);
     }
     else {
      var updated_by = { updated_by : $scope.quote_user.id};
      angular.extend(params, updated_by);
     }
     $log.info(params);


    quoteFactory.putQuote(params)
      .then(function(response) {
        $log.info(response);
        getMaterialInfoInit();
        if(response.data.message == 'success'){
          var message = "Saved Quotation Successfully";
          if ($scope.status_id == 11){
            openBuyConfirmation(response);
            var prodIDs = ''; var prodNames = '';
            angular.forEach($scope.$products, function(product, key) {
                prodIDs += product.id + ",";
                prodNames += product.product_name + ",";
            });
             _paq.push(['trackEvent', 'Quote', 'Button Click', 'Buy', $scope.quote_id]);
            ga('send', 'event', { eventCategory: 'Buy Button', eventAction: 'Button Click', eventLabel: $scope.serviceObj[0].service_name });
            ga('require', 'ecommerce');
            //add transaction data to the shopping cart using the ecommerce:addTransaction command:
            ga('ecommerce:addTransaction', {
              'id': $scope.quote_id,                        // Transaction ID. Required.
              'revenue': $scope.quote.price,                 // Grand Total.
              'tax': $scope.serviceTax                      // Tax.
            });

            //to add items to the shopping cart, you use the ecommerce:addItem command:
            ga('ecommerce:addItem', {
              'id': $scope.quote_id,                          // Transaction ID. Required.
              'name': prodNames,                  // Product name. Required.
              'sku': prodIDs,                               // SKU/code.
              'category': $scope.serviceObj[0].service_name,  // Category or variation.
              'price': $scope.quote.price,                    // Unit price.
              'quantity': '1'                                 // Quantity.
            });

            ga('ecommerce:send');
          }
          if ($scope.status_id == 5){
             _paq.push(['trackEvent', 'Quote', 'Button Click', 'Reject', $scope.quote_id]);
              ga('send', 'event', { eventCategory: 'Reject Button', eventAction: 'Button Click', eventLabel: $scope.serviceObj[0].service_name });
          }
          Flash.create('success', message);

        } else {
              mgsCommonFactory.loopErrorMessage(response.data.response.errorMessage);
              $q.reject("Registration Failed");
        }

      },
      function(error){
        $log.error(error);
      });
  }
  $state.reload();
  };

  var  populateCalculatorData = function(){
    $rootScope.initialized = true;
    angular.forEach($scope.serviceSpecs, function(specs, key) {
       angular.forEach($scope.attributesPackageModel, function(attributes, productId) {
         if (specs.product.id === productId && specs.attribute) {
           if (!$scope.isExist($scope.attributesPackageModel[productId], {'id' : specs.attribute.id})){
             $scope.attributesPackageModel[productId].push({'id' : specs.attribute.id, 'attribute_name': specs.attribute.attribute_name});
            }
         }
          else if(specs.product.id === productId && !specs.attribute) {
           if (!$scope.isExist($scope.attributesPackageModel[productId], { 'id' : productId})){
                $scope.attributesPackageModel[productId].push({'id' : productId});
            }

          }
       });



       angular.forEach($scope.optionsPackageModel, function(option, attributeId) {
          if (specs.option){
            if (specs.attribute.id == attributeId) {
              $scope.optionsNotPresent = false;
              $scope.optionsPackageModel[attributeId].push({'id' : specs.option.id});
            }
          }

       });

    });
    // if ($scope.serviceSpecs.length) {

    // }
    $log.info($scope.serviceSpecs);
    $log.info($scope.attributesPackageModel);

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

  var openBuyConfirmation = function(parameters) {
    var $items = {
            username: $scope.quote_user.username,
            servicename : $scope.serviceObj[0].service_name ,
            usertype : $scope.quote_user.usertype,
            user_id: parameters.user_id,

          };
    $state.go('BuyConfirm', {items:$items});
  };
}])
.factory('quoteFactory', ['$http','QUOTE_CONFIG', '$log',  function($http, QUOTE_CONFIG,  $log){
  return {
      getQuote : function(param) {
        return $http.get(QUOTE_CONFIG.resourceQuote + '/' + param.id);
      },
      getServiceInfo: function(param) {
        return $http.get(QUOTE_CONFIG.getServiceAPI, {
              params : param,
        });
      },
      getServiceDetails: function(params) {
        return $http.get(QUOTE_CONFIG.getServiceDetailAPI, {
          'params' : params
        });
      },
      putQuote : function(params) {
        return $http.put(QUOTE_CONFIG.resourceQuote + '/' + params.id, params );
      },
      getMaterialInfo: function(param) {
        return $http.get(QUOTE_CONFIG.getMaterialDetailAPI, {
              params : param,
        });
      },
  };
}])
.constant('QUOTE_CONFIG', {
   resourceQuote: '/api/quotes',
   getServiceAPI : '/api/get-quote-service-info',
   getServiceDetailAPI : '/api/get-quote-shoppingcart-info',
   getMaterialDetailAPI : '/api/get-quote-material-info'
});
