var app = angular.module('userApp', ['ui.router', 'satellizer','timepickerPop', 'ngMessages','ngAnimate','ui.bootstrap','angularjs-dropdown-multiselect', 'ngFlash', 'updateMeta', 'angulartics','angulartics.google.analytics' ,'angulartics.piwik','permission', 'permission.ui','purplefox.numeric'])
    .config(function($stateProvider, $urlRouterProvider, $authProvider,$provide, $locationProvider, $httpProvider, FlashProvider) {
        /*homepage*/
     //   $urlRouterProvider.when('/', '/home');
        $urlRouterProvider.otherwise('/');
        $urlRouterProvider.deferIntercept();
        $authProvider.loginUrl = '/api/authenticate';
        $authProvider.signupUrl = '/api/register';

        FlashProvider.setTimeout(10000);
/*GA code*/
    run.$inject = ['$rootScope', '$location', '$window'];
     function run($rootScope, $location, $window) {
        // initialise google analytics
        $window.ga('create', 'UA-76409848-1', 'auto');
        // track pageview on state change
        $rootScope.$on('$stateChangeSuccess', function (event) {
            $window.ga('send', 'pageview', $location.path());
            fbq('track', 'Search');
            fbq('track', 'ViewContent');
        });

    }

/*GA code end*/

        $stateProvider
            .state('home', {
                url: '/',
                templateUrl: '/js/tpl/home.html'
            })
            .state('404', {
                url: '/notFound',
                templateUrl: 'error404.html'
            })
            .state('403', {
                url: '/accessDenied',
                templateUrl: 'error403.html'
            })
            .state('quotelist', {
                url: '/config/quotelist',
                templateUrl: '/js/tpl/quote-queue.html',
                controller : 'QuoteController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin', 'CSR']
                 }
                }
            })
            .state('quotedetails', {
                url: '/config/quotedetails/{quoteId:int}',
                templateUrl: '/js/tpl/quote-details.html',
                controller : 'QuoteDetailsController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin', 'CSR']
                 }
                }
            })
            .state('service', {
                url: '/service/:serviceName',
                templateUrl: '/js/tpl/service.html',
                controller : 'ServiceController'
            })
            .state('quoteview', {
                url: '/quoteview/{quoteId:int}',
                templateUrl: '/js/tpl/quote-view.html',
                controller : 'QuoteDetailsController',
            })
            .state('newquote', {
                url: '/config/add/quote',
                templateUrl: '/js/tpl/add-new-quote.html',
                controller : 'AddQuoteController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin', 'CSR']
                 }
                }
            })
            .state('UserProfile', {
                url: '/userprofile',
                templateUrl: '/js/tpl/UserProfile.html',
                controller: 'UserProfileController'
            })
            .state('MyService', {
                url: '/myservices',
                templateUrl: 'js/tpl/myservice.html'
            })
            .state('MyService.quoteview', {
                url: '/quoteview/{quoteId:int}',
                templateUrl: '/js/tpl/quote-view.html',
                controller : 'QuoteDetailsController',
            })
            .state('adminuserpanel',{
                url : '/admin/user',
                templateUrl : '/js/tpl/admin-userpanel.html',
                controller  : 'AdminUserController',
                data: {
                  permissions: {
                    only: ['Master Admin']
                 }
                }
            })
            .state('adminadduser',{
                url : '/admin/adduser',
                templateUrl : '/js/tpl/admin-adduser.html',
                controller : 'AdminUserController',
                data: {
                  permissions: {
                    only: ['Master Admin']
                 }
                }
            })
            .state('adminedituser',{
                url : '/admin/user/edit/:userid',
                templateUrl : '/js/tpl/admin-edituser.html',
                controller : 'AdminUserController',
                data: {
                  permissions: {
                    only: ['Master Admin']
                 }
                }
            })
            .state('addressbook',{
                url : '/addressbook',
                templateUrl : '/js/tpl/addressbook.html',
                controller  : 'AddressBookController',
                access: {
                        isLoggedIn: true
                }
            })
            .state('ForgotPassword',{
                url : '/forgotpassword',
                templateUrl : '/js/tpl/ForgotPassword.html',
                controller  : 'AuthController'
            })
            .state('ResetPassword',{
                url : '/passwordreset/:email/:token',
                templateUrl : '/js/tpl/reset-password.html',
                controller  : 'ForgotPasswordController'
            })
            .state('thank-you',{
                url : '/thankyou',
                templateUrl : '/js/tpl/thankyou.html',
                controller  : 'ThankyouController',
                params: {
                    items: null
                }
            })
            .state('BuyConfirm',{
                url : '/confirmation',
                templateUrl : '/js/tpl/thankyou-confirmation.html',
                controller  : 'ThankyouController',
                params: {
                    items :null
                }
            })
            .state('unsubscribe',{
                url : '/unsubscribe/:email',
                templateUrl : '/js/tpl/unsubscribe.html',
                controller  : 'UnsubscribeController'
            })
            .state('otpverify',{
                url : '/otp-verify',
                templateUrl : '/js/tpl/otp-verify.html',
                controller  : 'OtpVerificationController',
                  params: {
                    items: null
                }
            })
            .state('add-template', {
                url: '/template/add',
                templateUrl: '/js/tpl/template_add.html',
                controller: 'EmailController'
            })
            .state('templates', {
                url: '/template',
                templateUrl: '/js/tpl/template.html',
                controller: 'EmailController'
            })
            .state('edit-template', {
                url: '/template/edit',
                templateUrl: '/js/tpl/template_edit.html',
                controller: 'EmailController'
            })
            .state('notes', {
                url: '/config/notes',
                templateUrl: '/js/tpl/notes/list.html',
                controller: 'NotesController'
            })
            .state('help-center', {
                url: '/help-center/',
                templateUrl: '/js/tpl/static_pages/help-center.html'
            })
            .state('about-us', {
                url: '/about-us',
                templateUrl: '/js/tpl/static_pages/about-us.html'
            })
            .state('privacy-policy', {
                url: '/privacy-policy',
                templateUrl: '/js/tpl/static_pages/privacy-policy.html'
            })
            .state('term-condition', {
                url: '/terms-conditions',
                templateUrl: '/js/tpl/static_pages/term-condition.html'
            })
            .state('partner', {
                url: '/partner-with-us',
                templateUrl: '/js/tpl/static_pages/partner.html',
                controller: 'PartnerController'
            })
            .state('contact-us', {
                url: '/contact-us',
                templateUrl: '/js/tpl/static_pages/contact-us.html',
                controller: 'ContactController'
            })
            .state('sitemap', {
                url: '/sitemap',
                templateUrl: '/js/tpl/sitemap.html',
                controller:'SeodataController'
            })
            .state('permission', {
                url: '/permission',
                params : { message: null, },
                templateUrl: '/js/tpl/permission.html',
                controller: 'PermissionController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('role', {
                url: '/role',
                params : { message: null, },
                templateUrl: '/js/tpl/role.html',
                controller: 'RoleController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('role/add', {
                url: '/role/add',
                templateUrl: '/js/tpl/role_add.html',
                controller: 'RoleController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('/role/edit', {
                url: '/role/:templateID',
                templateUrl: '/js/tpl/role_edit.html',
                controller: 'RoleController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('permission/add', {
                url: '/permission/add',
                templateUrl: '/js/tpl/permission_add.html',
                controller: 'PermissionController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('/permission/edit', {
                url: '/permission/:templateID',
                templateUrl: '/js/tpl/permission_edit.html',
                controller: 'PermissionController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            })
            .state('permissionrole', {
                url: '/permissionrole',
                params : { message: null, },
                templateUrl: '/js/tpl/permissionrole.html',
                controller: 'PermissionRoleController',
                data: {
                  permissions: {
                    only: ['Admin', 'Master Admin']
                 }
                }
            });


        $httpProvider.interceptors.push('spinnerInterceptor');

        // logger
        var IN_DEVELOPMENT = true;
        $provide.decorator('$log', ['$delegate', function ($delegate)  {
        var originals = {};
        var methods = ['info' , 'debug' , 'warn' , 'error'];

        angular.forEach(methods , function(method)
        {
            originals[method] = $delegate[method];
            $delegate[method] = function()
            {
                if (IN_DEVELOPMENT) {
                    var args = [].slice.call(arguments);
                    originals[method].apply(null , args);
                }
            };
       });

       return $delegate;
        }]);
        //$locationProvider.html5Mode(true);


    })
.run(['initializeRolePerms','$rootScope','$state','$filter', function(initializeRolePerms, $rootScope, $state, $filter) {
           $rootScope.$on('$stateChangePermissionDenied', function(event, toState, toParams, options) {
            return $state.go('403');
         });
    initializeRolePerms.init('no-reload');

    /* Date & Time */
    $rootScope.showCommonDate = function(value,format){
        if(value) {
            value = value.replace(/-/g, '/');
        }
        value = new Date(value+' UTC');
        return  $filter('date')(value,format);
    };
}]);
