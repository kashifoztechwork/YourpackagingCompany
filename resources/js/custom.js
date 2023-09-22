var Prevented = false;
var LocalTime = +Date.now();
var TimeDiffrence = SystemTime - LocalTime;
var TempData = {};
var DuplicatedSections = {};
var DuplicatedSubSections = {};
var DuplicatedCounts = {};
var DuplicatedSubSectionsCounts = {};
var RemoteData = {};
(function($){
    'use strict';
    $(function () {

        //Remote Details
        $('body').on('change','[data-type=GetRemoteData]',function(){
            var E = $(this);
            var Item = E.attr('data-item');
            var Remote = E.attr('data-remotelink');
            var Value = E.val();
            var Post = {PK:Value};
            Post[CFN] = CFH;
            Request(Remote ,function(Data){
                RemoteData[Item] = Data;
                UpdateRemoteViews();
            },Post);
        });

        //Details Pane
        $('body').on('click','[data-type=DetailsPane]',function(){
            var  E = $(this);
            var ID = E.attr('data-id');
            $('[data-type=DetailsPane]').not('[data-id="'+(ID)+'"]').parents('td').first().removeClass('table-dark')
            $('body [data-type=DetailsData]').not('[data-id="'+(ID)+'"]').hide();
            $('[data-type=DetailsData][data-id="'+(ID)+'"]').toggle();
            E.parents('td').first().toggleClass('table-dark')
        });

        //Editable List
        $('body').on('click','[data-type=Editable]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            $('[data-type=EditableField][data-id="'+(ID)+'"]').show();
            E.hide();
        });

        //Cancel Editable
        $('body').on('click','[data-type=CancelEditable]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            $('[data-type=EditableField][data-id="'+(ID)+'"]').hide();
            $('[data-type=Editable][data-id="'+(ID)+'"]').show();
        });

        $('body').on('click','[data-type=SaveEditable]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            var Fields = $('[data-type=EditableField][data-id="'+(ID)+'"] textarea,[data-type=EditableField][data-id="'+(ID)+'"] select,[data-type=EditableField][data-id="'+(ID)+'"] input');
            var PK = E.attr('data-pk');
            var Post = {Save:(new Date().getTime()),PK:PK};
            Post[CFN] = CFH;
            Fields.each(function(E){
                var E = $(this);
                Post[E.attr('name')] = E.val();
            });
            Fields.prop('readonly',true);
            Request(SaveEditable+'?T'+(new Date().getTime()),function(Data){
                if(Data.Status){
                    SuccessMessage('Saved',Data.Message);
                    $('[data-type=EditableField][data-id="'+(ID)+'"]').hide();
                    $('[data-type=Editable][data-id="'+(ID)+'"]').html(Data.Data[Fields.first().attr('name')]).show();
                }else{
                    ErrorMessage('Data Not Saved',Data.Error);
                }
                Fields.prop('readonly',false);
                CFH = Data.CFH;
            },Post);
        });

        //Update Fetched DropDown
        $('body').on('change', '[data-type=ChangeFetched]', function () {
            var E = $(this);
            var Value = E.val();
            if(Value != '' && Value != null && CFH){
                var Dest = $(E.attr('data-dest'));
                var Post = {
                    Type : E.attr('data-fetch-type'),
                    FetchBy : E.attr('data-fetch-by'),
                    PK : Value
                };
                Post[CFN] = CFH;
                Request(URI('Fetch/Index'), function (Data) {

                    if (Data.Status == 'Success') {
                        var Selected = Dest.attr('data-selected');
                        var HTML = GenerateOptions(Data.Items,Selected);
                        Dest.html(HTML)
                        if(Selected) Dest.change();
                    }
                }, Post);
            }
        });
        $('body [data-type=ChangeFetched]').change();

        //Grid Duplication
        $('body').on('click','[data-type=DuplicateSection]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            DuplicateSection(ID);
        });
        $('body').on('click','[data-type=DuplicateSubSection]',function(){
            var E = $(this);
            var SectionID = E.attr('data-section-id');
            var SectionIndex = E.attr('data-section-index');
            var Section = E.attr('data-section');
            var Title = E.attr('data-title');
            var Name = E.attr('data-name');
            DuplicateSubSection(E,SectionID,SectionIndex,Section,Name,Title);
        });


        $('body').on('click','[data-type=RemoveSection]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            var Index = E.attr('data-index');
            RemoveSection(ID,Index);
        });

        $('body').on('click','[data-type=RemoveSubSection]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            var Index = E.attr('data-index');
            var Section = E.attr('data-section')
            RemoveSubSection(E,Section,ID,Index);
        });

        //Store Duplicate Sections / SubSection
        $('[data-type=SubSection]').each(function(){
            var E = $(this);
            var ID = E.attr('data-id');
            E.find('[data-type=SubSection]').remove();
            var HTML = $('<div>').append(E.clone()).html();
            DuplicatedSubSections[ID] = HTML;
            DuplicatedSubSectionsCounts[ID] = {0:0};
            E.remove();
        });

        $('[data-type=Duplicate]').each(function(){
            var E = $(this);
            var Section = E.attr('data-section');
            var HTML = $('<div>').append(E.clone()).html();
            DuplicatedSections[Section] = HTML;
            DuplicatedCounts[Section] = 0;
            E.remove();
        });
        //Clock
		var Local = function(){
			var RealTime = +Date.now() + TimeDiffrence;
			var CurrentDate = new Date(RealTime);
			var Hours = CurrentDate.getHours();
			var Minutes = CurrentDate.getMinutes();
			var Seconds = CurrentDate.getSeconds();
			var APM = Hours >= 12 ? 'pm' : 'am';
			Hours = Hours % 12;
			Hours = Hours ? Hours : 12;
			Minutes = Minutes < 10 ? '0' + Minutes : Minutes;
			$('[data-type=Clock]').html(Hours + ':' + Minutes + ':'+ Seconds + ' ' + APM.toUpperCase()).show();
		}
		setInterval(Local,1000);
		Local();

        //Icons Append
        $('body [data-type=HasIcon]').each(function(){
            var E = $(this);
            var HTML = E.html();
            HTML = HTML.replace(/Icon\((.*?)\)/,'<i class="$1"></i>');
            E.html(HTML);
        });

        //Confirm Delete
        $('body').on('click','[data-type=Confirm]',function(Event){
            if(!Prevented){
                Event.preventDefault();
            }else{
                return true;
            }

            var E = $(this);
            swal({
                title:'Are you sure?',
                text: 'Are you sure, you want to delete this record?',
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((Confirmed)=>{
                ConfirmAction(Confirmed, E);
            });
        });

        //Confirm Action
        /*$('body').on('click', '[data-confirm=Map]', function (Event) {
            if (!Prevented) {
                Event.preventDefault();
            } else {
                return true;
            }
            var E = $(this);
            swal({
                title: 'Are you sure?',
                text: 'Are you sure you want to do this action? This may impact directly.',
                icon: 'warning',
                buttons: true,
                dangerMode: false,
            }).then((Confirmed) => {
                ConfirmAction(Confirmed,E);
            });
        });*/

        //Realtime Link Action
        $('body').on('click','[data-type=Realtime]',function(){
            var E = $(this);
            var Href = E.attr('href');
            var CallBack = E.attr('data-callback');
            Request(Href,function(Data){
                if(typeof CallBack != 'undefined'){
                    eval(CallBack+'(Data,E)');
                }
            });
            return false;
        });

        //Genrater Fetched Data
        $('body').on('change', '[data-type=ChangeFetched]', function(){
            var E = $(this);
            var Value = E.val();
            var Dest = $(E.attr('data-dest'));
            Request(URI('Fetch/Index'), function(Data){

                if (Data.Status == 'Success') {
                    var HTML = '';
                    var Selected = Dest.attr('data-selected');
                    for (var I in Data.Items) {

                        var Item = Data.Items[I];
                        HTML += '<option value="' + (Item.Value) + '"' + (Selected == Item.Value ? ' selected' : '') + '>' + (Item.Label) + '</option>'
                    }
                    Dest.html(HTML);
                }
            },{
                Type: E.attr('data-fetch-type'),
                FetchBy: E.attr('data-fetch-by'),
                PK: Value
            });
        });

        //Common Page Actions
        $('body li.page-item a').addClass('page-link');
        //Check All
        $('body').on('change','[data-type=CheckAll]',function(){
            var E = $(this);
            var ID = E.attr('data-id');
            var Elems = $('input[type=checkbox][data-id="'+(ID)+'"]');
            Elems.prop('checked',E.prop('checked'));
        });
        PostPlugins();
    });
})(jQuery);

