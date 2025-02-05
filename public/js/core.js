const URL = 'http://dev.th_hris';

const DS  = '/';

const getURL = function(called_url = null){

	if(called_url != null) {

		return URL+DS+called_url;
	}

	else{
		return URL;
	}

};

function hide_delay(target , duration = 10000)
{
	$(target).delay(duration).hide();
}

function dateDifference(dateNow,clockInDate)
{
	let diffMs = (dateNow - clockInDate); // milliseconds between now & Christmas
	let diffDays = Math.floor(diffMs / 86400000); // days
	let diffHrs = Math.floor((diffMs % 86400000) / 3600000); // hours
	let diffMins = Math.round(((diffMs % 86400000) % 3600000) / 60000); // minutes
	
	return diffHrs + " hours, " + diffMins + " minute ";
}
