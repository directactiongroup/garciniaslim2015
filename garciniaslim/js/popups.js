
function popUp(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,status=2,menubar=0,resizable=0,width=650,height=800,left = 50,top = 50');");
}
function label(URL) {
	day = new Date();
	id = day.getTime();
	eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,status=2,menubar=0,resizable=0,width=1000,height=458,left = 50,top = 50');");
}
