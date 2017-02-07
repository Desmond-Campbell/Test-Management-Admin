app.controller('EditAccessCtrl', ['$scope', '$http', '$timeout', function ( $scope, $http, $timeout ) {

	$scope.network_id = $( '#network_id').val();
	$scope.person_id = $( '#person_id').val();

	// Remove person

	$scope.removePerson = function () {

		$id = $scope.network_id;
		$person_id = $scope.person_id;

		$http.delete( '/network/' + $id + '/person/' + $person_id + '/remove' ).then( 
			
			function ( r ) {
				
				if ( typeof r.data.errors != 'undefined' ) {

					_alert( r.data.errors, 1 );

				} else {
					
					to( '/network/' + $id + '/people' );

				}
			
			},

			function () {

				_alert( 'Failed to remove person from network.' );

			});

	};

	// Checkbox functions for persons		

		$scope.permissions = [];
		$scope.selected = [];
		$scope.permission_info = {};
		$scope.person = {};
	  $scope.dirty_permissions = false;

		$scope.getPerson = function () {

	  	$scope.dirty_permissions = false;
			$id = $scope.network_id;
			$person_id = $scope.person_id;

			$http.get( '/network/' + $id + '/person/' + $person_id + '/get' ).then( 
				
				function ( r ) {
					
					if ( typeof r.data.errors != 'undefined' ) {

						_alert( r.data.errors, 1 );

					} else {
						
						$scope.permissions = r.data.permissions;
						$scope.selected = r.data.selected_permissions;
						$scope.permission_info = r.data.permission_info;
						$scope.person = r.data.person;

					}
				
				},

				function () {

					_alert( 'Failed to load permissions for this person.' );

				});

		};

		$scope.getPerson();

	  $scope.toggle = function (permission, list) {
	  	$scope.dirty_permissions = true;
	    var idx = list.indexOf(permission);
	    if (idx > -1) {
	      list.splice(idx, 1);
	    }
	    else {
	      list.push(permission);
	    }
	  };

	  $scope.exists = function (permission, list) {
	    return list.indexOf(permission) > -1;
	  };

	  $scope.isIndeterminate = function() {
	    return ($scope.selected.length !== 0 &&
	        $scope.selected.length !== $scope.permissions.length);
	  };

	  $scope.isChecked = function() {
	    return $scope.selected.length === $scope.permissions.length;
	  };

	  $scope.toggleAll = function() {
	  	$scope.dirty_permissions = true;
	    if ($scope.selected.length === $scope.permissions.length) {
	      $scope.selected = [];
	    } else if ($scope.selected.length === 0 || $scope.selected.length > 0) {
	      $scope.selected = $scope.permissions.slice(0);
	    }
	  };

	  $scope.savePermissions = function () {

			$id = $scope.network_id;
			$person_id = $scope.person_id;

			$http.post( '/network/' + $id + '/person/' + $person_id + '/update', { 'permissions' : $scope.selected } ).then( 
				
				function ( r ) {
					
					if ( typeof r.data.errors != 'undefined' ) {

						_alert( r.data.errors, 1 );

					} else {
						
						$scope.dirty_permissions = false;
						$scope.getPerson();
						
					}
				
				},

				function () {

					_alert( 'Failed to update permissions for this person.' );

				});

		};

}]);
