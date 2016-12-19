app.controller('CalendarController', function(dataFactory,$scope,$http){
  var totalPurchases = new Array();
  $scope.curDate = new Date();
  $scope.days = new Array(7);
  $scope.months = new Array('January','February','March','April','May','June','July','August','September','October','November','December');
  $scope.month = $scope.curDate.getMonth();
  $scope.year = $scope.curDate.getFullYear();
  $scope.day = $scope.curDate.getDate();
  $scope.id_account = 0;
  $scope.totalAmount = 0;
  
 

  
  $scope.getDays = function()
  {
    $scope.totalAmount = 0;
    var this_month = new Date($scope.year, $scope.month, 1);
    var next_month = new Date($scope.year, $scope.month + 1, 1);
    var first_week_day = this_month.getDay();
    var daysInMonth = Math.round((next_month.getTime() - this_month.getTime()) / (1000 * 60 * 60 * 24));
    var count = 0;
    var week = 0;

    $scope.days[week] = new Array(7);
    for(count; count < first_week_day; count++) 
    {
      $scope.days[week][count] = {day: ' ', total: ' '};
    }

    var realDate = $scope.year+'-'+($scope.month+1)+'-'+01;
    var lastDate = $scope.year+'-'+($scope.month+1)+'-'+daysInMonth;
    var query = {date: realDate, endDate: lastDate, id_account: $scope.id_account};
    $http.post('client/getPurchasesByDay',query).then(function (response)
    {
      totalPurchases = response.data;
      

      var start = first_week_day;
      for(var day_counter = 1; day_counter <= daysInMonth; day_counter++) 
      {
        start %= 7;
        if(start == 0)
        {
          week++;
          $scope.days[week] = new Array(7);
          count = 0;
        }

        var totalDay = 0;
        if(totalPurchases != 0)
        {
          for(var i = 0; i < totalPurchases.length; i++)
          {
            var dayNumber = totalPurchases[i].date.split("-");
            if(dayNumber[2] == day_counter)
            {
              totalDay += Number(totalPurchases[i].amount);
            }
          }
        }
        
        if(totalDay < 0)
        {
          $scope.days[week][count] = {day: day_counter, total: ("-$"+(-totalDay))};
        }
        else if(totalDay > 0)
        {
          $scope.days[week][count] = {day: day_counter, total: ("+$"+totalDay)};
        }
        else
        {
          $scope.days[week][count] = {day: day_counter, total: " "};
        }

        $scope.totalAmount += totalDay;
        start++;
        count++;
        
      }
      if($scope.totalAmount < 0)
      {
        $scope.totalAmount = "-$"+(-$scope.totalAmount);
      }
      else if($scope.totalAmount > 0)
      {
        $scope.totalAmount = "+$"+$scope.totalAmount;
      }
      else
      {
        $scope.totalAmount = "$"+$scope.totalAmount;
      }
    }, 
    function (error) 
    {
      $scope.status = 'Unable to load customer data.';
    });
    
  }

  $scope.minusMonth = function()
  {
    if($scope.month == 0)
    {
      $scope.month = 11;
      $scope.year--;
    }
    else
    {
      $scope.month--;
    }
    $scope.days = new Array(7);
    $scope.getDays();
  }
  $scope.plusMonth = function()
  {
    if($scope.month == 11)
    {
      $scope.month = 0;
      $scope.year++;
    }
    else
    {
      $scope.month++;
    }
    $scope.days = new Array(7);
    $scope.getDays();
  }
  $scope.minusYear = function()
  {
    $scope.year--;
    $scope.days = new Array(7);
    $scope.getDays();
  }
  $scope.plusYear = function()
  {
    $scope.year++;
    $scope.days = new Array(7);
    $scope.getDays();
  }
  $scope.loadLocal = function()
  {
    if(localStorage.getItem("id_account")) 
    {
      $scope.id_account = localStorage.getItem("id_account");
    }
    var headerNavs = angular.element('#menu-header').children();
    headerNavs[0].innerHTML = "<a href='#account'>ACCOUNT</a>";
    headerNavs[1].innerHTML = "<a href='#profile'>PROFILE</a>";
    headerNavs[2].innerHTML = "<a href='#logout'>LOG OUT</a>";
    var mainNavs = angular.element("#mainheader").children();
    mainNavs[3].innerHTML = "<a href='#account'>ACCOUNT</a>";
    mainNavs[4].innerHTML = "<a href='#profile'>PROFILE</a>";
    mainNavs[5].innerHTML = "<a href='#logout'>LOG OUT</a>";
    $scope.getDays(); 
  }
  $scope.loadLocal();
});