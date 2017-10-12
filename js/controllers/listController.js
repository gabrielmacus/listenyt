/**
 * Created by Gabriel on 12/10/2017.
 */
app.controller('listController', function($rootScope,$interval,$location,$websocket,$http) {



    $rootScope.load=function (id,playOnLoad) {

       var data = {
           'act':'link',
           'id':id

       };

        $http.get('app/api/youtube.php',{
                params:data
            })
            .then(function (res, status, headers, config) {

                console.log(res);
                $rootScope.ytList[id]['src'] = res.data.src;

                    if(playOnLoad)
                    {
                        $rootScope.play(id);
                    }



                },
                error)
    }

    $rootScope.play=function (id) {
        var audioElement = document.querySelector("audio");
        if(id)
        {
            $rootScope.loaded = $rootScope.ytList[id];
        }
        if(!$rootScope.loaded.ready)
        {
            audioElement.oncanplay = function() {
                audioElement.play();
                $rootScope.loaded.state = 'playing';
                $rootScope.loaded.ready=true;
                setTimeout(function () {
                    $rootScope.$apply();
                })
            };
        }
       else
        {
            audioElement.play();
            $rootScope.loaded.state = 'playing';

        }

    }

    $rootScope.pause=function (id) {

        var audioElement = document.querySelector("audio");
        audioElement.pause();
        $rootScope.loaded.state = 'paused';
    }



});