<?php
require_once ("extra/library.php");

require_once ("prehtmlincludes.php");
?>
<html>
<head><title>Donate</title>

<link rel="stylesheet" type="text/css" href="/css/main.css">

</head>
<body>

<div id="container">
	<div id="headerContainer">
		<div id="header">
			<h1>
				<?php require ("header.php") ?>
			</h1>
		</div>
                <div id="login">
			<?php echo($loginPrint); ?>
                </div>
	</div>
	<div id="navigation">
		<ul>
			<?php require ("nav.php"); ?>
		</ul>
	</div>
	<div id="content-container">
		<div id="content" style = "width: 100%; text-align: center;">
			<br>
			<h2><img src = '/images/dottico.png'> <u>Donations!</u> <img src = '/images/dottico.png'></h2>
			<p style = "margin-left: 25%; margin-right: 25%;"><b>
				Donations keep DrawRawr alive. Seriously. So if you enjoy the site and want it to continue existing, donating any spare change you might have is a good idea! 			
			</b></p>
			<p style='font-size:12px;'>(The Trophy System and Ad removal per user isn't finished yet, but the moment they are, you'll get your trophy and ads removed!) </p>
			<div class = "newArt" style = "text-align: center; margin-left: 5%; margin-right: 5%;">
				<b><u>Here's some cool things you can get from donating! It's our way of saying "Thank you". &#60;3 </u></b>
				<div style = "text-align: left;">
					<h3>$1.00</h3>
					<p><img src = '/images/donatortrophy.png'> The "Donator" Trophy! </p>
					<h3>$5.00</h3>
					<p><img src = '/images/supportertrophy.png'> The "DrawRawr Supporter" Trophy, and <font color='red'>NO ADS</font> for <u>one month!</u> </p>
					<h3>$10.00</h3>
					<p><img src = '/images/bestfriendtrophy.png'> The "A DrawRawr's Best Friend" Trophy, <font color='red'>NO ADS</font> for <u>three months,</u> and a DrawRawr button! </p>
					<h3>$20.00</h3>
					<p><img src = '/images/lovertrophy.png'> The "A DrawRawr Lover" Trophy, <font color='red'>NO ADS</font> for <u>six months,</u> and 3 DrawRawr buttons! </p>
					<h3>$50.00</h3>
					<p><img src = '/images/generousgoodtrophy.png'> The "A Very Generous and Good Looking Person" Trophy, <font color='red'>NO ADS</font> for <u>one year,</u> 5 DrawRawr buttons, and a DrawRawr T-SHIRT! </p>
					<h3>$100.00</h3>
					<p><img src = '/images/drlovesmetrophy.png'> <img src = '/images/seniormbtrophy.png'> The "&#60;3 DrawRawr loves me &#60;3" and "MR. MONEYBAGS" Trophies, <font color='red'>NO ADS</font> for <u>LIFE,</u> 8 DrawRawr buttons, a DrawRawr T-SHIRT, and a handwritten letter from the very thankful Admins! &#60;3</p>
				</div>
			</div>

<div class = "newart" style = "float: center; text-align: center; margin-left: 35%; margin-right: 35%; ">
<center>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
<input type="hidden" name="cmd" value="_s-xclick">
<table>
<tr><td><input type="hidden" name="on0" value="DrawRawr Donations">DrawRawr Donations</td></tr><tr><td><select name="os0">
	<option value="$1.00 Donation">$1.00 Donation $1.00</option>
	<option value="$5.00 Donation">$5.00 Donation $5.00</option>
	<option value="$10.00 Donation">$10.00 Donation $10.00</option>
	<option value="$20.00 Donation">$20.00 Donation $20.00</option>
	<option value="$50.00 Donation">$50.00 Donation $50.00</option>
	<option value="$100 Donation">$100 Donation $100.00</option>
</select> </td></tr>
</table>
<input type="hidden" name="currency_code" value="USD">
<input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----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-----END PKCS7-----
">
<input type="image" src="http://i87.photobucket.com/albums/k125/kimisakitsune/donatebutton.png" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/WEBSCR-640-20110401-1/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
</center>
</div>

		</div>
	</div>
	<div id="ads">
		<?php require_once ("ads.php"); ?>
	</div>
	<div id="footer">
		<?php require_once ("footer.php"); ?>
	</div>
</div>

</body>
</html>

