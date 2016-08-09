<html>
  <head> 
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/css/materialize.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <style>
      .timestamp {
        display: block;
      }
    </style>
  </head>
  <body ng-app='notes' ng-controller='notesController'>
      <nav>
        <div class="nav-wrapper">
          <a href="#" class="brand-logo center">Notes</a>
          <ul id="nav-mobile" class="right hide-on-med-and-down">
            <li><a ng-model="loginLogoutText" ng-click="loginlogout()" href="#">{{loginLogoutText}}</a></li>
          </ul>
        </div>
      </nav>
      <ul>
      <li ng-repeat="error in errors">{{error}}</li>
      </ul>
      <div class="container">
         <div ng-include="viewDisplayed">
        </div>
      </div>
     <script src="/moment.min.js"></script>
     <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.3.14/angular.min.js"></script>
     <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
     <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.97.7/js/materialize.min.js"></script> 
      <script>
        var app = angular.module('notes', []);
        app.filter('formatDate', function() {
          return function(dateString) {
              return moment(new Date(dateString)).format('ddd hh:mm');
            };
        });
        app.controller('notesController', ['$scope', '$http', '$timeout', function($scope, $http, $timeout){
          $scope.loggedIn = false;
          $scope.loginLogoutText = "Login";
          $scope.userEmail = "";
          $scope.userPassword = "";
          $scope.errors = [];
          $scope.notes = [];
          $scope.loginlogout = function() {
              if ( $scope.loggedIn ) $scope.logout();
              $scope.login();
          }

          function loggedIn(){
            $scope.loggedIn = true;
            $scope.loginLogoutText = "Logout";
            $scope.viewDisplayed = 'dash.html';
            $scope.errors = [];
            $scope.getnotes();
          }

          function loggedout(){
            $scope.loggedIn = false;
            $scope.loginLogoutText = "Login";
            $scope.viewDisplayed = 'login.html';
            $scope.userEmail = "";
            $scope.userPassword = "";
            $scope.errors = [];
          }

          loggedout();

          $scope.login= function(){
            $http({
              url: "/auth/login",
              method: "POST",
              headers: {
                'Content-Type': 'application/json'
              },
              data: JSON.stringify({email: $scope.userEmail, password: $scope.userPassword})
           }).then(function(response){
                $scope.user= response.data;
                loggedIn();
           }, function(response){
              $scope.errors = response.data;
            });
          }

          $scope.getNoteEditButtonText = function(note, index){
             if(note.editmode)
                return "Save";
             return "Edit"; 
          }

          $scope.editNote = function(note, index){
             if ( note.editmode ) {
                note.editmode = false;
                $http({
                  url: "/api/note",
                  method: "PUT",
                  headers: {
                    'Content-Type': 'application/json'
                  },
                  data: JSON.stringify(note)
               }).then(function(response){
               });
              }
              else
                note.editmode = true;
          }

          $scope.logout= function(){
            $http({
              url: "/api/logout",
              method: "GET",
              headers: {
                'Content-Type': 'application/json',
              }
           }).then(function(response){
                loggedout();
           });
          }

          $scope.postnote= function(){
            $http({
              url: "/api/note",
              method: "POST",
              headers: {
                'Content-Type': 'application/json'
              },
              data: JSON.stringify({title: 'Untitled', desc: ''})
           }).then(function(response){
              $scope.notes.unshift(JSON.parse(response.data));
           });
          }  
          $scope.removeNote = function(note) {
            $http({
              url: "/api/note",
              method: "DELETE",
              headers: {
                'Content-Type': 'application/json',
              },
              data: JSON.stringify({id: note.id})
           }).then(function(response){
             $scope.notes.splice($scope.notes.indexOf(note), 1); 
           }, function(response){
            });
          }
          $scope.getnotes= function(){
            $http({
              url: "/api/notes",
              method: "GET",
              headers: {
                'Content-Type': 'application/json',
              }
           }).then(function(response){
              $scope.notes = response.data;
              if ( !$scope.loggedIn )
                loggedIn();
           }, function(){
              loggedout();
            });
          }
          $scope.getuser= function(){
            $http({
              url: "/api/user",
              method: "GET",
              headers: {
                'Content-Type': 'application/json',
              }
           }).then(function(response){
              $scope.user= response.data;
           }, function(){
            });
          }
          $scope.getnotes();
          $scope.getuser();
        }]);
      </script>
    </body>
</html>
