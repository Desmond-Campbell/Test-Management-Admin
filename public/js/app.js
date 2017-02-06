var app = angular.module('testmy',  [ 'ngMaterial' ]);

function _alert( text, no_translate = false ) {

  alert( text );

}

function slugify(text)
{
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}

app.controller('CreateCtrl', ['$scope', '$http', function ( $scope, $http ) {

  $scope.network = {};
  $scope.newnetwork = {};

  $scope.save = function () {

    if ( !( typeof $scope.network.name == 'undefined' || $scope.network.name == null || $scope.network.name == '' ) ) {

      if ( typeof $scope.network.domain == 'undefined' || $scope.network.domain == null || $scope.network.domain == '' ) {

        $scope.network.domain = slugify( $scope.network.name );

      }

      $http.post( '/create-network', $scope.network ).then( function( r ) {

        if ( typeof r.data.errors != 'undefined' ) {

          _alert( r.data.errors, 1 );

        } else {
          
          $scope.newnetwork = r.data.network;

        }

      }, 

      function () {

      });

    }
  
  };

  $scope.slugifyDomain = function () {

    $scope.network.domain = slugify( $scope.network.domain );

  };

}]);