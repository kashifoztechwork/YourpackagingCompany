(function($){
    'use strict';
    var Form = $('[data-type=Wizard]');
    var Validator = null;    
    $('[data-type=Steps]').steps({
        headerTag: 'h3',
        bodyTag: 'section',
        onStepChanging: function(Event,Current,New) {
            Validator = Form.validate({
                errorPlacement: function(Label,E){
                    Label.addClass('mt-2 text-danger');
                    Label.insertAfter(E);
                },
                highlight: function(E,Class){
                    $(E).parent().addClass('has-danger');
                    $(E).addClass('form-control-danger');
                },
                rules:Rules,
                submitHandler: function(F){
                    return true;
                }
            });
            return Validator.form();
        },
        onFinishing: function(Event,Current){
            Form.submit();
        }
    });
})(jQuery);
