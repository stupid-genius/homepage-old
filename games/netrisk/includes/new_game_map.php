<?php
//echo var_dump(player_state());
?>

<script language="JavaScript">
<? if (player_state() == 'Attacking'){ ?>
    function selectme(index,army){
    		document.statusaction.fromstate.options[document.statusaction.fromstate.selectedIndex].selected = false;
    		document.statusaction.fromstate.options[index].selected = true;
// added JD	
		army--;
		document.statusaction.armies.options[army].selected = true;
    }
    function selectthem(index){
    		document.statusaction.tostate.options[document.statusaction.tostate.selectedIndex].selected = false;
    		document.statusaction.tostate.options[index].selected = true;
    }
<? } else if (player_state() == 'Fortifying'){ ?>
    var i=0;
    function selectme(index){
    	//if(document.statusaction.fromstates.selectedIndex == 0){// unselected
    	if(i == 0){
    		document.statusaction.fromstate.options[document.statusaction.fromstate.selectedIndex].selected = false;
    		document.statusaction.fromstate.options[index].selected = true;
    		i = 1;
    	} else {
    		document.statusaction.tostate.options[document.statusaction.tostate.selectedIndex].selected = false;
    		document.statusaction.tostate.options[index].selected = true;
    		i=0;
    	}
    }
    function selectthem(index){ }
<? } else { ?>       
    function selectme(index){
    		document.statusaction.fromstate.options[document.statusaction.fromstate.selectedIndex].selected = false;
    		document.statusaction.fromstate.options[index].selected = true;
    	}
    function selectthem(index){ }		
<? } ?>
</script>


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
		$attackable = $country['armies'] - 1;
                if ($attackable > 3) {$attackable = 3;}
		echo '<a href="javascript:select'.$js_select.'('.$selnum.','.$attackable.')" class="'; 
	        $init_place = false;
	        if($gamestate != 'Initial Placement' || $country['player'] == $_SESSION['player_id']) {
		  if ($country['armies'] < 1) {
		    echo 'blank.gif" alt="';  // when a country is conquered wont show anything
		  } else if ($country['armies'] < 5) {
		    echo 'infantry'.$country['armies']; 
	          } else if ($country['armies'] < 10) {
		    echo 'cavalry';
		  } else if ($country['armies'] >= 10) {
		    echo 'cannon'; 
	          } else {
		    echo 'ERROR NO IMAGE';
		  }
	        } else {
		  echo 'infantry1'; $init_place = true; 
		}
                echo '" >';
		if ($gamestate == 'Initial Placement' && $country['player']!= $_SESSION['player_id']){
		  echo '<p>1</p>';
		} else {
		  echo '<p>'.$country['armies'].'</p>';		  
		}
                echo '</a>';
            echo "</li>\n";
            $country_number++;
		}
	?>
</ul>
