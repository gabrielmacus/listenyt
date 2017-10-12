
<?php
include "app/autoload.php";
?>
<!DOCTYPE html>
<html>
<head>
    <title>TimerApp</title>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <script src="js/angular.min.js"></script>
    <script src="js/angular-route.min.js"></script>
    <script src="js/jquery-param.min.js"></script>
    <script src="js/angular-websocket.min.js"></script>
    <link href="css/roboto/stylesheet.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">

</head>
<body data-ng-app="app"  data-ng-controller="mainController">

    <script>

        function error() {
            alert("Error");
        }


        var app = angular.module('app', ['ngRoute','ngWebSocket']);

        app.controller('mainController', function($rootScope,$http,$location) {

            scope = $rootScope;
            $rootScope.lang = <?php echo json_encode($_LANG);?>;

            $rootScope.ytList = [] ;
            
            $rootScope.search=function () {

                $rootScope.search.act ='list';

                $http.get('app/api/youtube.php',{
                        params:$rootScope.search
                })
                    .then(function (res, status, headers, config) {


                            $rootScope.ytList = res.data;
                            $location.path( '/list' );

                    },
                        error)
            }

        });

        app.config(function($routeProvider) {
            $routeProvider

                .when("/", {
                    templateUrl : "views/home.html",
                    controller:'homeController'
                })
                .when('/list',
                    {
                        templateUrl:'views/list.html',
                        controller:'listController'
                    })
                .when('/player',
                    {
                        templateUrl:'views/player.html',
                        controller:'playerController'
                    })
        });
    </script>
    <script src="js/controllers/homeController.js"></script>
    <script src="js/controllers/listController.js"></script>
    <section  data-ng-style='containerStyle' class="animated main-container" data-ng-view>
    </section>

</body>
</html>

