$(document).ready(function(){

 /* Event on click on an element of the search list (tracks find by deezer) */   
$('#list-search-track li').on('click', function () {
    ClickTrack(this) //Launch function ClickTrack with element li as a parameter
})

/* Function ClickTrack with parameter li */   
function ClickTrack(li){
    $(li).off('click') //Disable the event click
    OnClickTrack($(li)) //Enable the event click (because when the li moved to the list list-choose-track all event are disabled)
    $('.text-muted-select').removeClass('text-muted-select').addClass('text-muted')
    if($('.list-track-item-select').length > 0){
        $('.list-track-item-select').removeClass('list-track-item-select')
    } else if($('.list-track-item-already-selected').length > 0){
        $('.list-track-item-already-selected').removeClass('list-track-item-already-selected')
    }
    if($(li).hasClass("list-track-item-already-actived")){
        $(li).addClass('list-track-item-already-selected')
    } else {
        $(li).addClass('list-track-item-select')
    }
    $(li).find(".text-muted").each(function() { 
        $(this).addClass('text-muted-select').removeClass('text-muted')
    });
}

/* Function for reactivating click event  */ 
function OnClickTrack(li){
    li.on('click', function() {
        ClickTrack(this)  //Launch function ClickTrack with element li as a parameter
    });
}


 /* Event on DoubleClick on an element of the search list (tracks find by deezer) */   
$('.search-track').on('dblclick', function() {
    SearchTrack(this)  //Launch function SearchTrack with element li as a parameter
});

 /* Event on DoubleClick on an element of the choose list (tracks doubleclicked in search list) */ 
$('.choose-track').on('dblclick',function() {
    ChooseTrack(this)  //Launch function ChooseTrack with element li as a parameter
});

/* Function for reactivating DoubleClick event on search list */ 
function DoubleClickSearchTrack(li){
    li.on('dblclick', function() {
        SearchTrack(this) //Launch function SearchTrack with element li as a parameter
    });
}

/* Function for reactivating DoubleClick event on choose list */ 
function DoubleClickChooseTrack(li){
    li.on('dblclick',function() {
        ChooseTrack(this) //Launch function ChooseTrack with element li as a parameter
    });
}

/* Function ChooseTrack with parameter li */   
function ChooseTrack(li){
    $(li).addClass('search-track').removeClass('choose-track') //Add class search-track to the li and remove the choose-track class
    $(li).off('dblclick') //Disabled DoubleClick event
    DoubleClickSearchTrack($(li)) //Enable the event DoubleClick (because when the li moved to the list list-choose-track all event are disabled)
    $('#text-empty-search').hide();
    $('#list-search-track').append(li) //Add li element to the list
    if($('#list-choose-track li').length < 1) {
        $('#text-empty-choose').show();
    }
    $('.number-track').html(parseInt($('.number-track').html()) - 1)
    if($('#list-choose-track li').length >= 3 && $('#list-choose-track li').length <= 20) { //Minimum track is 3 and Maximum is 20
        $('.number-track').addClass('text-success').removeClass('text-danger')
        $("#btn-guess").prop("disabled",false);
    } else {
        $('.number-track').addClass('text-danger').removeClass('text-success')
        $("#btn-guess").prop("disabled",true);
    }
}

/* Function SearchTrack with parameter li */   
function SearchTrack(li){
    $(li).addClass('choose-track').removeClass('search-track') //Add class choose-track to the li and remove the search-track class
    $(li).off('dblclick') //Disabled DoubleClick event
    DoubleClickChooseTrack($(li)); //Enable the event DoubleClick (because when the li moved to the list list-search-track all event are disabled)
    $('#text-empty-choose').hide();
    $('#list-choose-track').append(li)
    if($('#list-search-track li').length < 1) {
        $('#text-empty-search').show();
    }
    $('.number-track').html(parseInt($('.number-track').html()) + 1) //Add one to the count of tracks
    if($('#list-choose-track li').length >= 3 && $('#list-choose-track li').length <= 20) {
        $('.number-track').addClass('text-success').removeClass('text-danger')
        $("#btn-guess").prop("disabled",false);
    } else {
        $('.number-track').addClass('text-danger').removeClass('text-success')
        $("#btn-guess").prop("disabled",true);
    }
}

/* Event on click on the button confirm */   
$('#confirm-search').click(function(){
    ConfirmSearch()
})

/* Function ConfirmSearch called when button confirm-search is clicked or when key enter is pressed */   
function ConfirmSearch(){
    if($('#text-search').val() != ""){ 
        $('#list-search-track li').remove()
        $('#text-empty-search').hide();
        $('.lds-ring').show() //Load the loading ring
        $.ajax({ //Send HTTPRequest to the DeezerAPI with the text of the search
            type: "GET",
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            dataType : "json",
            url: "https://api.deezer.com/search/track?q=" + $('#text-search').val(),
            success: function(response) { //When the data is received, check if tracks are already stocked in the database
                response["data"].forEach(function(element){
                    if(!("preview" in element)){
                        response["data"].splice(response["data"].indexOf(element), 1);
                    }
                })
                $.ajax({ //Send HTTPRequest on alreadystocked.php with all track as parameters
                    type: "POST",
                    contentType : "json",
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    data : JSON.stringify(response["data"]),
                    dataType : "json",
                    url: "http://localhost/api/tracks/alreadystocked.php",
                    success: function(responseTwo) { //When the data is received, called function ResponseSearchAsync with the response as a parameter
                        ResponseSearchAsync(responseTwo);
                        $('.lds-ring').hide() //Hide loading ring
                    },
                });
            },
        });
    } else {
        $('#text-empty-search').show();
        $('#text-search').addClass('is-invalid')
        $('#invalid-search').removeClass('invisible')
    }  
}

/* Event on keyup on the input text text-search */  
$('#text-search').keyup(function(){
    if($(this).val() != ""){ //Check if the input is empty
        $(this).removeClass('is-invalid')
        $('#invalid-search').addClass('invisible')
    }
})

/* Function that displays tracks as an HTML element */  
function ResponseSearchAsync(tracks){
    $.each(tracks, function(index, value) {
        var classStocked = 'list-track-item-active'
        if(value["stocked"] == 1){
            classStocked = 'list-track-item-already-actived';
        }
        var minutes;
        if((value["duration"] / 60).toString().indexOf(".")){
            minutes = (value["duration"] / 60).toString().split(".")[0]
        }
        var secondes = value["duration"] - (60 * minutes);
        var childrenTrack = '<li class="list-track-item ' + classStocked +' search-track"><img src="'+ value["album"]["cover_medium"] +'" class="h-vw-6 my-auto"/><div class="d-flex flex-column w-100 ml-3 my-auto"><div class="d-flex w-100 justify-content-between"><h4 class="mb-1">'+ value["title_short"]+'</h4><small class="text-muted mr-3 mt-3">'+ minutes + "min " + secondes + "s" +'</small></div><h5 class="mb-1">'+ value["artist"]["name"] +'</h5><div class="text-muted">from the album: '+ value["album"]["title"] +'</div></div><input type="hidden" value="'+ JSON.stringify(value).replace(/"/g, "&quot;") +'"/></li>'
        $('#list-search-track').append(childrenTrack);
        DoubleClickSearchTrack($('#list-search-track li:last-child')); //Affect DoubleClick event
        OnClickTrack($('#list-search-track li:last-child')) //Affect Click event
    })
}

/* Event on click on the button trash-search which which allows you to empty the list search */  
$('#trash-search').click(function(){
    $('#list-search-track li').remove()
    $('#text-empty-search').show();
})

/* Event on click on the button trash-search which which allows you to empty the list choose */  
$('#trash-choose').click(function(){
    $('#list-choose-track li').remove()
    $('#text-empty-choose').show();
})

/* Event on click on the button btn-guess */  
$('#btn-guess').click(function(){
    $('#list-choose-track li').each(function(){ //For each li in choose list we add all the information of the tracks to the input tracks-choose
        if($('#tracksChoose').val() == ""){
            $('#tracksChoose').val("{ \"1\":" + $($(this).children()[2]).val())
        } else {
            $('#tracksChoose').val($('#tracksChoose').val() + ", \""+ $(this).index() +"\":" + $($(this).children()[2]).val())
        }
    })
    $('#tracksChoose').val($('#tracksChoose').val() + "}")
    $('#formChoose').submit()
})

/* Event on keypress on the key Enter*/  
$(document).on('keypress',function(e) {
    if(e.which == 13) {
        if($('#text-search').is(":focus")){ //If input search is focus
            ConfirmSearch()  // called the ConfirmCheck function
        }
    }
});

});


