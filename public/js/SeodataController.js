/**
* Name: MGSCommonController.js
* A common JS controller file for Global JS functions.
*
**/

app.controller('SeodataController',  function($state, $http, $rootScope, $scope,$auth, $uibModal, $location, $window, $location) {
    $rootScope.$on('$stateChangeSuccess', function (event) {
    $http.get('/api/seodata', {
            params:  {friendlyurl: $location.path()}
        })
        .then(function(response) {
            $seodata = response.data;
            $scope.title = $seodata.title;
            $scope.meta_description = $seodata.meta_description;
            $scope.meta_keyword = $seodata.meta_keyword;

            $scope.schema_name = $seodata.schema_name;
            $scope.schema_description = $seodata.schema_description;
            $scope.schema_image = $seodata.schema_image;
            $scope.opengraph_type = $seodata.opengraph_type;

            $scope.opengraph_type = $seodata.opengraph_type;
            $scope.opengraph_title = $seodata.opengraph_title;
            $scope.opengraph_description = $seodata.opengraph_description;
            $scope.opengraph_url = $seodata.opengraph_url;
            $scope.opengraph_image = $seodata.opengraph_image;

            $scope.twitter_card = $seodata.twitter_card;
            $scope.twitter_title = $seodata.twitter_title;
            $scope.twitter_description = $seodata.twitter_description;
            $scope.twitter_url = $seodata.twitter_url;
            $scope.twitter_image = $seodata.twitter_image;
/* facebook code*/
        (function() {
          var _fbq = window._fbq || (window._fbq = []);
          _fbq = window._fbq || [];
          _fbq.push(['track', '6048339321602', {'value':'0.00','currency':'INR'}]);
          if (!_fbq.loaded) {
            var fbds = document.createElement('script');
            fbds.async = true;
            fbds.src = 'https://connect.facebook.net/en_US/fbds.js';
            var s = document.getElementsByTagName('script')[0];
            s.parentNode.insertBefore(fbds, s);
            _fbq.loaded = true;
          }
        })();

/*piwik*/
            var webpage_id = $seodata.webpage_id;
            var _paq = _paq || [];
            _paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
            _paq.push(["setCookieDomain", "*.www.mygharseva.com"]);
            _paq.push(["setDomains", ["*.www.mygharseva.com"]]);
            _paq.push(['setCustomVariable','1','WebPageId', webpage_id,'page']);
            _paq.push(['trackPageView']);
            _paq.push(['enableLinkTracking']);
            (function() {
                var u="//leads.prohaktiv.com/";
                _paq.push(['setTrackerUrl', u+'piwik.php']);
                _paq.push(['setSiteId', 5]);
                var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
                g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
            })();

/*google code */
              _gaq = window._gaq || [];
              _gaq.push(['_trackPageview', $location.path()]);
              _gaq.push(["_setCustomVar", 1, "Member", "yes", 1]);
//              _gaq.push(["_setCustomVar", 2, "RegisteredFor", Math.max(months,12)+" months", 2]);
              _gaq.push(["_setCustomVar", 3, "Topic", "JavaScript", 3]);

        // Request completed successfully
        }, function(x) {
        // Request error
    });
   });
});

