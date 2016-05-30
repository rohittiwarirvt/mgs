app.controller('QuoteController', function($state, $http, $rootScope, $scope,$auth, $filter, $location, $uibModal) {


 	if($auth.isAuthenticated()){
	    var $user = JSON.parse(localStorage.getItem('user'));
	    $scope.user_id = $user.id;
      $rootScope.user_id = $user.id;
  }
  var searchObject = $location.search();

  //Number of items to be show
  $scope.numitems = {
      10: 10, 20: 20, 30: 30, 40:40, 50:50
  };
  $scope.viewby = 10;
  $scope.quotecsr={};

  $scope.fromdatepicker = {
    opened: false
  };
  $scope.todatepicker = {
    opened: false
  };

   $scope.openFromDate = function() {
    $scope.fromdatepicker.opened = true;
  };

  $scope.openToDate = function() {
    $scope.todatepicker.opened = true;
  };

  $scope.getQuotes = function(value){

    if (angular.isNumber(value)) {
        var value ={ user_id: value }
    }
    if (value && value.search_date) {
        value.fromDate  = (value.fromDate).toString();
        value.toDate  = (value.toDate).toString();
    }
    $http({
            url: '/api/quotes',
            method: "GET",
            params: {filters: value},
            headers: {'Content-Type': 'text/json'}
        }).success(function (data,status) {
          if(data.error_msg) {
              $scope.messages = data.error_msg;
          }
          else {
            angular.forEach(data, function(quotes, key) {
                quotes.user_information = JSON.parse(quotes.user_information);
                quotes.status = JSON.parse(quotes.status);
                quotes.selected = false;
                if(quotes.appointment_date) {
                   quotes.appointment_date = quotes.appointment_date.replace(/-/g, '/');
                }
                quotes.appointment_date = new Date(quotes.appointment_date+' UTC');
                quotes.created_at = new Date(quotes.created_at);
            });
            $scope.quotes = data;


            $scope.totalItems = $scope.quotes.length;
            $scope.currentPage = 1;
            $scope.itemsPerPage = $scope.viewby;
            $scope.maxSize = 5; //Number of pager buttons to show

            $scope.setPage = function (pageNo) {
              $scope.currentPage = pageNo;
            };

            $scope.pageChanged = function() {
            };

            $scope.setItemsPerPage = function(num) {
              $scope.itemsPerPage = num;
              $scope.currentPage = 1; //reset to first page
            }
          }
      });
   	}

    $scope.getStatus = function(){
      $http.get('/api/quote/get_status').success(function(data, status, headers, config) {
          $scope.status = data;
      });
  }


  $scope.save = function(quotecsr, id){
     var options = [];
     $scope.quotes.forEach(function(option) {
      if (option.selected) {
        if (options) {
          options.push(option.id);
        }
      }
    })
    quotecsr.quotes = options;
    if(id && id == $scope.user_id)
        quotecsr.user_id = $scope.user_id;

    if(quotecsr.quotes!='')
    $http.post('/api/quote/assgin_csr', quotecsr).success(function (data) {
        if(data.succ_msg)
        {
           $scope.successmessages = data.succ_msg;
        }
        else
        {
           $scope.errormessages = data.error_msg;
        }
    })
    $state.reload();
  };

  $scope.displaylist = 1;
  var viewId = $location.path().split("/")[2];
  if (viewId) {
   $scope.displaylist = 0;
  }

  $scope.checkflag = function() {
    $scope.displaylist = 0;
  }
  $scope.BackToList = function () {
    $scope.showDiv = 'servicehistory';
    $scope.displaylist = 1;
    $state.go('MyService');
  }

});//end

