$(document).ready(function(){
    
    var tracks = JSON.parse($('#tracks').val()) //Parse the list of tracks choosen
    $('#valid-audio').get(0).volume = 0.2; //Initialize volume of valid sound
    $('#track-audio').get(0).volume = 0.5; //Initialize volume of track's sound

    //Initialize all values
    var trackGuess;
    var tracksGuessed = {}
    var nameTrack;
    var nameArtist;
    var nameAlbum;
    var points = 0;

    $('#modal-start').modal("show") //Start with show modal

  /* Event on click on back-party button, back to home */  
    $('.back-party').on("click", function(){
        $('#formReturn').submit()
    })

    /* Event on click on start-party button, start game */  
    $('#start-party').on("click", function(){
        $('#modal-start').modal("hide")
        NextTrack()
    })

    /* Event on click on next-party button, setup the next track with HTMLElement and var js */ 
    $('#next-party').on("click", function(){
        $('#modal-next').modal("hide")
        AddPreviousTrackList() //Add current rack to the previous track list
        NextTrack() //Initliaze next track
    })

    /* Event on change on sliding values, change the volume of track sound */  
    $('#volume-audio').on("change", function(){
        $('#track-audio').get(0).volume = $(this).val()
    })


    /* Function asynchrone that initializes next track */  
    async function NextTrack(){
        $('#text-check').focus(); //Add focus on iput text-check
        $('#text-check').val("");
        if($.isEmptyObject(tracksGuessed)){ //If is the first track
            trackGuess = tracks["1"] //TrackGuess is the track which must be found
            $('#number-track-guess').html("1") 
        } else { 
            var nextTrack = String(Object.keys(tracksGuessed).length + 1) //TrackGuess is the track next of the previous
            $('#number-track-guess').html(String(Object.keys(tracksGuessed).length + 1))
            trackGuess =  tracks[nextTrack] 
        }
        $('#preview-audio').attr("src", trackGuess["preview"])
        $('#track-audio').get(0).load()
        await $('#track-audio').get(0).play() //Play the sound of the track
        //Initialize HTMLElement
        $($('#track-name-id').parent().parent()).removeClass("text-success").addClass("text-danger")
        $('#track-name-id').html("NOT YET FOUND")
        $($('#artist-name-id').parent().parent()).removeClass("text-success").addClass("text-danger")
        $('#artist-name-id').html("NOT YET FOUND")
        $($('#album-name-id').parent().parent()).removeClass("text-success").addClass("text-danger")
        $('#album-name-id').html("NOT YET FOUND")
        //Initialize JS Variable
        nameTrack = trackGuess["title_short"].toLowerCase().replace(/ /g,"").replace(/ *\([^)]*\) */g, ""); //Change title_short to lowercase and remove space and parenthesis
        nameArtist = trackGuess["artist"]["name"].toLowerCase().replace(/ /g,"").replace(/ *\([^)]*\) */g, ""); //Change artist name to lowercase and remove space and parenthesis
        nameAlbum = trackGuess["album"]["title"].toLowerCase().replace(/ /g,"").replace(/ *\([^)]*\) */g, ""); //Change album title to lowercase and remove space and parenthesis
        StartTimer(); //Start timer
    }

    /* Function which add track guessed in a list and displays to the list list-previous-track*/  
    function AddPreviousTrackList(){
        var previousTrack = Object.values(tracksGuessed)[Object.keys(tracksGuessed).length - 1]
        var minutes;
        if((previousTrack["duration"] / 60).toString().indexOf(".")){
            minutes = (previousTrack["duration"] / 60).toString().split(".")[0]
        }
        var secondes = previousTrack["duration"] - (60 * minutes)
        var childrenPreviousTrack = '<li class="d-flex flex-row border-bottom"><img src="'+ previousTrack["album"]["cover_medium"] +'" class="summary-cover my-auto"><div class="d-flex flex-column w-100 ml-3 my-auto"><div class="d-flex w-100 justify-content-between"><div class="summary-name-track">'+ previousTrack["title_short"] +'</div><small class="mr-3 mt-1 summary-small">'+ minutes + "min " + secondes + "s"+'</small></div><div class="summary-name-artist">'+previousTrack["artist"]["name"]+'</div><div class="summary-name-album">from the album: '+previousTrack["album"]["title"] +'</div></div></li>'
        $('#list-previous-track').append(childrenPreviousTrack)
        $('#text-empty-previous').hide();
    }

    /* Function that start timer and set interval every second*/ 
    function StartTimer(){
        time = setInterval(Timer30, 1000);
    }

     /* Function called every second and update the timer-guess element*/ 
    function Timer30() {
        if(parseInt($('.timer-guess').html()) > 0){ //As long as the timer is under 30 seconds
            $('.timer-guess').html(parseInt($('.timer-guess').html())- 1)
        } else {
            AbortTimer() //Called the end of the time to guessed one track
        }
    }

