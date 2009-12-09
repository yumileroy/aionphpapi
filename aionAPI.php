<?php
/**
 * Aion website parser.
 *
 * Get all the user info from the Aion user pages.
 * Server list is accurate as of November 10, 2009.
 *
 * @copyright  Copyright (c) 2009 Blake Harley
 * @license    MIT-style license
 * @version    1.0.7
 * @link       http://www.blakeharley.com/project/aionapi
 **/

class aionProfile
{
	const naURL = "http://na.aiononline.com/characters/";
	const euURL = "http://uk.aiononline.com/characters/";
	private $na_servers = array( "Ariel",
							"Azphel",
							"Fregion",
							"Israphel",
							"Kaisinel",
							"Lumiel",
							"Marchutan",
							"Meslamtaeda",
							"Nezakan",
							"Siel",
							"Triniel",
							"Vaizel",
							"Yustiel",
							"Zikel" );
	private $eu_servers = array( "Arbulo",
							"Balder",
							"Castor",
							"Deltras",
							"Gorgos",
							"Kahrun",
							"Kalil",
							"Kromede",
							"Lephar",
							"Nerthus",
							"Perento",
							"Spatalos",
							"Suthran",
							"Telemechus",
							"Thor",
							"Urtem",
							"Vidar",
							"Votan" );

	private $loc;
	private $raw;
	
	public function __construct( $user, $server, $region = "na" )
	{
		// Check to see if server is valid
		if ( !$this->validateServer( $server, $region ) )
		{
			echo "Invalid server!";
			return false;
		}
	
		if ( strcmp( $region, "na" ) == 0 )
			$this->raw = file_get_contents( self::naURL . $server . "/" . $user );
		else if ( strcmp( $region, "eu" ) == 0 )
			$this->raw = file_get_contents( self::euURL . $server . "/" . $user );
		
		$this->loc = $region;
		
		$this->raw = str_replace( array("\t","\n","\r"), array("","",""), $this->raw );
	}
	
	// =========================================================
	//  This function mines the data out of the HTML page
	// =========================================================
	
	public function userData( $print = false )
	{
		preg_match( '/<span class="name"><span>Lv.<\/span> <em>([a-zA-Z0-9:\/_.?-]{1,})<\/em> ([a-zA-Z0-9:\/_.?-]{1,})<\/span>/si', $this->raw, $data1 );
		
		preg_match('/<\/span><p class="info"><span>([a-zA-Z0-9:\/_.?-]{1,})<\/span> \| <span>([a-zA-Z0-9:\/_.?-]{1,})<\/span> \| <span>([a-zA-Z0-9:\/_.?-]{1,})<\/span><\/p>/si', $this->raw, $data2 );
		
		preg_match('/<p class="photo"><img name="sig_image" id="sig_image" src="([a-zA-Z0-9:\/_.?-]{1,})" alt=""/si', $this->raw, $data3 );
		
		preg_match('/<dl class="info"><dt>Current Abyss Status<\/dt><dd><span>(.*)<\/span> \| <span class="point">([a-zA-Z0-9:\/_.?-]{1,})<\/span> \| <span>(.*)<\/span><\/dd><dt>Accumulated Abyss Status<\/dt><dd class="total"><span>Top Rank: <strong>(.*)<\/strong><\/span><span>Total Kills: <strong>([a-zA-Z0-9:\/_.?-]{1,})<\/strong><\/span><\/dd><\/dl>/si', $this->raw, $data4 );
		
		preg_match('/<p class="legion"><a href="(.*)">([a-zA-Z0-9:\/_ .?-]{1,})<\/a><\/p><\/dd><\/dl>/si', $this->raw, $data5 );
		
		if ( strcmp( $this->loc, "na" ) == 0 )
			preg_match('/<strong class="title"><a href="#" onclick=\'goBookUrl\("http:\/\/powerwiki.na.aiononline.com\/aion\/(.*)"\); return false;\'>(.*)<\/a><\/strong>/si', $this->raw, $data6 );
		else
			preg_match('/<strong class="title"><a href="#" onclick=\'goBookUrl\("http:\/\/powerwiki.uk.aiononline.com\/aion\/(.*)"\); return false;\'>(.*)<\/a><\/strong>/si', $this->raw, $data6 );
			
		
		$results = array(
						"name"			=> $data1[2],	// Player's name
						"level"			=> $data1[1],	// Player's level
						"title"			=> $data6[2],	// Player's title
						"class"			=> $data2[3],	// Player's class
						"race"			=> $data2[2],	// Elyos or Asmodian
						"server"		=> $data2[1],	// Player's server
						"image"			=> $data3[1],	// Player's avatar
						"abyss_rank"	=> $data4[1],	// Player's current abyss rank
						"abyss_points"	=> $data4[2],	// Player's current abyss points
						"rank"			=> $data4[3],	// ???
						"total_rank"	=> $data4[4],	// Player's highest abyss rank
						"total_kills"	=> $data4[5],	// Player's kills
						"clan"			=> $data5[2],	// Player's clan
						"clan_url"		=> $data5[1]	// Link to player's clan
						);
		
		if ( $print )
			print_r( $results );
		
		return $results;
	}
	
	// =========================================================
	//  Other functions
	// =========================================================
	
	public function printRaw( )
	{
		echo $this->raw;
	}
	
	private function validateServer( $server, $region )
	{
		$result = false;
		
		if ( strcmp( $region, "na" ) == 0 )
			for( $i = 0; $i < sizeof( $this->na_servers ); $i++ )
				if ( strcmp( $server, $this->na_servers[$i] ) == 0 )
					$result = true;
		if ( strcmp( $region, "eu" ) == 0 )
			for( $i = 0; $i < sizeof( $this->eu_servers ); $i++ )
				if ( strcmp( $server, $this->eu_servers[$i] ) == 0 )
					$result = true;
		
		return $result;
	}
}