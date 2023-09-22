var Context,
    Recorder,
    Volume,
    VolumeLevel = 0,
    Form;
$(function(){
    Form = $('form[name=TestForm]');
    $('body').on('click','[data-type=StartRecording]',function(){
        var E = $(this);
        E.hide();
        $('[name=SubmitTest]').show();
        $('[data-type=Started]').show();
        StartRecording();
    });
    $('body').on('click','[name=SubmitTest]',function(){
        StopRecording();
        $('[data-type=Started]').hide();
        return false;
    });
});

function InitiateMedia(Stream) {
    var Input = Context.createMediaStreamSource(Stream);
    Volume = Context.createGain();
    Volume.gain.value = VolumeLevel;
    Input.connect(Volume);
    Volume.connect(Context.destination);
    Recorder = new Recorder(Input);
}

function StartRecording(){
    Recorder && Recorder.record();
}

function StopRecording(){
    Recorder && Recorder.stop();
    GenrateAudio();
    Recorder.clear();
}

function GenrateAudio(){
    Recorder && Recorder.exportWAV(WavHandler.bind(this));
}

function WavHandler(Blob){
    var Data = new FormData();
    Data.append('Audio',Blob,'RecordedAudio.wav');
    Data.append('ProfileID',ProfileID);
    $.ajax({
        type: 'POST',
        url: URI('Tests/SaveAudio'),
        data: Data,
        processData: false,
        contentType: false,
        beforeSend: function(XHR){
            StartLoader();
        },
        complete: function(XHR){
            StopLoader();
        }
    }).done(function(Data) {
        if(Data.Status == 'Success'){
            $('[name=RecordingName]').val(Data.File);
        }
        Form.submit();
    });
}
window.onload = function(){
    try{
        window.AudioContext = window.AudioContext || window.webkitAudioContext || window.mozAudioContext;
        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia;
        window.URL = window.URL || window.webkitURL || window.mozURL;
        Context = new AudioContext();
    }catch(Execption){
        console.warn('No web audio support in this browser!');
    }
    navigator.getUserMedia({audio: true}, InitiateMedia, function(Execption){
        console.warn('No live audio input: ' + Execption);
    });
};
