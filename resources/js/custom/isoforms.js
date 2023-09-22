$(function () {

    if (typeof ExistingData != 'undefined' && ExistingData != null){
        var Categories = ExistingData.Categories;
        for (var J in Categories){
            var Category = Categories[J];
            var Index = DuplicateSection('ConfigFormFields',Category);
            for (var K in Category.SaveFields){
                var Field = Category.SaveFields[K];
                DuplicateSubSection($('[data-type=DuplicateSubSection][data-section-index="' + (Index) + '"][data-section-id=ConfigFormFields]'), 'ConfigFormFields', Index, 'CategoryFields', '', '', Field);
            }
        }
    }
    $('body').on('change', 'input', function () {
        $('[data-type=Linked]').each(function () {
            var E = $(this);
            var Src = E.attr('data-src');
            var Index = parseInt(E.attr('data-src-index'));
            if(E.val() == ''){
                E.val($('[name*="['+(Index)+']['+(Src)+']"]').val());
            }
            /*$('body').on('change', '[name*="['+(Index - 1)+']['+(Src)+']"]', function () {

            });*/
        });
    });

    //Source Field Changer
    $('body').on('change','[data-type=SourceField]',function(){
        var E = $(this);
        var Value = E.val();
        var Name = E.attr('data-name');
        var Extra = E.attr('data-extras');
        var Submissions = SubmissionsData[Name];
        var IndexValue = IndexesData[Name][Value];
        if(typeof Submissions[Value] != 'undefined'){
            var SubmissionID = Submissions[Value];
            Request(URI('Fetch/GetSubmissionsMeta'), function(Data){
                if(Data.Status == 'Success'){
                    for(var CategoryName in Data.Multiples){
                        RemoveSection(CategoryName);
                        for(var Index in Data.Multiples[CategoryName]){
                            var Item = Data.Multiples[CategoryName][Index];
                            var ItemData = {};
                            for(var I in Item){
                                ItemData[Item[I].Name] = Item[I].Value;
                            }
                            DuplicateSection(CategoryName,ItemData,true);
                        }
                    }
                    for(var I in Data.Items){
                        var Item  = Data.Items[I];
                        $('[name*="['+(Item.Name)+']"]').val(Item.Value).prop('readonly',true);
                    }
                }
                E.prop('disabled',false);
            },{
                Type : 'ISOSubmissionMeta',
                FetchBy : 'SubmissionID',
                PK : SubmissionID,
                Extras : Extra,
                Index : IndexValue
            });
        }
    });
    $('body').on('click','[type=submit]', function(){
        $('body [data-type=SourceField]').each(function(){
            var E = $(this);
            var Val = E.children('option:selected').text();
            E.append('<option value="'+(Val)+'"></option>')
            E.val(Val);
        });
    });
});
