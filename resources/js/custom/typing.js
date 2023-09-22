var BackspaceCount = 0;
var TotalChars = 0;
var TotalWords = 0;
var StartTime = 0;
var EndTime = 0;
var Progress = null;
var End = true;
var Stats = {};
var TestTime = 60;
$(function () {
    //On Form Submission
    $('body').on('submit', 'form', function () {
        $('[name=TypingEnd]').val(Date.now());
        $('[name=LettersRemoved]').val(BackspaceCount);
    });
    $('body').on('click','[data-type=StartTest]',function(){
        var E = $(this);
        End = false;
        StartTime = new Date().getTime();
        EndTime = StartTime + (TestTime * 1000)
        StartTest();
        E.parent().remove();
        $('textarea[name=TypingParagraph]').focus();
        $('[data-type=Submitable]').show();
    });
    $('[name=TypingParagraph]').on('keydown',function(event){
        var Code = event.which;
        if(Code == 8 || Code == 46){
            BackspaceCount++;
        }
    });
    $('body').on('submit','form[name=TestForm]',function(){
        End = true;
        $('[name=Stats]').val(JSON.stringify(Report));
    });
    $(window).bind('beforeunload', function(Event){
        if(!End){
            Event.preventDefault();
            return false;
        }
    });
});
function StartTest(){
    $('[data-type=TestWriter]').show();
    $('[name=TypingStart]').val(Date.now());
    Progress = setInterval(GetProgress,1);
}

function GetProgress(){
    var Text = $('textarea[name=TypingParagraph]').val();
    //Getting Time
    var Seconds = 0;
    var CurrentDate = new Date().getTime();
    if(EndTime >= CurrentDate){
        var SLeft = (EndTime - CurrentDate)/1000;
        SLeft = SLeft % 86400;
        SLeft = SLeft % 3600;
        Seconds = parseInt(SLeft % 60);
    }
    var Elaps = (TestTime - Seconds);
    //Getting Words Per minute
    var WPM = ((Text.split(' ').length - 1) * 60 / Elaps);
    Report = {
        WPM : Math.round(WPM)+' wpm',
        TimeLeft : Seconds +' seconds',
        TimeElaps : Elaps + ' seconds',
        Eraser : BackspaceCount
    }
    if(Seconds <= 0){
        End = true;
        $('[name=SubmitTest]').click();
        window.clearInterval(Progress);
    }
    for(var I in Report){
        $('[data-type=ProgressValue][data-id='+(I)+']').html(Report[I]);
    }
}
