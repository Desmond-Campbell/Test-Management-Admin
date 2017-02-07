app.controller('NewPersonCtrl', ['$scope', '$http', '$timeout', function ( $scope, $http, $timeout ) {

	$scope.network_id = $( '#network_id').val();
	$scope.person = {};

	// Create person

	  $scope.save = function () {

			$id = $scope.network_id;

			$http.post( '/network/' + $id + '/person/create', $scope.person ).then( 
				
				function ( r ) {
					
					if ( typeof r.data.errors != 'undefined' ) {

						_alert( r.data.errors, 1 );

					} else {
						
						if ( typeof r.data.result_id != 'undefined' ) {

							to( '/network/' + $id + '/person/' + r.data.result_id + '/edit' );

						} else {

							to( '/network/' + $id + '/people' );

						}
						
					}
				
				},

				function () {

					_alert( 'Failed to add this person.' );

				});

		};

}]);
