function intMonth(m){
	if (m == 1) {
		return "January";
	}
	if (m == 2) {
		return "February";
	}
	if (m == 3) {
		return "March";
	}
	if (m == 4) {
		return "April";
	}
	if (m == 5) {
		return "May";
	}
	if (m == 6) {
		return "June";
	}
	if (m == 7) {
		return "July";
	}
	if (m == 8) {
		return "August";
	}
	if (m == 9) {
		return "September";
	}
	if (m == 10) {
		return "October";
	}
	if (m == 11) {
		return "November";
	}
	if (m == 12) {
		return "December";
	}
	else {
		return "Month";
	}
}

function getXMLHTTP () {
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
		return xmlhttp;
	} else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		return xmlhttp;
	}
}

//Login Scripts
function showReset () {
	$("#passReset").slideDown("slow");
	$("#openPassReset").css("font-weight","normal");
	$("#openPassReset").unbind("click");
	$("#openPassReset").click(hideReset);
}
function hideReset () {
	$("#passReset").fadeOut("fast");
	$("#openPassReset").css("font-weight","bold");
	$("#openPassReset").unbind("click");
	$("#openPassReset").click(showReset);
}
function showUpdates () {
	getUpdateCount(cookiename,"inbox");
	getUpdateCount(cookiename,"news");
	getUpdateCount(cookiename,"art");
	getUpdateCount(cookiename,"journal");
	getUpdateCount(cookiename,"comment");
	getUpdateCount(cookiename,"fav");
	getUpdateCount(cookiename,"watch");
	noUpdates(cookiename);

	
	$("#updateNum").show();
}
function hideUpdates () {
	$("#updateNum").hide();
}
function getUpdateCount(user,type) {
	if      (type=="inbox")   {var pType="<b>Inbox: </b>";}
	else if (type=="news")    {var pType="<b>News: </b>";}
	else if (type=="art")     {var pType="<b>Arts: </b>";}
	else if (type=="journal") {var pType="<b>Journals: </b>";}
	else if (type=="comment") {var pType="<b>Comments: </b>";}
	else if (type=="fav")     {var pType="<b>Favorites: </b>";}
	else if (type=="watch")   {var pType="<b>Watches: </b>";}
	else                      {var pType="<b>Other: </b>";}
	$.ajax({
		type: "POST",
		url: "/extra/updates/getUpdateCount.php",
		data: "user=" + user + "&type=" + type,
		success: function(reply) {
			if (reply != "0") {
				$("#u" + type).html(pType + reply + "<br>");
			} else {
				$("#u" + type).html("");
			}
		},
	});
}
function noUpdates(user) {
	$.ajax({
		type: "POST",
		url: "/extra/updates/getUpdateCount.php",
		data: "user=" + user + "&type=all",
		success: function(reply) {
			if (reply == "0") {
				$("#uall").html("<b>No Updates! </b>");
			} else {
				$("#uall").html("");
			}
		},
	});
}
function sendLostPassword(){
	$.ajax({
		type: "POST",
		url: "/sendNewPassword.php",
		data: "email=" + $("#resetEmail").val(),
		beforeSend: function(reply) {
			$("#passResetButon").unbind("click");
			$("#passResetButon").fadeOut("slow");
			$("#resetResponse").html("Sending Email...<br><img src='/images/smallLoading.gif'>");
		},
		success: function(reply) {
			if (reply == "Email Sent! ") {
				$("#resetResponse").html(reply);
			} else {
				$("#resetResponse").html(reply);
				$("#passResetButon").val("Try Again! ");
				$("#passResetButon").click(sendLostPassword);
				$("#passResetButon").fadeIn("slow");
			}
		}
	});
	setTimeout("checkUpdates()",15000); //Check for updates every 15 seconds.
}
