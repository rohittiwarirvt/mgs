app.controller('ImageSlideController', function ($scope) {
  $scope.myInterval = 5000;
  $scope.noWrapSlides = false;
  $scope.active = 0;
  var slides = $scope.slides = [];
  var currIndex = 0;

/* Add Slides For Desktop UI */
  $scope.addSlide = function() {
    slides.push({
      image: [
        "/themes/mgs/images/slides/mgs-hp-beauty.jpg",
        "/themes/mgs/images/slides/mgs-hp-painting.jpg",
        "/themes/mgs/images/slides/mgs-hp-pest-control.jpg"
        ][slides.length % 3],
      text: ['Say GOOD-BYE to Salon, <br/> Call Beautician at doorstep',
            'Let our Painters <br/> add colors to your Home',
            'Get Rid of Unwanted Guest <br/> from your Place'][slides.length % 3],
      
      id: currIndex++
    });
  };
/* Add Slides For Mobile, Tablet etc devices */
  $scope.addSlideM = function() {
    slides.push({
      image: [
        "/themes/mgs/images/slides/mgs-m-hp-beauty.jpg",
        "/themes/mgs/images/slides/mgs-m-hp-painting.jpg",
        "/themes/mgs/images/slides/mgs-m-hp-pest-control.jpg"
        ][slides.length % 3],
      text: ['Say GOOD-BYE to Salon, <br/> Call Beautician at doorstep',
            'Let our Painters <br/> add colors to your Home',
            'Get Rid of Unwanted Guest <br/> from your Place'][slides.length % 3],

      id: currIndex++
    });
  };

  $scope.randomize = function() {
    var indexes = generateIndexesArray();
    assignNewIndexesToSlides(indexes);
  };

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

  // http://stackoverflow.com/questions/962802#962890
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
  /* For Mobile Image Slider */
    $scope.getWindowDimensions = function() {
        return {
            'h': $(window).height(),
            'w': $(window).width()
        };
    };
    $scope.$watch($scope.getWindowDimensions, function(newValue, oldValue) {
        $scope.windowHeight = newValue.h;
        $scope.windowWidth = newValue.w;
        $scope.small = false;
        if ($scope.windowWidth < 768) //for Mobile & smaller devices
        {
            for (var i = 0; i < 3; i++) {
              $scope.addSlideM();
            }
        }
        else {
            for (var i = 0; i < 3; i++) {
              $scope.addSlide();
            }
        }
    }, true);
});
