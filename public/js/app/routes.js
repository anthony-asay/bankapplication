var app =  angular.module('main-App',['ngRoute','angularUtils.directives.dirPagination','ngLocationUpdate']);
app.factory('location', [
    '$location',
    '$route',
    '$rootScope',
    function ($location, $route, $rootScope) {
        $location.skipReload = function () {
            var lastRoute = $route.current;
            var un = $rootScope.$on('$locationChangeSuccess', function () {
                $route.current = lastRoute;
                un();
            });
            return $location;
        };
        return $location;
    }
]);
app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
            when('/', {
                templateUrl: 'templates/home.html',
                controller: 'HomeController'
            }).
            when('/register', {
                templateUrl: 'client/view/register.html',
                controller: 'HomeController'
            }).
            when('/login', {
                templateUrl: 'client/view/login.html',
                controller: 'ClientController'
            }).
            when('/account/', {
                templateUrl: 'client/view/account.html',
                controller: 'ClientController',
                noReload: true
            }).
            when('/profile', {
                templateUrl: 'client/view/profile.html',
                controller: 'ClientController'
            }).
            when('/aboutus', {
                templateUrl: 'templates/aboutus.html',
                controller: 'HomeController'
            }).
             when('/calendar', {
                templateUrl: 'client/view/calendar.html',
                controller: 'CalendarController'
            }).
            when('/logout', {
                templateUrl: 'templates/home.html',
                controller: 'HomeController'
            });
}]);