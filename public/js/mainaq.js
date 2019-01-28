var app = angular.module('mainApp', ['ngRoute', 'angularUtils.directives.dirPagination', 'restangular', 'ngFileSaver', 'growlNotifications']);

app.config(['$routeProvider', function($routeProvider) 
{
    $routeProvider.
        when('/', {
            templateUrl: 'templates/books.html',
            controller: 'BooklistController'
        }).
        when('/books', {
            templateUrl: 'templates/books.html',
            controller: 'BooklistController'
        });
}]);

function updataDataTable(originalData, id, response)
{
    angular.forEach(originalData, function(item, key) 
    {
        if (item.id == id)
        {
            originalData[key] = response;
        }
    });
    
    return originalData;
}

app.factory('currentScope', function(){
   var currentScope = {};

   return currentScope;
})

app.factory('dataFactory', function($http) 
{
    var dataService = {
        httpRequest: function(url, method, params, dataPost, upload) 
        {
            var passParameters = {};
            passParameters.url = url;
              
            if (typeof method == 'undefined') {
                passParameters.method = 'GET';
            } else {
                passParameters.method = method;
            }
              
            if (typeof params != 'undefined') {
                passParameters.params = params;
            }
              
            if (typeof dataPost != 'undefined') {
                passParameters.data = dataPost;
            }
              
            if (typeof upload != 'undefined') {
                passParameters.upload = upload;
            }
              
            var promise = $http(passParameters).then(function(response) 
            {
                return response.data;                
            }, 
            function(response)
            {                
                if (response.status == '500') 
                {
                    currentScope.notifications.push('An error occured while processing your request.');
                    return {
                        message: 'error',
                        data: { 
                            message: [response.statusText]
                        }
                    };
                    
                } else 
                {
                    return {
                        message: 'error',
                        data: response.data
                    };
                }
            });
              
            return promise;
        }
    };
  
    return dataService;
});