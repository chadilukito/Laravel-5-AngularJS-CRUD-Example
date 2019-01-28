app.controller('HomeController', function($scope, $http)
{
    currentScope = $scope;
    $scope.pools = [];
});

app.controller('BooklistController', function(FileSaver, Blob, Restangular, dataFactory, $scope, $http)
{
    currentScope = $scope;
    $scope.notifications = [];
    $scope.errors = [];
    $scope.data = [];
    $scope.sortReverse = true;
    $scope.sortColumnName = '';
    $scope.dataTemp = {};
    $scope.totalDataTemp = {};
    $scope.totalData = 0;
    $scope.currentPage = 1;
    $scope.pageSize = 5;
    $scope.pageChanged = function(newPage) {
        getResultsPage(newPage);
    };
    $scope.exportMenu = ['Titles and Authors', 'Only Titles', 'Only Authors'];
    $scope.selectedExport = $scope.exportMenu[0];
    $scope.selectedExportId = 0;
    
    getResultsPage(1);
    
    $scope.resetForm = function() {
        angular.copy({}, $scope.form);
    }
    
    $scope.recordErrors = function (error) {
        $scope.errors = [];
        for (var key in error) 
        {
            $scope.errors.push(error[key][0]);
        }
    }
    
    function createQueryParams(pageNumber) 
    {
        $params = '';
        
        if (!$.isEmptyObject($scope.dataTemp))
        {
            $params = '&search='+$scope.searchText;
        } 
        
        if ($scope.sortColumnName) 
        {
            $params += '&order='+$scope.sortColumnName;
            
            if ($scope.sortReverse)
            {
               $params += '&sort=desc';
            } else {
               $params += '&sort=asc';
            }
        }
        
        if (pageNumber) 
        {
            $params += '&page='+pageNumber;
        }
        
        return $params;
    }
      
    function getResultsPage(pageNumber) 
    {
        $params = createQueryParams(pageNumber);
        
        dataFactory.httpRequest('/books?'+$params).then(function(data) 
        {
            $scope.data = data.data;
            $scope.totalData = data.total;
        });
    }
    
    $scope.searchData = function() {
        if ($scope.searchText.length >= 3)
        {
            if ($.isEmptyObject($scope.dataTemp))
            {
                $scope.dataTemp = $scope.data;
                $scope.totalDataTemp = $scope.totalData;
                $scope.data = {};
            }
            
            getResultsPage(1);       
            $scope.currentPage = 1;              
            
        } else 
        {
            if (!$.isEmptyObject($scope.dataTemp))
            {
                $scope.data = $scope.dataTemp;
                $scope.totalData = $scope.totalDataTemp;
                $scope.dataTemp = {};
            }
        }
    }
    
    $scope.sortColumn = function(col) {
        $scope.sortColumnName = col;
        
        if ($scope.sortReverse)
        {
           $scope.sortReverse = false;        
        } else {
           $scope.sortReverse = true;
        }
        
        getResultsPage(1);
        $scope.currentPage = 1;
    }
     
    $scope.sortClass = function(col) {
        if ($scope.sortColumnName == col)
        {
           if ($scope.sortReverse)
           {
              return 'fas fa-sort-down';                 
           } else {
              return 'fas fa-sort-up';
           }
           
        } else {
           return 'fas fa-sort';
        }
    } 
    
    
    $scope.saveAdd = function() {
        dataFactory.httpRequest('books', 'POST', {}, $scope.form).then(function(data) 
        {
            if (data.message) {
                $scope.recordErrors(data.data);
            } else 
            {
                $scope.data.push(data);
                $(".modal").modal("hide");
            }             
        });
    }
    
    $scope.edit = function(id) {
        dataFactory.httpRequest('books/'+id+'/edit').then(function(data) 
        {
            if (data.message) {
                $scope.recordErrors(data.data);
                $(".modal").modal("hide");
            } else {
                $scope.form = data;
            }            
        });
    }
      
    $scope.saveEdit = function() {
        dataFactory.httpRequest('books/'+$scope.form.id, 'PUT', {}, $scope.form).then(function(data) 
        {
            if (data.message) {
                $scope.recordErrors(data.data);
            } else 
            {                
                $scope.data = updataDataTable($scope.data, data.id, data);
                $(".modal").modal("hide");
            }     
        });
    }
      
    $scope.remove = function(book, index) {
        var result = confirm("Are you sure want to delete this book?");
        if (result) 
        {
            dataFactory.httpRequest('books/'+book.id, 'DELETE').then(function(data) 
            {
                if (data.message) {
                    //$scope.recordErrors(data.data);
                } else {
                    $scope.data.splice(index, 1);
                }               
            });
        }
    }
    
    
    $scope.dropboxExportSelected = function(item) { 
        $scope.selectedExport = item;
        
        for (i=0; i < $scope.exportMenu.length; i++) 
        {
            if ($scope.exportMenu[i] === item) 
            {
                $scope.selectedExportId = i;
                break;
            }
        }
    }
    
    $scope.downloadCSV = function() {
        $params = '?'+createQueryParams();
        Restangular.one('books/export/csv/'+$scope.selectedExportId+$params).withHttpConfig({responseType: 'blob'}).get().then(function(response) 
        {
             var file = new Blob([response], { type: 'text/csv;charset=utf-8' });
             FileSaver.saveAs(file, 'export.csv');
        });
    }
    
    $scope.downloadXML = function() {
        $params = '?'+createQueryParams();
        Restangular.one('books/export/xml/'+$scope.selectedExportId+$params).withHttpConfig({responseType: 'blob'}).get().then(function(response) 
        {
             var file = new Blob([response], { type: 'application/xml;charset=utf-8' });
             FileSaver.saveAs(file, 'export.xml');
        });
    }
});