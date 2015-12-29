
////////////////////////////////////////////////////////////////////////////////
// App Declaration /////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


var app = angular.module('app', []);


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Controllers /////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


app.controller('mainController', function($scope, $http) {

	/**
	 * Function to fetch filtered results data.
	 * @param {object} filters - Object that contains mapping of filter names 
	 * to values.
	 */ 
	$scope.filter = function(filters) {
		$http.post("src/api.php?query=filter_results", filters).then(function(response) {
			$scope.results = response.data;

		}, function(failure) {
			console.log(failure);
		});
	};


	/**
	 * Function that initializes app by fecthing results, course, and quarter data.
	 */
	$scope.reset = function() {
		$scope.filters = {};
		$scope.users;

		$http.get("src/api.php").then(function(response) {
			$scope.results = response.data.results;
			$scope.courses = response.data.courses;
			$scope.quarters = response.data.quarters;

		}, function(failure) {
			console.log(failure);
		});
	};


	/**
	 * Function that deletes one results record.
	 * @param {object} to_delete - Object that contains info used for selecting
     * record to delete.
	 * @param {int,string} admin
	 * @param {bool} is_user
	 */
	$scope.delete = function(to_delete, admin, is_user) {
		var url = "src/api.php?delete=" + String(to_delete);
		url += "&admin=" + String(admin) + "&user=" + String(is_user);
		
		$http.get(url).then(function(response) {
			$scope.reset();
			
			if (is_user)
				$scope.loadAdmin(admin, false);

		}, function(failure) {
			console.log(failure);
		});
	};


	/*
	 * Function that inits admin UI by fetching user data.
	 * @param {int,string} uid - ID of user making the request.
	 * @param {bool} show_call - Bool that determines if UI components
	 * should be toggled and if get request should be made.
	 */
	$scope.loadAdmin = function(uid, show_call) {
		$scope.showUserAddRow = false;

		if (show_call)
		{
			$("#standard_ui").toggle();
			$("#admin_ui").toggle();
			$("#filter_form").toggle();
		}

		if ($scope.users === undefined || !show_call)
		{
			$http.get("src/api.php?admin=" + String(uid)).then(function(response) {
				$scope.users = response.data.users;
				$scope.user_types = response.data.user_types;
			}, function(failure) {
				console.log(failure);
			});
		}
	};

	
	/*
	 * Function that submits request to create new user.
	 * @param {object} new_user
	 * @param {int,string} admin
	 */
	$scope.saveUser = function(new_user, admin)
	{
		if (Object.size(new_user) != 5)
			return;

		var url = "src/api.php?admin=" + String(admin);
		url += "&new_user=" + JSON.stringify(new_user);

		$http.post(url, new_user).then(function(response) {
			$scope.loadAdmin(admin, false);
			$scope.new_user = {};
			$scope.showUserAddRow = false;
			console.log(response.data);
		}, function(failure) {
			console.log(failure);
		});
	};


	// init app
	$scope.reset();
});


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Filters /////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});


app.filter('date', function() {
	return function(input) {
		return new Date();
	}
});


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// Helpers /////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////


/*
 * Function that returns the lenght of an object.
 * @param {object} obj - Object whose length is to be found.
 * @returns {int} - Length of obj.
 */
Object.size = function(obj) {
	var size = 0;
	
	for (var key in obj)
	{
		if (obj.hasOwnProperty(key))
			++size;
	}

	return size;
};


////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
