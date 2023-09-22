$(function(){
    $('body').on('change','[name=ProfileID]',function(){
        var E = $(this);
        var Value = parseInt(E.val());
        $('[name=RoleID]').html('');
        if(Value > 0){
            Request(URI('Json/FetchData'),function(Data){
                //Appending Roles
                $('[name=RoleID]').html(GenerateOptions(ToOptionItems(Data.Roles)));
            },{
                Fetch:'ProfileRelateables',
                By:'SubProject',
                Key:Value
            });
        }
    });
});
