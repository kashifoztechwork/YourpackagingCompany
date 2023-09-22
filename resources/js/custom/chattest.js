var Questions = {
    1 : 'Hi, how are you doing?',
    2 : 'Why do you want to work at Mindbridge?',
    3 : 'What is your strongest skill?',
    4 : 'What is your biggest weakness?',
    5 : 'What achievement  makes you proud of yourself?',
    6 : 'What is your biggest regret in life?'
};
var AutoReply = 30;
var NoReply = true;
var AutoName = 'HR Representative';
var AutoInterval = 0;
var LastMessage = 0;
var NoReplyInterval = 0;
var AddedMessages = {1:[],2:[]};
$(function(){
    AutoMessage(1);
    $('body').on('submit','[name=TestForm]',function(){
        if(LastMessage < Count(Questions)){
            var E = $(this);
            var Message = $('input[name=Message]');
            var Text = Message.val();
            if(Text != ''){
                SendMessage(Text,true);
                Message.val('');
            }
            return false;
        }else{
            $('[name=Messages]').val(JSON.stringify(AddedMessages));
        }
    });
    OnNoReply();
});

function OnNoReply(){
    NoReplyInterval = setInterval(function(){
        if(NoReply){
            AutoMessage(LastMessage);
        }
    },AutoReply * 1000);
}

function AutoMessage(Index){
    if(typeof Questions[Index] != 'undefined'){
        SendMessage(Questions[Index],false);
        LastMessage = Index;
    }
}

function SendMessage(Message,Me){
    var Time = new Date();
    Time = LeadZero(Time.getHours()) + ':' + LeadZero(Time.getMinutes()) + ':' + LeadZero(Time.getSeconds());
    var HTML = '<li class="clearfix'+(Me ? ' odd' : '')+'"><div class="chat-avatar"><i>'+(Time)+'</i></div><div class="conversation-text"><div class="ctext-wrap"><i>'+(Me ? MyName : AutoName)+'</i><p>'+(Message)+'</p></div></div></li>';
    $('[data-type=ChatList]').append(HTML);
    if(Me){
        window.clearInterval(NoReplyInterval);
        NoReply = false;
        var AddableMessage = {};
        eval('AddableMessage = {'+(LastMessage)+' : Message + \' - \' + Time}');
        AddedMessages[2].push(AddableMessage);
    }else{
        AddedMessages[1].push(Message + ' - ' + Time);
    }
    AutoInterval = setTimeout(function(){
        if(Me){
            AutoMessage(LastMessage+1);
            NoReply = true;
            OnNoReply();
        }
        //window.clearTimeout(AutoInterval);
    },4000);
}

function LeadZero(Num){
    return Num > 9 ? Num : '0'+Num;
}