//Extending Jquery
$.validator.addMethod('NIC',function(Value){
    return /^(\d{5}-\d{7}-\d{1})$/.test(Value);
}, 'Please enter a valid NIC number');

$.expr[":"].contains = $.expr.createPseudo(function(arg) {
    return function( elem ) {
        return $(elem).text().toUpperCase().indexOf(arg.toUpperCase()) >= 0;
    };
});
$.validator.addMethod('OnlyName', function (Value) {
    return /^[a-zA-Z ]+$/.test(Value);
}, 'Name Must be characters only');

$.validator.addMethod('InvalidDate', function (Value) {
    var Current = Date.parse(Value);
    var ND = new Date(Date.now());
    var Now = Date.parse((parseInt(ND.getMonth()) + 1) + '/' + ND.getDate() + '/' + ND.getFullYear());
    return Current >= Now;
}, 'Date Must be greater or equal to the current date');

function ConfirmAction(Confirmed, Element) {
    if (Confirmed) {
        Prevented = true;
        if (Element.prop('tagName') == 'A') {
            document.location = Element.attr('href');
            //Element.click();
        }
        Element.click();
        Prevented = false;
    }
}

function PostPlugins(){
    /*$('[data-type=DataTable]').DataTable({
        "aLengthMenu": [
            [, 100, 200, -1],
            [50, 100, 200, "All"]
        ],
        "iDisplayLength": 50,
        "language": {
            search: ""
        },
        "columnDefs": [{ "orderable": false, "targets": 0 }]
    });
    $('[data-type=DataTable]').each(function(){
        var datatable = $(this);
        var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
        search_input.attr('placeholder', 'Search');
        search_input.removeClass('form-control-sm');
        var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
        length_sel.removeClass('form-control-sm');
    });*/
    //Data Filtering
    //Plugins
    $('[data-type=FilterData]').each(function(){
        $(this).SetFilters();
    });
    $('body [data-type=Chozen],body select').each(function(){
        var E = $(this);
        var Multi = typeof E.attr('multiple') !== 'undefined' && E.attr('multiple') !== false;
        if(Multi){
            E.children('option').first().before('<option value="Remove">Remove All</option>');
            E.children('option').first().before('<option value="All">Select All</option>');
        }
        E.select2();
        if(Multi){
            E.on('change',function(){
                if(E.val().indexOf('All') >= 0){
                    E.find('option,optgroup option').prop('selected',true);
                    E.children('option[value=All],option[value=Remove],option[value=""]').prop('selected',false);
                    E.change();
                }
                if(E.val().indexOf('Remove') >= 0){
                    E.find('option,optgroup option').removeAttr('selected').prop('selected',false);
                    E.change();
                }
            });
        }
    });

    $('body [data-item=TimePicker]').each(function(){
        $(this).datetimepicker({
            format: 'HH:mm'
        })
    });
    $('body [data-type=ColorPicker]').asColorPicker();
    $('body [data-type=DatePicker]').datepicker({
        format: 'yyyy-mm-dd',
		autoclose: true,
		todayBtn: true,
		todayHighlight : true,
		todayBtn: 'linked',
    });
    $('body [data-type=DatePickerMY]').datepicker({
		format: 'yyyy-mm',
		startView: 'months',
		minViewMode: 'months'
	}).attr('autocomplete','off');
	$('body [data-type=DatePickerY]').datepicker({
		format: 'yyyy',
		startView: 'years',
		minViewMode: 'years'
	}).attr('autocomplete','off');
    //$('body [data-type=DateRange]').daterangepicker();
    $('[data-type=AutoClose]').datepicker({
        autoclose: true
    });
    $('[data-type=DateRange]').each(function () {
        var E = $(this);
        var Original = E.val();
        E.daterangepicker({
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY-MM-DD'
            }
        }).on('cancel.daterangepicker', function () {
            $(this).val('');
        });
        if (Original == '') {
            E.val('');
        }
    });
    $('[data-type=DateTimeRange]').daterangepicker({
        timePicker: true,
        timePickerIncrement: 30,
        locale: {
            format: 'MM/DD/YYYY h:mm A'
        }
    });
    $('input[type=checkbox]').each(function(){
        var E = $(this);
        var Name = E.attr('name');
        E.next('input[name="'+(Name)+'"]').remove();
    });
    //Form Validation
    if(typeof Rules != 'undefined'){
        $('[data-validate=true]').validate({
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
    }

    //Loaders
    $(document).ajaxStart(function(){
        StartLoader();
    });
    $(document).ajaxStop(function(){
        StopLoader();
    });
}
function DuplicateSection(Section,PreFilled,ReadonlyPrefilled,Index){
    Index = typeof Index == 'undefined' ? DuplicatedCounts[Section] : Index;
    var HTML = DuplicatedSections[Section];
    if(typeof HTML != 'undefined'){
        HTML = HTML.replace(/\{Index\}/g,Index);
        $('[data-type=SectionContainer][data-id="'+(Section)+'"]').append(HTML);
        //Prefilled Data
        if(typeof PreFilled != 'undefined'){
            for(var I in PreFilled){
                var Value = PreFilled[I];
                if(Value != null){
                    var FieldContainer = $('[data-type=Duplicate][data-section="'+(Section)+'"][data-index='+(Index)+']');
                    var Field = FieldContainer.find('[name$="'+(I)+'"]');
                    if(Field.length == 0){
                        var Field = FieldContainer.find('[name*="'+(I)+'"]');
                    }

                    if(Field.attr('type') == 'radio'){
                        Field.each(function(){
                            if($(this).val() == Value){
                                $(this).prop('checked',true);
                            }
                        });
                    }else if(Field.attr('type') == 'checkbox'){
                        if(Value){
                            Field.prop('checked',true);
                        }
                    }else if(Field.attr('type') == 'text'){
                        if(typeof Value == 'object'){
                            Field.each(function(){
                                var CField = $(this);
                                for(var VI in Value){
                                    if(CField.attr('name').indexOf(I+'['+(VI)+']') > 0){
                                        CField.val(Value[VI]);
                                    }
                                }
                            });
                        }else{
                            Field.val(Value).prop('readonly',ReadonlyPrefilled);
                        }
                    }else{
                        Field.val(Value).prop('readonly',ReadonlyPrefilled);
                    }
                }
            }
        }
        PostPlugins();
        DuplicatedCounts[Section]++;
        return Index;
    }
}
function RemoveSection(Section,Index){
    if(typeof Index != 'undefined'){
        $('[data-type=Duplicate][data-section="'+(Section)+'"][data-index="'+(Index)+'"]').remove();
    }else{
        $('[data-type=Duplicate][data-section="'+(Section)+'"]').remove();
    }
}

function RemoveSubSection(E,Section,ID,Index){
    E.parents('[data-type=SubSection][data-section="'+(Section)+'"][data-id="'+(ID)+'"][data-index="'+(Index)+'"]').first().remove();
}
function DuplicateSubSection(Element,ParentSection,ParentIndex,Section,ID,Title,PreFilled,ReadonlyPrefilled){
    var Index = DuplicatedSubSectionsCounts[Section];
    if(typeof Index[ParentIndex] != 'undefined'){
        Index = Index[ParentIndex];
    }else{
        Index = 0;
        DuplicatedSubSectionsCounts[Section][ParentIndex] = 0;
    }
    var HTML = DuplicatedSubSections[Section];
    HTML = HTML.replace(/\{Index\}/g,Index).replace(/\{SectionID\}/g,ParentSection).replace(/\{SectionIndex\}/g,ParentIndex).replace(/\{SectionTitle\}/g,Title).replace(/\{ID\}/g,ID);
    var Container = Element.parents('[data-type=SectionContainer],[data-type=SubSectionContainer]').first().find('[data-type=SubSectionContainer][data-section="'+(Section)+'"][data-section-index="'+(ParentIndex)+'"]');
    Container.append(HTML);

    //Prefilled Data
    if(typeof PreFilled != 'undefined'){
        for(var I in PreFilled){
            var Value = PreFilled[I];
            if(Value != null){
                var FieldContainer = Container.find('[data-type=SubSection][data-id="'+(Section)+'"][data-index='+(Index)+'][data-section="'+(ParentIndex)+'-'+(ParentSection)+'"]');
                var Field = FieldContainer.find('[name$="'+(I)+'"]');
                if(Field.length == 0){
                    var Field = FieldContainer.find('[name*="'+(I)+'"]');
                }

                if(Field.attr('type') == 'radio'){
                    Field.each(function(){
                        if($(this).val() == Value){
                            $(this).prop('checked',true);
                        }
                    });
                }else if(Field.attr('type') == 'checkbox'){
                    if(Value){
                        Field.prop('checked',true);
                    }
                }else if(Field.attr('type') == 'text'){
                    if(typeof Value == 'object'){
                        Field.each(function(){
                            var CField = $(this);
                            for(var VI in Value){
                                if(CField.attr('name').indexOf(I+'['+(VI)+']') > 0){
                                    CField.val(Value[VI]);
                                }
                            }
                        });
                    }else{
                        Field.val(Value).prop('readonly',ReadonlyPrefilled);
                    }
                }else{
                    Field.val(Value).prop('readonly',ReadonlyPrefilled);
                }
            }
        }
    }
    PostPlugins();
    DuplicatedSubSectionsCounts[Section][ParentIndex]++;
    return Index;
}

function URI(Item){
    return BaseLink+Item;
}
function Request(Link,CallBack,Vars){
    if(!Prevented){
        Vars = !Vars ? {} : Vars;
    	var Key = Link;
    	for(var I in Vars){
    		Key += '-'+Vars[I];
    	}
    	if(typeof TempData[Key] == 'undefined'){
    		var PST = $.post(Link,Vars,function(Data){
    			TempData[Key] = Data;
    			CallBack(Data);
                if(typeof Data.Messages != 'undefined' && Data.Messages.length > 0){
                    for(var I in Data.Messages){
                        var Message = Data.Messages[I];
                        switch(Message.Type){
                            case 'Success':
                                SuccessMessage('Success',Message._Message);
                                break;
                            case 'Error':
                                ErrorMessage('Error',Message._Message);
                                break;
                            case 'Warning':
                                WarningMessage('Warning',Message._Message);
                                break;
                            case 'Info':
                                InfoMessage('Info',Message._Message);
                                break;
                        }
                    }
                }
    		},'json');
    		return PST;
    	}else{
    		CallBack(TempData[Key]);
    	}
    }
}

function StartLoader(){
    if(DisplayLoader){
        StopLoader();
        Prevented = true;
        $('body').append('<div class="swal-overlay swal-overlay--show-modal" data-type="Loader"><div class="swal-modal" style="width: 100px;"><div class="flip-square-loader mx-auto"></div></div></div>')
    }
}

function StopLoader(){
    if(DisplayLoader){
        Prevented = false;
        $('[data-type=Loader]').remove();
    }
}

function SuccessMessage(Heading,Message){
    ResetMessage();
    $.toast({
        heading: Heading,
        text: Message,
        showHideTransition: 'slide',
        icon: 'success',
        loaderBg: '#f96868',
        position: 'top-center'
    });
}
function InfoMessage(Heading,Message){
    ResetMessage();
    $.toast({
        heading: Heading,
        text: Message,
        showHideTransition: 'slide',
        icon: 'info',
        loaderBg: '#46c35f',
        position: 'top-center'
    })
}
function WarningMessage(Heading,Message){
    ResetMessage();
    $.toast({
        heading: Warning,
        text: Message,
        showHideTransition: 'slide',
        icon: 'warning',
        loaderBg: '#57c7d4',
        position: 'top-center'
    })
}
function ErrorMessage(Heading,Message){
    ResetMessage();
    $.toast({
        heading: Heading,
        text: typeof Message == 'object' ? Message.join('<br />') : Message,
        showHideTransition: 'center',
        icon: 'error',
        loaderBg: '#f2a654',
        position: 'top-center'
    })
}
function ResetMessage() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $('.jq-toast-wrap').css({
        'top': '',
        'left': '',
        'bottom': '',
        'right': ''
    });
}

