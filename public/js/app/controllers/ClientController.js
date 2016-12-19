app.controller('ClientController', function(dataFactory,$scope,$http,$location, $window){

  $scope.purchases = [];
  $scope.id_account = 0;
  $scope.account_type = '';
  $scope.accounts = [];
  $scope.days = 30;
  $scope.myVar = true;
  $scope.client_id;
  $scope.contactInfo = [];
  $scope.profileMessage = '';
  $scope.accountTypes = [];


  $scope.trueRefresh = function()
  {
    $http.post('client/refresh', $scope.client_id).then(function (response) 
    {
      localStorage.setItem("client", JSON.stringify(response.data));
      $scope.loadLocal();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data: ' + error.message;
    });
  }
  $scope.addAccount = function()
  {
    var newAccount = {id_client: $scope.client_id, id_type: $scope.form.account_type, balance: $scope.form.balance};

    $http.post('client/addAccount', newAccount).then(function (response)
    {
      $scope.refresh();
      $scope.loadLocal();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
    $(".modal").modal("hide");
  }

  $scope.getAccountTypes = function()
  {
    $http.post('home/getAccountTypes').then(function (response)
    {
      $scope.accountTypes = response.data;
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
  }
  
  $scope.updateInfo = function()
  {
    var realDate = $scope.contactInfo.date_birth.getFullYear()+'-'+($scope.contactInfo.date_birth.getMonth()+1)+'-'+$scope.contactInfo.date_birth.getDate();
    var updateClient = {name_first: $scope.contactInfo.name_first, name_middle: $scope.contactInfo.name_middle,
                    name_last: $scope.contactInfo.name_last, name_user: $scope.contactInfo.name_user,
                    password: $scope.contactInfo.password, date_birth: realDate,
                    phone_number: $scope.contactInfo.phone_number, email: $scope.contactInfo.password,
                    address: $scope.contactInfo.address, city: $scope.contactInfo.city, state: $scope.contactInfo.state,
                    zipcode: $scope.contactInfo.zipcode, id_client: $scope.contactInfo.id_client, id: $scope.contactInfo.id};
    $http.post('client/updateContactInfo', updateClient).then(function (response)
    {
      $window.scrollTo(0, 0);
      $scope.profileMessage = "Your information was updated.";
      document.getElementById("p-alert").className += "alert alert-success";
      $scope.purchases = response.data;
      $scope.trueRefresh();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
  }

  $scope.displayPurchaseDays = function()
  {
    var query = {days: $scope.days, id_account: $scope.id_account};
    $http.post('client/getPurchasesByDate', query).then(function (response)
    {
      $scope.purchases = response.data;
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
  }

  $scope.changeAccount = function(id)
  {
    $scope.days = 30;
    $scope.id_account = id;
    $scope.displayPurchaseDays();
  }

  $scope.addFunds = function()
  {
    var d = new Date();
    var realDate = d.getFullYear() + '-' + (d.getMonth() + 1) + '-' + d.getDate();
    var funds = {date: realDate, id_account: $scope.id_account, source: '-', amount: $scope.form.amount};

    $http.post('client/addPurchase', funds).then(function (response)
    {
      $scope.refresh();
      $scope.loadLocal();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
    $(".modal").modal("hide");
  }

  $scope.loadLocal = function()
  {
    if (JSON.parse(localStorage.getItem("client"))) 
    {
      $window.scrollTo(0, 0);
      var client = JSON.parse(localStorage.getItem('client'));
      var dateArray = client.info.date_birth.split("-");
      var goodDate = new Date();
      goodDate.setFullYear(dateArray[0], dateArray[1] - 1, dateArray[2]);
      $scope.client_id = client.client_id;
      $scope.contactInfo = client.info;
      $scope.contactInfo.conPassword = $scope.contactInfo.password;
      $scope.contactInfo.date_birth = goodDate;
     
      for (i = 0; client.accounts[i]; i++)
      {
        $scope.accounts[i] = client.accounts[i];
        $scope.account_type = client.accounts[i].type;
        $scope.id_account = client.accounts[i].id;
      }
      if(localStorage.getItem("account_id"))
      {
        $scope.id_account = localStorage.getItem("account_id");
      }
      $scope.displayPurchaseDays();
      angular.element('.message').addClass('ng-hide');
      var headerNavs = angular.element('#menu-header').children();
      headerNavs[0].innerHTML = "<a href='#account'>ACCOUNT</a>";
      headerNavs[1].innerHTML = "<a href='#profile'>PROFILE</a>";
      headerNavs[2].innerHTML = "<a href='#logout'>LOG OUT</a>";
      var mainNavs = angular.element("#mainheader").children();
      mainNavs[3].innerHTML = "<a href='#account'>ACCOUNT</a>";
      mainNavs[4].innerHTML = "<a href='#profile'>PROFILE</a>";
      mainNavs[5].innerHTML = "<a href='#logout'>LOG OUT</a>";
      
    } 
  }

  $scope.saveAdd = function()
  {
    var realDate = $scope.form.date.getFullYear()+'-'+($scope.form.date.getMonth()+1)+'-'+$scope.form.date.getDate();
    var purchase = {date: realDate, id_account: $scope.id_account, source: $scope.form.source, amount: (-$scope.form.amount)};
    $http.post('client/addPurchase', purchase).then(function (response) 
    {
      localStorage.setItem("account_id", $scope.id_account);
      $scope.refresh();
      $scope.loadLocal();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data: ' + error.message;
    });
    $(".modal").modal("hide");
      //$(".modal-backdrop.in").addClass("ng-hide");
      
  }
  
  $scope.remove = function(item, index){
    var result = confirm("Are you sure delete this item?");
   	if (result) 
    {
      $http.post('client/deletePurchase', item.id).then(function (response) 
      {
        $scope.refresh();
        $scope.purchases.splice(index,1);
        $scope.loadLocal();
      }, 
      function (error) 
      {
        $scope.status = 'Unable to load customer data: ' + error.message;
      }); 
    }
  }

  $scope.removeAccount = function(item, index){
    var result = confirm("Are you sure delete this item?");
    if (result) 
    {
      $http.post('client/deleteAccount', item.id).then(function (response) 
      {
        var old = angular.element("#account-"+index);
        old.empty();
        $scope.accounts.splice(index,1);
        $scope.refresh();
        $scope.loadLocal();
      }, 
      function (error) 
      {
        $scope.status = 'Unable to load customer data: ' + error.message;
      }); 
    }
  }

  $scope.displayClient = function(data)
  {
    localStorage.setItem("client", JSON.stringify(data));
    $location.path('/account/');
  }

  $scope.refresh = function()
  {
    $http.post('client/refresh', $scope.client_id).then(function (response) 
    {
      $scope.displayClient(response.data);
      $scope.loadLocal();
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data: ' + error.message;
    });
  }

  $scope.authenticate = function(client) {
      if (client)
      {
        $http.post('authenticate', client).then(function (response) {
                if (response.data == 0)
                {
                  $scope.error = "The username or password is incorrect";
                }
                else 
                {
                  $scope.displayClient(response.data);
                }
            }, function (error) {
                $scope.status = 'Unable to load customer data: ' + error.message;
            });
      }
      else
      {
        $scope.error = "You must enter a username and password";
      }
      
    }
  $scope.loadCalendar = function ()
  {
    localStorage.setItem("id_account", $scope.id_account);
    $location.path('/calendar');
  }
  $scope.getAccountTypes();
  $scope.loadLocal();
});