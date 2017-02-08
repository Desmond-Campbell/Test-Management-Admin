app.controller('CreateCtrl', ['$scope', '$http', function ( $scope, $http ) {

  $scope.network = { timezone : '', name : '', domain : '' };
  $scope.newnetwork = {};
  $scope.working = false;

  $scope.save = function () {

    if ( !( typeof $scope.network.name == 'undefined' || $scope.network.name == null || $scope.network.name == '' ) ) {

      if ( typeof $scope.network.domain == 'undefined' || $scope.network.domain == null || $scope.network.domain == '' ) {

        $scope.network.domain = slugify( $scope.network.name );

      }

      $scope.working = true;

      $http.post( '/create-network', $scope.network ).then( function( r ) {

        $scope.working = false;

        if ( typeof r.data.errors != 'undefined' ) {

          _alert( r.data.errors, 1 );

        } else {
          
          $scope.newnetwork = r.data.network;

        }

      }, 

      function () {

        $scope.working = false;

        _alert( 'We were not able to create your network. Sorry about that. Please contact support.' );

      });

    }
  
  };

  $scope.slugifyDomain = function () {

    $scope.network.domain = slugify( $scope.network.domain );

  };

}]);