function Count(Obj){
    var Count = 0;
    if(Obj != null && typeof Obj != 'undefined'){
        for(var I in Obj)Count++;
    }
    return Count;
}

function GenerateOptions(Items,Selected){
    HTML = '';
    if(Count(Items) > 0){
        for(var I in Items) {
            var Item = Items[I];
            if(typeof Item == 'object'){
                if(typeof Item.Value == 'object'){
                    HTML += '<optgroup label="'+(Item.Label)+'">'+(GenerateOptions(Item.Value))+'</optgroup>';
                }else{
                    HTML += '<option value="'+(Item.Value)+'"'+(Selected == Item.Value ? ' selected' : '')+'>'+(Item.Label)+'</option>';
                }
            }else{
                HTML += '<option value="'+(I)+'"'+(Selected == I ? ' selected' : '')+'>'+(Item)+'</option>';
            }
        }
    }
    return HTML;
}

function ToOptionItems(Items){
    var Out = {};
    if(Count(Items) > 0){
        var Index = 0;
        for(var I in Items){
            var Item = Items[I];
            if(typeof Item == 'object'){
                Out[Index] = {Value:ToOptionItems(Item),Label:I};
            }else{
                Out[Index] = {Value:I,Label:Item};
            }
            Index++;
        }
    }
    return Out;
}

