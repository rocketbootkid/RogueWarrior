<?php

function doFight($warriors) {

  $warriors = explode(",", chooseWarrior());
  $warrior_one = $warriors[0];
  $warrior_two = $warriors[1];

  # Get Warrior Details
  $arrWarriorOneStats = getAllWarriorDetails($warrior_one);
  $arrWarriorTwoStats = getAllWarriorDetails($warrior_two);

  # See who gets to attack first (Speed)
  
  # while (both players have positive HP)
  
    # First attacks / Second Defends
  
      # See if Attacker Hits
      
      # If hits, how much damage
      
      # Take damage away from Defender
      
    # Second Attacks / First Defends
    
      # See if Attacker Hits
      
      # If hits, how much damage
      
      # Take damage away from Defender

  # Write fight log to database
    # fight_id
    # winner_id
    # loser_id
    # fight_log (string containing details of the fight as a table)
  
  # Update loser warrior record / set status to dead

  # Handle winner updates
    # If title changes (e.g. 5, 10, 15 wins, etc), also randomly choose attribute to buff

}

function chooseWarriors() {
  # Choose one warrior at random
  $warrior_one = chooseRandomWarrior();

  # Choose second warrior at same rank, or one rank higher or lower.
  $warrior_two = chooseSuitableWarrior($warrior_one);
  
  return $warrior_one  . "," . $warrior_two;

}
function chooseRandomWarrior() {

  # Count the number of non-dead warriors in the database
  
  
  # Select one warrior and extract their warrior_id


  return $randomwarrior;
  
}

function chooseSuitableWarrior($warrior_one_id) {

  # Get rank of Warrior One
  
  # Convert to Rank Number
  
  # Determine adjacent Rank Numbers, and if less than zero, set to zero
  
  # Convert adjacent Rank Numbers back to Ranks
  
  # Search for Warrior;
  #   That's not dead
  #   That is one of the three ranks
  #   That is not Warrior One

  return $warrior_two;

}

function countWarriors() {
	
	$sql = "SELECT count(*) FROM roguewarrior.warrior WHERE warrior_status NOT LIKE 'Dead';";
	
	
	
	
}

function getWarriorAttribute($warrior_id, $attribute) {



}

function getAllWarriorDetails($warrior_id) {

  # Search for warrior

  return $arrDetails;

}

function buildRankArray() {

  # Read ranks.txt into a 2D array

  return $arrRanks;

}




?>
