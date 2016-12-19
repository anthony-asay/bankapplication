app.controller('HomeController', function(dataFactory,$scope,$http, $location){
  $scope.register = [];
  $scope.password = [];
  $scope.contactInfo = [];
  $scope.pasMessage = '';
  $scope.passRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
  $scope.accounts = [];

  $scope.getAccountTypes = function()
  {
    $http.post('home/getAccountTypes').then(function (response)
    {
      $scope.accounts = response.data;
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
  }

  $scope.verifyPassword = function()
  {
    if($scope.contactInfo.password != $scope.contactInfo.conPassword)
    {
      $scope.pasMessage = 'The passwords do not match';
    }
    else if(!$scope.register.password.$valid)
    {
      $scope.pasMessage = "Password must be 8 characters long and contain 1 number and 1 uppercase letter";
    }
    else 
    {
      $scope.pasMessage = '';
    }
  }

  $scope.verifyUserName = function()
  {
    var userName = {user_name: $scope.contactInfo.name_user};
    $http.post('client/verifyUserName', userName).then(function (response)
    {
      if(response.data)
      {
        $scope.nameMessage = 'The user name entered is already registered.';
      }
      else
      {
        $scope.nameMessage = '';
      }
    }, 
    function (error) 
    {
      $scope.status = 'Unable to register.';
    });
  }

  $scope.registerInfo = function()
  {
    var realDate = $scope.contactInfo.date_birth.getFullYear()+'-'+($scope.contactInfo.date_birth.getMonth()+1)+'-'+$scope.contactInfo.date_birth.getDate();
    var newClient = {name_first: $scope.contactInfo.name_first, name_middle: $scope.contactInfo.name_middle,
                    name_last: $scope.contactInfo.name_last, name_user: $scope.contactInfo.name_user,
                    password: $scope.contactInfo.password, date_birth: $scope.contactInfo.date_birth,
                    phone_number: $scope.contactInfo.phone_number, email: $scope.contactInfo.email,
                    address: $scope.contactInfo.address, city: $scope.contactInfo.city, state: $scope.contactInfo.state,
                    zipcode: $scope.contactInfo.zipcode, account_type: $scope.contactInfo.account_type};

    $http.post('client/register', newClient).then(function (response)
    {
      localStorage.setItem("client", JSON.stringify(response.data));
      $location.path('/account/');
    }, 
    function (error) 
    {
      $scope.status = 'Unable to register.';
    });
  }

  if (JSON.parse(localStorage.getItem("client"))) 
  {
    angular.element('.message').addClass('ng-show');
    var headerNavs = angular.element('#menu-header').children();
    headerNavs[0].innerHTML = "<a href='#/register'>REGISTER</a>";
    headerNavs[1].innerHTML = "<a href='#/aboutus'>ABOUT US</a>";
    headerNavs[2].innerHTML = "<a href='#/login'>LOG IN</a></div>";
    var mainNavs = angular.element("#mainheader").children();
    mainNavs[3].innerHTML = "<a href='#/register'>REGISTER</a>";
    mainNavs[4].innerHTML = "<a href='#/aboutus'>ABOUT US</a>";
    mainNavs[5].innerHTML = "<a href='#/login'>LOG IN</a></div>";
    localStorage.clear();
  }

  $scope.getAccountTypes();
});