function UpdateRemoteViews(){
    $('body [data-type=RemoteView]').each(function(E){
        var E = $(this);
        var ID = E.attr('data-id');
        var Tag = E.prop('tagName');
        eval('var Value = RemoteData.'+(ID));
        if(typeof Value == 'undefined')Value = '';
        if(Tag == 'INPUT' || Tag == 'SELECT' || Tag == 'TEXTAREA' || Tag == 'BUTTON'){
            E.val(Value);
        }else{
            E.html(Value);
        }
    });
}

function Price(Amount,Currency){

    if(typeof Currencies[Currency] != 'undefined'){
        Currency = Currencies[Currency];
        var LSymbol = '';
        var RSymbol = '';
        if(Currency.Position == 'L') LSymbol = Currency.Symbol + ' ';
        if(Currency.Position == 'R') RSymbol = ' ' + Currency.Symbol;
        return LSymbol + (GetFloat(Amount)) + RSymbol
    }
    return '0';
}

function GetNumber(Value){
    if(Value == '' || Value == null)return 0;
    Value = parseInt(Value);
    return Value == 'NaN' ? 0 : Value;
}

function GetFloat(Value){
    Value = parseFloat(Value);
    if(Value > 0 && Value != 'NaN'){
        return parseFloat(Value.toFixed(2));
    }else{
        return 0.00;
    }
}


function AddTo(Obj,Name,Value){
    Obj[Name] = Value;
}
