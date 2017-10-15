/**
 * Created by Gabriel on 12/10/2017.
 */
app.controller('listController', function($rootScope,$interval,$location,$websocket,$http) {



    $rootScope.load=function (id,playOnLoad) {

       var data = {
           'act':'link',
           'id':id

       };

        $rootScope.ytList[id].loading = true;

        $http.get('app/api/youtube.php',{
                params:data
            })
            .then(function (res, status, headers, config) {

                console.log(res);
                $rootScope.ytList[id]['src'] = res.data.src;
                    $rootScope.ytList[id].loading = false;
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
            $rootScope.loaded.id = id;
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

            audioElement.ontimeupdate=function (e) {

                $rootScope.loaded.progress= (audioElement.played.end(0) *100) /  audioElement.duration;

                scope.$apply();

            }
            audioElement.onended = function() {

                $rootScope.next();
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

    $rootScope.next=function () {
      var keys =   Object.keys($rootScope.ytList);

       var idx= keys.indexOf($rootScope.loaded.id);

        var nextIdx =idx+1;
        if(keys.length > nextIdx)
        {
           $rootScope.load(keys[nextIdx],true);
        }



    }
    $rootScope.prev=function () {
        var keys =   Object.keys($rootScope.ytList);

        var idx= keys.indexOf($rootScope.loaded.id);

        var prevIdx =idx-1;
        if(prevIdx > -1)
        {
            $rootScope.load(keys[prevIdx],true);
        }



    }

});