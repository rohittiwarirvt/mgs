'use strict';

var directiveModule = angular.module('mgs-dropdown-multiselect', []);

directiveModule.directive('mgsDropdownMultiselect', ['$filter', '$document', '$compile', '$parse',
    function ($filter, $document, $compile, $parse) {
      return {
        restrict: 'AE',
        scope: {
                selectedModel: '=',
                options: '=',
                extraSettings: '=',
                events: '=',
                searchFilter: '=?',
                translationTexts: '=',
                groupBy: '@'
            },
        templateUrl: function(element, attrs) {
          return attrs.templateUrl || '/js/tpl/mgs-dropdown-multiselect.html';
        },
        controller: 'MgsDropdownMultiselectController',
        controllerAs: 'mgs-drop-multi',
        link : 'mgsDropMultiLink'
      };
    }]);

directiveModule.controller('MgsDropdownMultiselectController', ['$scope', '$element', '$attrs',  function($scope,  $element, $attrs ){
  $scope.checkboxes = attrs.checkboxes ? true : false;

}]);

var mgsDropMultiLink = function ( $scope, $element, $attrs ) {
  var $dropdownTrigger = $element.children()[0];
  $scope.settings = {
    dynamicTitle: true,
    scrollable: false,
    scrollableHeight: '300px',
    displayProp: 'label',
    idProp: 'id',
    selectionLimit: 0,
    buttonClasses: 'btn btn-default',
  };

  $scope.texts = {
    selectionCount: 'checked',
    dynamicButtonTextSuffix: 'checked'
  };

  $scope.isChecked = function (id) {
      if ($scope.singleSelection) {
         return $scope.selectedModel !== null && angular.isDefined($scope.selectedModel[$scope.settings.idProp]) && $scope.selectedModel[$scope.settings.idProp] === getFindObj(id)[$scope.settings.idProp];
      }
      return _.findIndex($scope.selectedModel, getFindObj(id)) !== -1;
    };

  $scope.setSelectedItem = function (id, dontRemove) {

    var findObj = getFindObj(id);
    var finalObj = null;
    finalObj = findObj;


   if ($scope.singleSelection) {
      clearObject($scope.selectedModel);
      angular.extend($scope.selectedModel, finalObj);
      }

      dontRemove = dontRemove || false;
      var exists = _.findIndex($scope.selectedModel, findObj) !== -1;

      if (!dontRemove && exists) {
           $scope.selectedModel.splice(_.findIndex($scope.selectedModel, findObj), 1);
       } else if (!exists && ($scope.settings.selectionLimit === 0 || $scope.selectedModel.length < $scope.settings.selectionLimit)) {
         $scope.selectedModel.push(finalObj);
       }
    };

  $scope.getPropertyForObject = function (object, property) {
      console.log(())
      if (angular.isDefined(object) && object.hasOwnProperty(property)) {
          return object[property];
      }
      return '';
  };

  $scope.getButtonText = function () {
    if ($scope.settings.dynamicTitle && ($scope.selectedModel.length > 0 ||
       (angular.isObject($scope.selectedModel) && _.keys($scope.selectedModel).length > 0))) {
       if ($scope.settings.smartButtonMaxItems > 0) {
          var itemsText = [];

          angular.forEach($scope.options, function (optionItem) {
            if ($scope.isChecked($scope.getPropertyForObject(optionItem, $scope.settings.idProp))) {
              var displayText = $scope.getPropertyForObject(optionItem, $scope.settings.displayProp);
              var converterResponse = $scope.settings.smartButtonTextConverter(displayText, optionItem);
              itemsText.push(converterResponse ? converterResponse : displayText);
            }
           });

         if ($scope.selectedModel.length > $scope.settings.smartButtonMaxItems) {
           itemsText = itemsText.slice(0, $scope.settings.smartButtonMaxItems);
           itemsText.push('...');
         }
           return itemsText.join(', ');
         } else {
           var totalSelected;
           if ($scope.singleSelection) {
             totalSelected = ($scope.selectedModel !== null && angular.isDefined($scope.selectedModel[$scope.settings.idProp])) ? 1 : 0;
           } else {
              totalSelected = angular.isDefined($scope.selectedModel) ? $scope.selectedModel.length : 0;
           }

          if (totalSelected === 0) {
            return $scope.texts.buttonDefaultText;
          } else {
           return totalSelected + ' ' + $scope.texts.dynamicButtonTextSuffix;
          }
        }
    } else {
     return $scope.texts.buttonDefaultText;
    }
  };


  function getFindObj(id) {
     var findObj = {};
     if ($scope.settings.externalIdProp === '') {
       findObj[$scope.settings.idProp] = id;
     } else {
       findObj[$scope.settings.externalIdProp] = id;
     }
     return findObj;
   }

  function clearObject(object) {
    for (var prop in object) {
       delete object[prop];
    }
  }



  $scope.toggleDropdown = function () {
     $scope.open = !$scope.open;
  };

  $scope.checkboxClick = function ($event, id) {
    $scope.setSelectedItem(id);
    $event.stopImmediatePropagation();
  };


};

