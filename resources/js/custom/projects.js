$(function(){
    $('body').on('change','[data-id=GenrateTiers]',function(){
        var E = $(this);
        var Index = E.attr('data-index');
        var Value = E.val();
        //Append Policy Tiers Section
        for(var I in Value){
            var ID = Value[I];
            if($('[data-section="'+(Index)+'-SalaryPolicy"][data-id=Tiers][data-name='+(ID)+']').length == 0){
                DuplicateSubSection(E,'SalaryPolicy',Index,'Tiers',Value[I],E.children('option[value="'+(ID)+'"]').html());
            }
        }
        //Removing Unselected Tiers
        E.children('option').each(function(){
            var Option = $(this);
            var OptionValue = Option.val();
            if(Value.indexOf(OptionValue).toString() < 0){
                $('[data-section="'+(Index)+'-SalaryPolicy"][data-id=Tiers][data-name='+(OptionValue)+']').remove();
            }
        });
    });
});
