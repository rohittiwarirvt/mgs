app.controller('NotesController', function($state, $http, $rootScope, $scope,$auth, $filter, $location, $window, $uibModal) {

  $scope.noteAdd= $scope.reply = {};
	$scope.messages={};
  $scope.utcTime = ' UTC';
	$scope.submitted = false;
	var url = $location.path().split('/');
	$scope.quote_id = url[url.length - 1];

   	if(url.indexOf('quoteview') > -1){
       $scope.noteAdd.note_type = $scope.reply.note_type = 'External';
       $scope.noteAdd.assign_to = '1';
   	}

 	if($auth.isAuthenticated()){
	    var $user = JSON.parse(localStorage.getItem('user'));
	    $scope.user_id = $user.id;
  	}

 	$scope.preval='Internal';
    $scope.ShowConfirm = function () {
        if (confirm("Please confirm to show this message to customer?")) {
            $scope.preval = $scope.noteAdd.note_type;
        } else {
            $scope.noteAdd.note_type = $scope.preval;
        }
    }

   	$scope.getDepartments = function(){
		$http.get('/api/notes/get_dept_list').
		success(function(data, status, headers, config) {
			$scope.dept = data;
		});
   	}

   	$scope.getSubjects = function(){
		$http.get('/api/notes/get_subject_list').
		success(function(data, status, headers, config) {
			$scope.subject = data;
		});
   	}

 	$scope.getNotes = function(value){
		$http({
		    url: '/api/notes',
		    method: "GET",
		    params: { quote_id: value},
		    headers: {'Content-Type': 'text/json'}
		}).success(function (data,status) {
			if(data.error_msg) {
				$scope.messages = data.error_msg;
			}
			else {
				$scope.notes = data;
				$scope.notes.lastNote = $scope.notes[$scope.notes.length - 1];
			}
		});
   	}

    $scope.save = function(value){
    	$scope.submitted = true;
    	if ($scope.notesForm.$valid && angular.isNumber(+$scope.quote_id)) {

            if(value=='reject'){
				   $scope.noteAdd.subject =10;
				   $scope.noteAdd = angular.extend($scope.noteAdd, {
		    			'quote_status' :5
    	    		});
            }

    		$scope.noteAdd = angular.extend($scope.noteAdd, {
    			'quote_id' :$scope.quote_id,
    			'subject_id':$scope.noteAdd.subject,
                'department_id':$scope.noteAdd.assign_to,
                'user_id':$scope.user_id,
                'created_by':$scope.user_id,
                'service_type':$scope.selectedService.service_name
    	    });
    		$http.post('/api/notes',$scope.noteAdd).success(function (data) {
				$('#NotesFormWrapper').modal('hide');

				$scope.disabled= false;
				$scope.messages = data.succ_msg;
				$window.location.reload();
	    	})
	    	.error(function (data, status){
	                console.log("Error status : " + status);
	        }),
	        function(error){
	          $scope.error = error;
	        };
    	}
	};

	$scope.replyNotes = function(parentNote) {
        if ($scope.NotesReplyForm.$valid) {
	        $scope.reply = angular.extend($scope.reply, {
				'department_id':parentNote.department_id,
				'subject_id':parentNote.subject_id,
	            'quote_id':parentNote.quote_id,
	            'parent_id':parentNote.id,
	            'user_id':parentNote.user_id,
	            'created_by':$scope.user_id,
	            'service_type':$scope.selectedService.service_name
		    });
	     	$http.post('/api/notes',$scope.reply).success(function (data) {
				$('#NotesReply').modal('hide');
				$scope.messages = data.succ_msg;
				$window.location.reload();
	    	})
	    	.error(function (data, status){
                console.log("Error status : " + status);
        	});
     	}
	};

    $scope.sendReply = function(parentNote){
   		$scope.replyNote	= parentNote;
	};

	$scope.setQuoteStatus = function(quoteStatus){
		$rootScope.quoteReject = '';
		if(quoteStatus!='') {
   		 	$rootScope.quoteReject	= quoteStatus;
   		}
	};

	$scope.changeStatus = function(noteDetail){
   		$scope.statusNote = noteDetail;
	};

    $scope.update = function(noteDetail){
    	if ($scope.noteStatusForm.$valid){
    		$scope.statusNote = angular.extend($scope.statusNote, {
	            'quote_id':noteDetail.quote_id,
	            'user_id':noteDetail.user_id,
	            'created_by':noteDetail.user_id,
                'status': $scope.statusNote.statusvalue
		    });
	        $http.put('/api/notes/'+ noteDetail.id, $scope.statusNote).success(function(data){
	        if(data.error_msg)
  	               $scope.messages = data.error_msg;
	            else
	            {
	               $scope.messages = data.succ_msg;
	              $('#NotesStatusWrapper').modal('hide');
	            }
	        })
	        .error(function (data, status){
                console.log("Error status : " + status);
       		});
		}
    };

});//end

