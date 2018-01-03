$(document).ready(function () {
	alert('Play started');
	$('.audio').play(function(){
		alert('Play started');
		})
		
	
	}

        var audio;
        //jInit is my own site standard which is triggered after aynschronous loading of javascript
        //libraries. You can here use $(document).ready instead, in general case.
        function jInit(){
            audio = $(".audio");
            addEventHandlers();
        }
 
        function addEventHandlers(){
            $("a.load").click(loadAudio);
            $("a.start").click(startAudio);
            $("a.forward").click(forwardAudio);
            $("a.back").click(backAudio);
            $("a.pause").click(pauseAudio);
            $("a.stop").click(stopAudio);
            $("a.volume-up").click(volumeUp);
            $("a.volume-down").click(volumeDown);
            $("a.mute").click(toggleMuteAudio);
        }
 
        function loadAudio(){
            audio.bind("load",function(){
                $(".alert-success").html("Audio Loaded succesfully");
            });
            audio.trigger('load');
        }
 
        function startAudio(){
            audio.trigger('play');
        }
 
        function pauseAudio(){
            audio.trigger('pause');
        }
 
        function stopAudio(){
            pauseAudio();
            audio.prop("currentTime",0);
        }
 
        function forwardAudio(){
            pauseAudio();
            audio.prop("currentTime",audio.prop("currentTime")+5);
            startAudio();
        }
 
        function backAudio(){
            pauseAudio();
            audio.prop("currentTime",audio.prop("currentTime")-5);
            startAudio();
        }
 
        function volumeUp(){
            var volume = audio.prop("volume")+0.2;
            if(volume >1){
                volume = 1;
            }
            audio.prop("volume",volume);
        }
 
        function volumeDown(){
            var volume = audio.prop("volume")-0.2;
            if(volume <0){
                volume = 0;
            }
            audio.prop("volume",volume);
        }
 
        function toggleMuteAudio(){
            audio.prop("muted",!audio.prop("muted"));
        }
       