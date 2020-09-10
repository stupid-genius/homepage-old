<ul class="map">
	<?php
    //echo var_dump($_SESSION['STATES']);
    
        $me_num = 0;
        $them_num = 0;
		$countries = $_SESSION['STATES'];

		foreach( $countries as $country ){
			echo '<li id="'.str_replace(' ', '', $country['name']).'" class="'.$_SESSION['PLAYERS'][$country['player']]['color'].'" >';
    			//echo $country['name'];
                if ($country['player'] == $_SESSION['player_id']) {
                    $js_select='me';
                    $selnum = ++$me_num;
                }
                else {
                    $js_select='them';
                    $selnum = ++$them_num;
                }
                echo '<a class="'; 
	                $init_place = false;
	                if($gamestate != 'Initial Placement' || $country['player'] == $_SESSION['player_id']){
	                	if ($country['armies'] < 1) // when a country is conquered wont show anything
	                		echo 'blank.gif" alt="';
	                	else if ($country['armies'] < 5)
	                		echo 'infantry'.$country['armies']; 
	                	else if ($country['armies'] < 10)
	                		echo 'cavalry';
	                	else if ($country['armies'] >= 10)
	                		echo 'cannon';
	                	else
	                		echo 'ERROR NO IMAGE';
	                } else { echo 'infantry1'; $init_place = true; }
                    echo '" >';
                    echo '<p>'.$country['armies'].'</p>';
                echo '</a>';
            echo "</li>\n";
            $country_number++;
		}
	?>
</ul>