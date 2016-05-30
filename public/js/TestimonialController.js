app.controller('TestimonialController', function ($scope,$http) {
  $scope.myInterval = 5000;
  $scope.noWrapSlides = false;
  $scope.active = 0;
  var slides = $scope.slides = [];
  var currIndex = 0;

/* Add Testimonial  */
 $scope.gettestimonial = function(){
      $http.get('/api/gettestimonials').success(function(data) {
          $scope.testimonialdata = data.testimonialdata;
          $scope.addSlide = function(i) {
              slides.push({
                description: $scope.testimonialdata[i].description,
                authorname: $scope.testimonialdata[i].author_name,
                description1: $scope.testimonialdata[i+1].description,
                authorname1: $scope.testimonialdata[i+1].author_name,
                id: currIndex++
              });
        };
        $scope.randomize = function() {
          var indexes = generateIndexesArray();
          assignNewIndexesToSlides(indexes);
        };
        for (var i = 0; i < $scope.testimonialdata.length; i=i+2) {
          $scope.addSlide(i);
        }
          // Randomize logic below
        function assignNewIndexesToSlides(indexes) {
          for (var i = 0, l = slides.length; i < l; i++) {
            slides[i].id = indexes.pop();
          }
        }

        function generateIndexesArray() {
          var indexes = [];
          for (var i = 0; i < currIndex; ++i) {
            indexes[i] = i;
          }
          return shuffle(indexes);
        }

        function shuffle(array) {
          var tmp, current, top = array.length;
          if (top) {
            while (--top) {
              current = Math.floor(Math.random() * (top + 1));
              tmp = array[current];
              array[current] = array[top];
              array[top] = tmp;
            }
          }
          return array;
        }

  });
  }

});
