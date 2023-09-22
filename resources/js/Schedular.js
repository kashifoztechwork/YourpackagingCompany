$(function () {
    var date = new Date();
    date.setDate(date.getDate() - 2);
    var CYear = date.getFullYear();
    var CMonth = date.getMonth();
    var CDate = date.getDay();
    $('[data-type=InterviewDate]').datepicker({
        dateFormat: "mm/dd/yy",
        startDate: new Date(CYear,CMonth,CDate)
    });

    $('body').on('change', '[name=InterviewDate]', function () {

        var Element = $(this);
        var Val = Element.val();
        Request(URI('InterviewScheduling/Slots'), function (Data) {

            $('[name=InterviewHourslots]').html(CreateOptions(Data.Items));

        }, {
                Date: Element.val()
            });
    });
    $('body').on('change', '[name=Status]', function () {
        var Element = $(this);
        var Val = Element.val();
        var Hiders = $('[name=InterviewDate], [name=InterviewHourslots]');

        if (Val == 'Interview Scheduled' || Val == 'Rescheduled') {

            Hiders.parents('.form-group').show();
            Hiders.removeAttr('disabled');
            Hiders.attr('required', true);
           
            

            

        } else {
            Hiders.parents('.form-group').hide();
            Hiders.attr('disabled', true);
        }
    })
    $('[name=Status]').change();
});
function CreateOptions(Items) {

    var HTML;
    if (Items != null) {
        for (var I in Items) {

            var Item = Items[I];
            HTML += '<option value="' + (Item.ID) + '">' + (Item.Name) + '</option>';
        }
    }
    return HTML;
}