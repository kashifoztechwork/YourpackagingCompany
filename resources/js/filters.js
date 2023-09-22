
var SetFilters = function(Element,Options,CallBack){
    this.Containers = '[data-type=Filters]';
    this.FindElements = '[data-type=FilterItem]';
    this.ElementRow = '[data-type=FilterRow]';
    if(typeof Options != 'undefined'){
        for(var I in Options){
            this[I] = Options[I];
        }
    }
    var Options = $.extend(this,Options);
    //this = $.extend(this.element.data(), options);
    Element.on('change keyup keydown',function(){
        var Value = Element.val();
        if(Value != ''){
            $(Options.Containers).find(Options.ElementRow).hide();
            $(Options.Containers).find(Options.ElementRow+' '+Options.FindElements+":contains('"+(Value)+"')").each(function(){
                $(this).parents(Options.ElementRow).first().show();
            });
        }else{
            $(Options.Containers).find(Options.ElementRow).show();
        }
    });
}
$.fn.SetFilters = function(NewOptions,CallBack) {
    var E = $(this);
    var Container = E.attr('data-container');
    var Options = {};
    if(typeof Container != 'undefined' && Container != ''){
        Options['Containers'] = Container;
    }
    Options = $.extend(NewOptions,Options);
    new SetFilters($(this),Options,CallBack);
};
