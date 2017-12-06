<?php
  // Some constants
  $MAX_RESULTS = 50;

  // This could all be one string, but might as well split it up
  $api = "https://restcountries.eu/rest/v2/name/";
  $filters = "?fields=name;population;alpha2Code;alpha3Code;flag;region;subregion;languages";
  
  if ($_SERVER["REQUEST_METHOD"] == "POST")
  {
    // Validate the data coming in
    if ( empty($_POST["country"]) )
    {  
      echo "Error reading data";
      return;
    }  
    else
      $input = $_POST["country"];
        
    // Generate the API call with the URL combined with the user input
    $apiCall = $api . $input . $filters;
    
    // Use file_get_contents, thanks to PHP 7, to get the JSON data
    $data = file_get_contents( $apiCall );
    
    // This will store the decoded JSON data into $json
    $json = json_decode( $data, true );
    
    // Now lets loop over the JSON data and store in an associative array
    $arr = array();
    foreach ( $json as $country )
    {
      // Parse out the relevant language info
      $langs = array();
      $languages = $country['languages'];
      foreach ( $languages as $lang )
      {
        array_push( $langs, $lang['name'] );
      }
      $country['languages'] = $langs;
    
      // We're combining name and population for sorting
      $arr[ $country['name'] . $country['population'] ] = $country;
    }
    
    // This function will sort the name/population combo in alphabetical order
    ksort( $arr );
    
    // Now limit the results to the $MAX_RESULTS
    $count = 0;
    $countries = array();
    $regions = array();
    $subregions = array();
    foreach ( $arr as $name => $info )
    {
      array_push( $countries,  $info );
      
      $r = $info['region'];
      if ( empty($regions[ $r ]) )
        $regions[ $r ] = 1;
      else
        $regions[ $r ] = $regions[ $r ] + 1;
      
      $s = $info['subregion'];
      if ( empty($subregions[ $s ]) )
        $subregions[ $s ] = 1;
      else
        $subregions[ $s ] = $subregions[ $s ] + 1;
      
      // Limit the search
      $count = $count + 1;
      if ( $count == $MAX_RESULTS )
        break;
    }
    ksort($regions);
    ksort($subregions);
    
    // Now lets send back the data
    $returnJSON->count = $count;
    $returnJSON->data = $countries;
    $returnJSON->regions = $regions;
    $returnJSON->subregions = $subregions;
    echo json_encode( $returnJSON );
  }
?>
