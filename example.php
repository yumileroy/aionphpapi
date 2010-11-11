<?php

require("aionAPI.php");

/* aionProfile( charname, servername, na - default / eu ) */
$user = new aionProfile( "Yumi", "Israphel" ); 

$data = $user->userData();

echo $data['name'] . "<br />";            // Player's name
echo $data['level'] . "<br />";           // Player's level
echo $data['title'] . "<br />";           // Player's title
echo $data['class'] . "<br />";           // Player's class
echo $data['race'] . "<br />";            // Elyos or Asmodian 
echo $data['server'] . "<br />";          // Player's server
echo $data['image'] . "<br />";           // Player's avatar
echo $data['abyss_rank'] . "<br />";      // Player's current abyss rank
echo $data['abyss_points'] . "<br />";    // Player's current abyss points
echo $data['rank'] . "<br />";            // ???
echo $data['total_rank'] . "<br />";      // Player's highest abyss rank
echo $data['total_kills'] . "<br />";     // Player's kill count
echo $data['clan'] . "<br />";            // Player's clan
echo $data['clan_url'] . "<br />";        // Link to player's clan
echo $data['hp'] . "<br />";              // Player's HP
echo $data['mp'] . "<br />";              // Player's MP
?>
