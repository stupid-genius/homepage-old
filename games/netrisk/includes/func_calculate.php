<?php
// should use a passed ID not the session state player_id
$next_player_countries_array = array();
$countries = $_SESSION['STATES'];

while( $country=current($countries) ){ 
	if($country['player'] == $next_player_id){
		$next_player_countries_array[] = key($countries);
	}
	next($countries);
} unset($countries);
//reset($countries);

$numcountries = count($next_player_countries_array);

if($numcountries < 4){ 
	// they cant have a continent so dont check for one and they must get the maximum min of 3 armies
	$new_armies = 3;
} else {
	$country_bonus = floor($numcountries / 3);
	if($country_bonus < 3) $country_bonus = 3;
	$continent_bonus = 0;
	
	// comparing arrays from db
	$sql = "SELECT * FROM continents";
	$q = mysql_query($sql);
	/*$all_continents = $DB->query($sql);
	if(DB::isError($all_continents))
		die($all_continents->getMessage());*/

//	while($continent_countries = $all_continents->fetchRow(DB_FETCHMODE_ASSOC)){
	while($continent_countries = mysql_fetch_assoc($q)) {
				
		$continent_countries_array = string_2_array($continent_countries['states']); 
		// check this against $next_player_countries_array -- if theres a match add the continents bonus
		// array_diff() returns an array containing all the values of array1  that are not present in any of the other arguments
		$arraydiffs = array_diff($continent_countries_array,$next_player_countries_array);
		if(count($arraydiffs) == 0){ // empty array, so everything in array1 had a match in array2 and the player has the continent
			$continent_bonus += $continent_countries['bonus'];			
		}		
	}
	$new_armies = $continent_bonus + $country_bonus;
}
// ****** Calling page should use $new_armies as the armies variable
?>
