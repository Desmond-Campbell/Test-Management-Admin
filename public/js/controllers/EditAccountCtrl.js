app.controller('EditAccountCtrl', ['$scope', '$http', function ( $scope, $http ) {

  $scope.account = {};
  $scope.working = false;
  $scope.success = false;
  $scope.failure = false;

  $scope.update = function () {

    $scope.working = true;
    $scope.success = false;
    $scope.failure = false;

    $http.post( '/account/update', $scope.account ).then(
      
      function ( response ) {

        $scope.clearPasswords();
   
        if ( typeof response.data.errors !== 'undefined' ){

          $scope.working = false;
          $scope.success = false;
          $scope.failure = true;
          $scope.error = response.data.errors;

        } else {

          $scope.working = false;
          $scope.success = true;
          $scope.failure = false;

        }

      },
      function () {

        $scope.clearPasswords();
        
        $scope.working = false;
        $scope.success = false;
        $scope.failure = true;
        $scope.error = _tt('Sorry, we were not able to update your account.');

      });
  };

  $scope.clearPasswords = function () {

    $scope.account.current_password = '';
    $scope.account.password = '';
    $scope.account.password2 = '';

  }

  $scope.getAccount = function () {

    $http.get( '/account/get' ).then( function( r ){ 

      if ( typeof r.data.errors === 'undefined' ) {

        $scope.account = r.data.account;

      } else {

        $scope.failure = true;
        $scope.success = false;
        $scope.working = false;
        $scope.error = r.data.errors;

      }

    },
    function () {
      
      $scope.failure = true;
      $scope.success = false;
      $scope.working = false;
      $scope.error = _tt('Sorry, we were not able to update your account.');

    })
  };

  $scope.getAccount();

}]);