/* Function called when a timer of guessed track is over*/ 
    function AbortTimer(){
        clearInterval(time); //Renews timer
        $('.timer-guess').html("30")
        var key = String(Object.keys(tracksGuessed).length + 1);
        tracksGuessed[key] = trackGuess;
        if(Object.keys(tracks).length == Object.keys(tracksGuessed).length){ //If is the last track show modal end
            AddPreviousTrackList();
            StockTracks()
            $('#track-audio').get(0).pause()
            $('#point-guess').html(points)
            $('#modal-end').modal("show")
        } else {
            $('#track-audio').get(0).pause()
            $('#modal-next').modal("show")
        }
    }

    /* Function called when a party is over, stock all the track not stored in the database*/ 
    function StockTracks(){
        $.ajax({ //Send HTTPRequest at stocktracks.php with all tracks as parameters
            type: "POST",
            contentType : "json",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            data : JSON.stringify(tracks),
            dataType : "json",
            url: "http://localhost/api/tracks/stocktracks.php",
        });
    }

    /* Event on keyup on text-check */  
    $('#text-check').keyup(function(){
        if($(this).val() != ""){ //If value is empty disabled the button confirm-check
            $('#confirm-check').prop( "disabled", false);
        } else {
            $('#confirm-check').prop( "disabled", true);
        }
    })

    /* Event on keypress on the key Enter*/  
    $(document).on('keypress',function(e) {
        if(e.which == 13) {
            if($('#text-check').is(":focus")){ //If input text-check is focus
                ConfirmCheck() // called the ConfirmCheck function
            }
        }
    });

    /* Function called when the button confirm-check is clicked or when the enter key is pressed*/ 
    function ConfirmCheck(){
        if($('#text-check').val() != ""){
            CheckResult($('#text-check').val()) //Check if the answer match with on of JS Variable
            $('#text-check').val("") 
        }
    }

    /* Event on click on the button confirm-check*/  
    $('#confirm-check').on('click', function(){
        ConfirmCheck() // called the ConfirmCheck function
    })

    /* Function which checks if the answer matches with JS Variable*/ 
    function CheckResult(text){
        var textFormated = text.toLowerCase().replace(/ /g,"").replace(/ *\([^)]*\) */g, "") //Change answer to lowercase and remove space and parenthesis
        if(textFormated == nameTrack){
            //Initialize HTMLElement of track name
            $($('#track-name-id').parent().parent()).removeClass("text-danger").addClass("text-success")
            $('#track-name-id').html(trackGuess["title_short"])
            nameTrack=""
            points +=1 //Add point
            $('#valid-audio').get(0).play(); //Play valid sound
        }
        if(textFormated == nameArtist){
            //Initialize HTMLElement of artist name
            $($('#artist-name-id').parent().parent()).removeClass("text-danger").addClass("text-success")
            $('#artist-name-id').html(trackGuess["artist"]["name"])
            nameArtist=""
            points +=1 //Add point
            $('#valid-audio').get(0).play(); //Play valid sound
        }
        if(textFormated == nameAlbum){
            //Initialize HTMLElement of album name
            $($('#album-name-id').parent().parent()).removeClass("text-danger").addClass("text-success")
            $('#album-name-id').html(trackGuess["album"]["title"])
            nameAlbum=""
            points +=1 //Add point
            $('#valid-audio').get(0).play(); //Play valid sound
        }
    }
});
