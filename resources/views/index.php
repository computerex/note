<html>
  <head> 
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
   <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
</head>
    <body ng-app='notes' ng-controller='notesController'>
     <button ng-click="submit()">Submit</button> 
      <script>
        var app = angular.module('notes', []);
        app.controller('notesController', ['$scope', '$http', function($scope, $http){
          $scope.submit = function(){
            $http({
              url: "/auth/login",
              method: "POST",
              headers: {
                'Content-Type': 'application/json'
              },
              data: JSON.stringify({email: 'mohd.ali.el@gmail.com', password: 'foobar'})
           }).then(function(response){
            }, function(response){
             });
            }
        }]);
      </script>
    </body>
</html>
