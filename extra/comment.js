function getComments (range,ammount,slide,older){
	if (typeof range   == "undefined") {var range = "";}
	if (typeof ammount == "undefined") {var ammount = "";}
	$(".moreRepliesDialog").dialog("close"); //Close all reply comments when refreshing comments
	$.ajax({
		type: "POST",
		url: "/comments.php",
		data: "type=" + commentType + "&location=" + pageLoc + "&loggedIn=" + loggedIn + "&cookiename=" + cookiename + "&range=" + range + "&ammount=" + ammount,
		beforeSend: function() {
			if (slide == true) {
				if (older != true) {
					$("#loadComments").hide('slide', {direction: 'right'}, "slow");
				} else {
					$("#loadComments").hide('slide', {direction: 'left'}, "slow");
				}
			} else {
				$("#loadComments").fadeOut(100);
			}
		},
		success: function(reply) {
			document.getElementById("loadComments").innerHTML = reply;
			if (slide == true) {
				if (older != true) {
					$("#loadComments").show('slide', {direction: 'left'}, "slow");
				} else {
					$("#loadComments").show('slide', {direction: 'right'}, "slow");
				}
			} else {
				$("#loadComments").fadeIn(1000);
			}
		},
		error: function(){
			$('#loadComments').val("Comment loading failed. Try refreshing the page! ");
		}
	});


}

function getReplies(id){
	var replybox = "#replies" + id;
	$.ajax({
		type: "POST",
		url: "/comments.php",
		data: "id=" + id + "&loggedIn=" + loggedIn + "&cookiename=" + cookiename + "&getReplies=True",
		beforeSend: function() {
			document.getElementById("replyButton" + id).onclick = function () {
				$(replybox).slideUp(400, function () {
					$("#replies" + id).html("");
				});
				document.getElementById("replyButton" + id).onclick = function () {getReplies(id);};
			};
		},
		success: function(reply) {
			$(replybox).html(reply);
			$(replybox).hide();
			$(replybox).slideDown(500);
		},
		error: function(){
			$(replybox).val("Comment loading failed. Try refreshing the page! ");
		}
	});


}

function getUpdate(id){
	var replybox = "#loadComments" + id;
	$.ajax({
		type: "POST",
		url: "/comments.php",
		data: "id=" + id,
		beforeSend: function() {
			$(replybox).fadeOut("fast");
		},
		success: function(reply) {
			$(replybox).fadeIn("fast");
			$(replybox).html(reply);
		},
		error: function(){
			$(replybox).val("Comment loading failed. Try refreshing the page! ");
		}
	});


}

