$(function(){

	setInterval('GetStatus()', 2000);
});
function GetStatus() {
    Request(URI('Profiles/Status?T=' + (new Date().getTime())), function (Data) {
        if (Data.Status == 'Test Queued'){
            $('[data-type=TestStart]').show();
		}
		if(Data.Status == 'Test Skipped'){
			document.location = URI('Tests/Finished')
		}
	}, {
		ProfileID: ID
	});
}
