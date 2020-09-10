<?php
		$sql = 'SELECT attackcard FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id'];
		$attackcard = get_one($sql);

		if(!$attackcard){ // if they dont have a card
			// give them one
			$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = 1'; // get card data
			$cards = get_one($sql);

			$cards = string_2_array($cards);
			$newcard = array_shift($cards); // shouldnt run out of cards...			
			$cards = array_2_string($cards);
			// get and add to players cards
			$sql = 'SELECT cards FROM game_'.$_SESSION['game_id'].' WHERE id = '.$_SESSION['player_id']; // get card data
			$pcards = get_one($sql);

			if($pcards) // so as to not put a comma if there are no pre-existing cards
				$pcards .= ','.$newcard; // add new card
			else
				$pcards = $newcard;
			$sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '$pcards' WHERE id = ".$_SESSION['player_id'];
			$q = single_qry($sql);
			// update world data minus the handed out card
			$sql = 'UPDATE game_'.$_SESSION['game_id']." SET cards = '$cards' WHERE id = 1";
			$q = single_qry($sql);
					
			$attackcard = 1; // set attackcard to 1 and update
			$sql = 'UPDATE game_'.$_SESSION['game_id']." SET attackcard = $attackcard WHERE id = ".$_SESSION['player_id'];
			$q = single_qry($sql);
		}
?>
