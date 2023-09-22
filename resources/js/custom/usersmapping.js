$(function(){
    $('[data-type=TreeView]').treeview({
        expandIcon: 'fa fa-plus',
        collapseIcon: 'fa fa-minus',
        enableLinks: true,
        showTags: true,
        data: MappedData
    });
    /*//If Profile Selected
    if(ProfileSelected){
        FetchProfiles('FetchUnMapped',ProfileID,function(Data){
            '<div class="card-body pt-2 pb-2 pl-0 pr-0" data-type="FilterRow">
                                                            <div class="media">
                                                                <a href="@Helper.ActionURI(string.Format("MapProfile?MappedWith={0}&Mapped={1}",Profile.ID,Item.ID))" data-type="ConfirmMap"><div class="badge badge-pill badge-primary ml-1 mr-3"><i class="mdi mdi-arrow-left"></i> <i class="mdi mdi-arrow-left"></i></div></a>
                                                                <div class="media-body">
                                                                    <h6 class="mb-1 small text-white font-weight-bold" data-type="FilterItem">@Item.Profile.FirstName @Item.Profile.LastName</h6>
                                                                    <p class="mb-0 text-muted" data-type="FilterItem">
                                                                        @Item.PortalID
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>'
        });

        FetchProfiles('FetchMapped',ProfileID,function(Data){
            '<div class="card-body pt-2 pb-2 pl-0 pr-0" data-type="FilterRow">
                                                            <div class="media">
                                                                <div class="media-body">
                                                                    <h6 class="mb-1 small text-white font-weight-bold" data-type="FilterItem">@Item.Profile.FirstName @Item.Profile.LastName</h6>
                                                                    <p class="mb-0 text-light" data-type="FilterItem">
                                                                        @Item.PortalID  @@ @(Helper.DateFormat(CurrentMapping.Where(CM=>CM.SlaveID == Item.ID).FirstOrDefault().StartDate))
                                                                    </p>
                                                                </div>
                                                                <a href="@Helper.ActionURI(string.Format("UnMap?ProfileID={1}",Item.ID))" data-type="ConfirmMap"><div class="badge badge-pill badge-dark mr-1"><i class="mdi mdi-arrow-right"></i> <i class="mdi mdi-arrow-right"></i></div></a>
                                                            </div>
                                                        </div>'
        });
    }*/
});

function UpdateMapping(Data,Element){
    var ID = Data.Item.SlaveID;

    //If Mapped
    if(Data.Action == 'Mapped'){
        $('[data-type=UnMappedProfiles] [data-type=FilterRow][data-id="'+(ID)+'"]').remove();
        $('[data-type=MappedProfiles]').append('<div class="card-body pt-2 pb-2 pl-0 pr-0" data-type="FilterRow" data-id="'+(ID)+'"><div class="media"><div class="media-body"><h6 class="mb-1 small text-white font-weight-bold" data-type="FilterItem">'+(Data.Item.Name)+'</h6><p class="mb-0 text-light" data-type="FilterItem">'+(Data.Item.PortalID)+'  @ '+(Data.Item.StartDate)+'</p></div><a href="'+(Data.Item.Link)+'" data-confim="Map" data-type="Realtime" data-callback="UpdateMapping"><div class="badge badge-pill badge-dark mr-1"><i class="mdi mdi-arrow-right"></i> <i class="mdi mdi-arrow-right"></i></div></a></div></div>');
    }
    //If Unmapped
    if(Data.Action == 'UnMapped'){
        $('[data-type=MappedProfiles] [data-type=FilterRow][data-id="'+(ID)+'"]').remove();
        $('[data-type=UnMappedProfiles]').children().first().after('<div class="card-body pt-2 pb-2 pl-0 pr-0" data-type="FilterRow" data-id="'+(ID)+'"><div class="media"><a href="'+(Data.Item.Link)+'" data-confim="Map" data-type="Realtime" data-callback="UpdateMapping"><div class="badge badge-pill badge-primary ml-1 mr-3"><i class="mdi mdi-arrow-left"></i> <i class="mdi mdi-arrow-left"></i></div></a><div class="media-body"><h6 class="mb-1 small text-white font-weight-bold" data-type="FilterItem">'+(Data.Item.Name)+'</h6><p class="mb-0 text-muted" data-type="FilterItem">'+(Data.Item.PortalID)+'</p></div></div></div>');
    }
}

/*function FetchProfiles(Action,Master,CallBack){

    Request(FetchFrom,function(Data){
        if(Data.Status == "Success"){
            CallBack(Data);
        }
    },{
        FetchAction:Action,
        MasterID:Master,
        Page:1
    });
}*/