function postComment (id){
	//Check if id isn't given. If it is, use default bottom page comment box. 
	if (typeof id == "undefined") {
		var id = "0";
		var box = $('.commentArea').val();
		var box = encodeURIComponent(box);
	} else {
		var box = $("#replies" + id + " .commentArea").val();
		var box = encodeURIComponent(box);
	}
	if ($("#updateBool").val() == "true") {
		pageLoc = $("#pageLoc" + id).val();
		commentType = $("#commentType" + id).val();
	}
	if (box == "" || box == " ") {
		alert("Comment must not be empty! ");
	} else {
		$("#replies" + id + " .submitCommentButton").unbind('click') //Turn off the click to prevent double comments
		closeComment();
		$.ajax({
			type: "POST",
			url: "/postComment.php",
			data: "user=" + cookiename + "&pass=" + cookiepass + "&text=" + box + "&type=" + commentType + "&location=" + pageLoc + "&id=" + id,
			success: function(reply) {
				//If Updates comment, delete that update. Else, refresh updates. 
				if ($("#updateBool").val() == "true") {
					deleteUpdate($("#updateID" + id).val(),"comment");
				} else {
					getComments(range,ammount);
				}
			},
			error: function(){
				alert("Posting comment failed! ");
			}
		});
	}
}
function moreReplies(id,originDepth) {
	$("#moreReplies" + id).dialog({
		width: $(window).width() - 75,
		height: $(window).height() - 100
	});
	var replybox = "#moreReplies" + id + " .viewMoreReplies";
	$.ajax({
		type: "POST",
		url: "/comments.php",
		data: "id=" + id + "&loggedIn=" + loggedIn + "&cookiename=" + cookiename + "&originDepth=" + originDepth +  "&getReplies=True",
		success: function(reply) {
			$(replybox).html(reply);
		},
		error: function(){
			$(replybox).val("Reply loading failed. Try refreshing the page! ");
		}
	});


}
function deleteComment (id){
	var conf = confirm("Are you sure you want to delete this comment? ");
	if (conf == true) {
		$.ajax({
			type: "POST",
			url: "/postComment.php",
			data: "user=" + cookiename + "&pass=" + cookiepass + "&delete=True&id=" + id + "&location=" + pageLoc + "&type=" + commentType,
			success: function(reply) {
				getComments(range,ammount);
			},
			error: function(){
				alert("Deleting comment failed! ");
			}
		});
	}
}
function showParent(id) {
	$("#parentView" + id).dialog({ 
		title: 'Parent',
	});
}
function setEmotes() {
	$('.insertable').unbind('click');
	$('.insertable').click(function () {
		var commentArea = $(this).parent().parent().find('.commentArea').val();
		var emote       = $(this).html();
		$(this).parent().parent().find('.commentArea').val(commentArea + emote);
	});
	$('.bbcode').click(function () {
		var commentArea = $(this).parent().parent().find('.commentArea').val();
		var emote       = $(this).find('.code').html();
		$(this).parent().parent().find('.commentArea').val(commentArea + emote);
	});
}
function preview() {
	$('.previewCommentButton').unbind('click');
	$('.previewCommentButton').click(function () {
		var commentArea = $(this).parent().find('.commentArea').val();
		$('#previewCommentDialog').dialog({width:650,height:400,modal:'true' });
		$.ajax({
			type: "POST",
			url: "/extra/BBPreview.php",
			data: "text=" + escape(commentArea),
			beforeSend: function() {
				$("#previewCommentDialog").html("<br><br><center> :: LOADING ::</center>");
			},
			success: function(reply) {
				$("#previewCommentDialog").html(unescape(reply));
			},
		});
	});
}
function openComment (id) {
	if (typeof id == "undefined") {
		//Open regular comment
		$("#leaveComment").fadeOut("fast",function () {
			$("#leaveCommentHTML").fadeIn("slow");
			$("#leaveCommentHTML .commentArea").focus();
			scrollTo("#leaveCommentHTML .commentArea");
			$("#leaveCommentHTML .submitCommentButton").button();
			setEmotes();
			preview();
		});
	} else {
		//Open reply comment
		$("#replies" + id).fadeOut("fast",function () {
			$("#replies" + id).html($($("#leaveCommentHTML")).html());
			$("#replies" + id + " .submitCommentButton").click(function () {postComment(id);});
			$("#replies" + id + " .closeComment").click(function () {closeComment(id);});
			$("#replies" + id).fadeIn("slow");
			$("#replies" + id + " .commentArea").focus();
			scrollTo("#comment" + id);
			$("#replies" + id + " .submitCommentButton").button();
			setEmotes();
			preview();
		});
	}
}
function closeComment (id) {
	//When the X button is pressed, Close either the main comment box, or a specific reply comment box
	if (typeof id == "undefined") {
		$("#leaveCommentHTML").hide();
		$("#leaveComment").fadeIn("slow", function () {
		$("#leaveCommentHTML .commentArea").val("");
		});
	} else {
		$("#replies" + id).hide();
		$("#replies" + id + " .submitCommentButton").unbind('click')
		$("#replies" + id + " .commentArea").val("");
		$("#replies" + id + " .commentArea").remove();
	}
}
function scrollTo(id){
     	$('html,body').animate({scrollTop: $(id).offset().top},'slow');
}
$(document).ready(function(){
	getComments(range,ammount);
	$(".submitCommentButton").click(function () {postComment();});
	$(".closeComment").click(function () {closeComment();});
});
