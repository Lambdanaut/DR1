<?php

function trophyName($type){
	switch($type) {
		case "spammaster":
			return "Spam Master";
			break;
		case "greenribbon":
			return "Green Ribbon";
			break;
		case "redribbon":
			return "Red Ribbon";
			break;
		case "blueribbon":
			return "Blue Ribbon";
			break;
		case "moderator":
			return "Moderator";
			break;
		case "modarator":
			return "Modarator";
			break;
		case "coolkid":
			return "Cool Kid";
			break;
		case "incapableofhate":
			return "Incapable of Hate";
			break;
		case "impossibletohate":
			return "Impossible to Hate";
			break;
		case "stalker":
			return "Stalker";
			break;
		default:
			return $type;
	}
}
function trophyInfo($type){
	switch($type) {
		case "spammaster":
			return "For submitting fifteen artworks in one day.";
			break;
		case "greenribbon":
			return "For making 1,000 comments.";
			break;
		case "redribbon":
			return "For making 10,000 comments.";
			break;
		case "blueribbon":
			return "For making 100,000 comments.";
			break;
		case "moderator":
			return "For dedicating personal time as a DrawRawr moderator. ";
			break;
		case "modarator":
			return "For dedicating personal time as a DrawRawr modarator. ";
			break;
		case "coolkid":
			return "For getting 100 watchers. ";
			break;
		case "incapableofhate":
			return "For friending 20 users. ";
			break;
		case "impossibletohate":
			return "For being on 20 user's friend lists. ";
			break;
		case "stalker":
			return "For watching 100 users. ";
			break;
		case "Gay":
			return "This user will always be gay. ";
			break;
		case "onlyhumans bff~!":
			return "The user with this trophy is a gigantic flaming pile of shit. And he's gay. Fuck you Ashes. <3 ";
			break;
		default:
			return "There is no information on this particular trophy";
	}
}

?>
