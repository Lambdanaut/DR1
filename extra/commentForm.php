<?php
$commentForm = "
	<form>
	<style type='text/css'>
	.insertable {
		cursor: pointer;
	}
	.bbcode {
		cursor: pointer;
	}
	.code {
		display: none;
	}
	</style>
	<font size = 2 style = 'float:left;margin-left:1%'>
		<b class = 'bbcode'>Bold<span class='code'>[b] [/b]</span></b> 
		<i class = 'bbcode'>Italic<span class='code'>[i] [/i]</span></i> 
		<u class = 'bbcode'>Underline<span class='code'>[u] [/u]</span></u> 
		<s class = 'bbcode'>Strikeout<span class='code'>[s] [/s]</span></s> 
	</font>
	<div class = 'closeComment'><font size = 1 color = 'red' style = 'float:right;margin-right: 2%;border:solid 1px; padding: 1px 2px 0 2px; cursor:pointer;'>
		X
	</font></div><br>
	<textarea class = 'commentArea' rows = '10' cols = '65' tabindex=1></textarea>
	<div class = 'emotes'>
 
 <img src = '/images/emote/yellowstar.png'> <span class = 'insertable'>[yellowstar]</span><br>
 <img src = '/images/emote/blackstar.png'> <span class = 'insertable'>[blackstar]</span><br>

 <img src = '/images/emote/happy.png'> <span class = 'insertable'>[happy]</span><br>
 <img src = '/images/emote/sad.png'> <span class = 'insertable'>[sad]</span><br>
 <img src = '/images/emote/love.gif'> <span class = 'insertable'>[love]</span><br>
 <img src = '/images/emote/hearteyes.png'> <span class = 'insertable'>[wuv]</span><br>
 <img src = '/images/emote/blush.png'> <span class = 'insertable'>[blush]</span><br>
 <img src = '/images/emote/barf.gif'> <span class = 'insertable'>[barf]</span><br>
 <img src = '/images/emote/dead.png'> <span class = 'insertable'>[dead]</span><br>
 <img src = '/images/emote/durrr.png'> <span class = 'insertable'>[durrr]</span><br>
 <img src = '/images/emote/disappoint.png'> <span class = 'insertable'>[disappoint]</span><br>
 <img src = '/images/emote/dotdotdot.png'> <span class = 'insertable'>[dotdotdot]</span><br>
 <img src = '/images/emote/exclamation.png'> <span class = 'insertable'>[exclamation]</span><br>
 <img src = '/images/emote/woah.gif'> <span class = 'insertable'>[woah]</span><br>
 <img src = '/images/emote/wave.gif'> <span class = 'insertable'>[wave]</span><br>
 <img src = '/images/emote/fu.gif'> <span class = 'insertable'>[fu]</span><br>
 <img src = '/images/emote/party.png'> <span class = 'insertable'>[party]</span><br>
 <img src = '/images/emote/question.png'> <span class = 'insertable'>[question]</span><br>
 <img src = '/images/emote/rage.png'> <span class = 'insertable'>[rage]</span><br>
 <img src = '/images/emote/reg.png'> <span class = 'insertable'>[reg]</span><br>
 <img src = '/images/emote/reg2.png'> <span class = 'insertable'>[reg2]</span><br>
 <img src = '/images/emote/skull.png'> <span class = 'insertable'>[skull]</span><br>
 <img src = '/images/emote/classy.png'> <span class = 'insertable'>[classy]</span><br>
 <img src = '/images/emote/sleepy.gif'> <span class = 'insertable'>[sleepy]</span><br>
 <img src = '/images/emote/ugh.png'> <span class = 'insertable'>[ugh]</span><br>
 <img src = '/images/emote/giggle.gif'> <span class = 'insertable'>[giggle]</span><br>
 <img src = '/images/emote/wink.png'> <span class = 'insertable'>[wink]</span><br>
 <img src = '/images/emote/smug.gif'> <span class = 'insertable'>[smug]</span><br>
 <img src = '/images/emote/crying.png'> <span class = 'insertable'>[;_;]</span><br>
 <img src = '/images/emote/devil.png'> <span class = 'insertable'>[devil]</span><br>
 <img src = '/images/emote/angel.png'> <span class = 'insertable'>[angel]</span><br>
 <img src = '/images/emote/dunce.png'> <span class = 'insertable'>[dunce]</span><br>
 <img src = '/images/emote/ninja.gif'> <span class = 'insertable'>[ninja]</span><br>
 <img src = '/images/emote/cool.gif'> <span class = 'insertable'>[cool]</span><br>
 <img src = '/images/emote/deal.gif'> <span class = 'insertable'>[deal]</span><br>
 
 <img src = '/images/emote/whiteheart.png'> <span class = 'insertable'>[whiteheart]</span><br>
 <img src = '/images/emote/pinkheart.png'> <span class = 'insertable'>[pinkheart]</span><br>
 <img src = '/images/emote/redheart.png'> <span class = 'insertable'>[redheart]</span><br>
 <img src = '/images/emote/orangeheart.png'> <span class = 'insertable'>[orangeheart]</span><br>
 <img src = '/images/emote/yellowheart.png'> <span class = 'insertable'>[yellowheart]</span><br>
 <img src = '/images/emote/greenheart.png'> <span class = 'insertable'>[greenheart]</span><br>
 <img src = '/images/emote/lightblueheart.png'> <span class = 'insertable'>[lightblueheart]</span><br>
 <img src = '/images/emote/blueheart.png'> <span class = 'insertable'>[blueheart]</span><br>
 <img src = '/images/emote/purpleheart.png'> <span class = 'insertable'>[purpleheart]</span><br>
 <img src = '/images/emote/blackheart.png'> <span class = 'insertable'>[blackheart]</span><br>
 <img src = '/images/emote/rainbowheart.gif'> <span class = 'insertable'>[rainbowheart]</span><br>
 <img src = '/images/emote/1sttrophy.png'> <span class = 'insertable'>[1sttrophy]</span><br>
 <img src = '/images/emote/2ndtrophy.png'> <span class = 'insertable'>[2ndtrophy]</span><br>
 <img src = '/images/emote/3rdtrophy.png'> <span class = 'insertable'>[3rdtrophy]</span><br>
 <img src = '/images/emote/musicnote.png'> <span class = 'insertable'>[musicnote]</span><br>
 <img src = '/images/emote/dottico.png'> <span class = 'insertable'>[dot]</span><br>
 <img src = '/images/emote/reddot.png'> <span class = 'insertable'>[reddot]</span><br>
 <img src = '/images/emote/bubble.png'> <span class = 'insertable'>[bubble]</span><br>
 <img src = '/images/emote/fish.png'> <span class = 'insertable'>[fish]</span><br>
 <img src = '/images/emote/apple.png'> <span class = 'insertable'>[apple]</span><br>
 <img src = '/images/emote/toast.png'> <span class = 'insertable'>[toast]</span><br>
 <img src = '/images/emote/rainbow.png'> <span class = 'insertable'>[rainbow]</span><br>
 <img src = '/images/emote/yeah.gif'> <span class = 'insertable'>[yeah]</span><br>
 <img src = '/images/emote/yes.gif'> <span class = 'insertable'>[yes]</span><br>
 <img src = '/images/emote/no.gif'> <span class = 'insertable'>[no]</span><br>
 <img src = '/images/emote/pokeball.png'> <span class = 'insertable'>[pokeball]</span><br>
 <img src = '/images/emote/cake.png'> <span class = 'insertable'>[cake]</span><br>
 <img src = '/images/emote/wrench.png'> <span class = 'insertable'>[wrench]</span><br>

 <img src = '/images/emote/halo.png'> <span class = 'insertable'>[halo]</span><br>
 <img src = '/images/emote/crown.png'> <span class = 'insertable'>[crown]</span><br>
 <img src = '/images/emote/partyhat.png'> <span class = 'insertable'>[partyhat]</span><br>
 <img src = '/images/emote/tophat.png'> <span class = 'insertable'>[tophat]</span><br>
 <img src = '/images/emote/horns.png'> <span class = 'insertable'>[horns]</span><br>
 <img src = '/images/emote/duncecap.png'> <span class = 'insertable'>[duncecap]</span><br>
 <img src = '/images/emote/bunnyears.png'> <span class = 'insertable'>[bunnyears]</span><br>
 <img src = '/images/emote/wingl.png'> <span class = 'insertable'>[wingl]</span><br>
 <img src = '/images/emote/wingr.png'> <span class = 'insertable'>[wingr]</span><br>

 <img src = '/images/emote/gun.png'> <span class = 'insertable'>[gun]</span><br>
 <img src = '/images/emote/gunflip.png'> <span class = 'insertable'>[gunflip]</span><br>
 <img src = '/images/emote/gunblue.png'> <span class = 'insertable'>[gunblue]</span><br>
 <img src = '/images/emote/gunbrown.png'> <span class = 'insertable'>[gunbrown]</span><br>
 <img src = '/images/emote/gungray.png'> <span class = 'insertable'>[gungray]</span><br>
 <img src = '/images/emote/gunorange.png'> <span class = 'insertable'>[gunorange]</span><br>
 <img src = '/images/emote/gunpurple.png'> <span class = 'insertable'>[gunpurple]</span><br>
 <img src = '/images/emote/gunred.png'> <span class = 'insertable'>[gunred]</span><br>
 <img src = '/images/emote/gunyellow.png'> <span class = 'insertable'>[gunyellow]</span><br>

 <img src = '/images/emote/onlyhuman.png'> <span class = 'insertable'>[ohuman]</span><br>
	</div>
	<div class='previewCommentButton' style='float:left;text-align:left;margin-left:1%;cursor:pointer;width:170px;'><b>Preview Comment</b></div><br>
	<center><div style='float:center;clear:both;'><input type='button' value='Post Comment' class = 'submitCommentButton' tabindex=2></div></center>
	</form>
	";
?>
