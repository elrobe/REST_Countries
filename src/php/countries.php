<?php
  /*  
   * File:     countries.php
   * Author:   Eli Berg
   * Purpose:  Provides the processing of a search query for the REST Countries
   *           API. Sanitizes the user input, queries the API, then turns the
   *           response data into a JSON format for the requesting front-end to
   *           display.
   */
  
  // SCRIPT ====================================================================
  
  // Constants
  $WARNING = false;
  $MAX_RESULTS = 50;
  $api = "https://restcountries.eu/rest/v2/name/";
  $filters = "?fields=name;population;alpha2Code;alpha3Code;flag;region;subregion;languages";
    
  // Process the expected incoming POST request containing a country name
  if ( $_SERVER['REQUEST_METHOD'] == "POST" )
  {
    // Validate the data coming in
    if ( empty($_POST['country']) )
    {
      errorMessage("Please enter search criteria.");
      return;
    }
    
    // Generate the API call with the URL combined with the user input
    $input = $_POST['country'];
    $input = filter_var( $input, FILTER_SANITIZE_STRING ); // Sanitize first!
    $apiCall = $api . $input . $filters;
    
    // Use file_get_contents to query the API, but we also need handling
    // in case the function doesn't get any data
    set_error_handler( "warningHandler", E_WARNING ); // redirect a warning
    $response = file_get_contents( $apiCall );
    restore_error_handler();
    
    // If we got a warning, then quit (response set by the warning handler)
    if ( $GLOBALS['WARNING'] )
      return;
    
    // If we get this far, there's some data to process
    processResults( $response );
    return; // Not needed but ensures we don't process anything else
  }
  else // Sanity check
  {
    errorMessage("Invalid request type. Try again.");
    return;
  }
  
  // FUNCTIONS =================================================================
  
  /*
   * Function:  errorMessage
   * Purpose:   Populate the 'error' node of the response with a given message.
   */
  function errorMessage( $message )
  {
    $toReturn = array();
    $toReturn['error'] = $message;
    echo json_encode( $toReturn );
  }
  
  /*
   * Function:  warningHandler
   * Purpose:   Sets an error message in the response and sets a global variable
   *            to signal an early response back to the front-end.
   */
  function warningHandler( $eNo, $eMsg )
  {
    if ( $GLOBALS['WARNING'] ) // Only log one error in case there are several
      return;
    $GLOBALS['WARNING'] = true;
    errorMessage("No results found. Try a different query.");
  }
  
  /*
   * Function:  processResults
   * Purpose:   Process the response from the API call. This assumes the data is
   *            in a JSON format.
   */
  function processResults( $response )
  {
    // This will store the decoded JSON data into $json
    $json = json_decode( $response, true );
    
    // Format the JSON data for output and sort it
    $arr = array();
    formatData( $json, $arr );
    
    // Set up some arrays to store the data we'll return
    $count = 0;
    $countries = array();
    $regions = array();
    $subregions = array();
    // Store the first $MAX_RESULTS number of sorted results into the response
    foreach ( $arr as $name => $info )
    {
      array_push( $countries,  $info );                   // Countries
      
      addRegionCount( $info['region'], $regions );        // Regions
      
      addRegionCount( $info['subregion'], $subregions );  // Subregions
      
      // Limit the search
      $count = $count + 1;
      if ( $count == $GLOBALS['MAX_RESULTS'] )
        break;
    }
    // Sort the regions and subregions alphabetically by key as well
    ksort($regions);
    ksort($subregions);
    
    // Now send back the data to the front-end
    $toReturn = array();
    $toReturn['data'] = $countries;
    $toReturn['regions'] = $regions;
    $toReturn['subregions'] = $subregions;
    echo json_encode( $toReturn );
  }
  
  /*
   * Function:  formatData
   * Purpose:   Formats JSON data from the API in a readable format for the
   *            front-end and sorts the countries by name and population.
   */
  function formatData( &$json, &$outArr )
  {
    // Loop over the country data and store in an associative array
    foreach ( $json as $country )
    {
      // Replace the languages array with an HTML-injected string for display
      $langStr = "";
      $langAry = $country['languages'];
      foreach ( $langAry as $cur )
        $langStr = $langStr . $cur['name'] . "<br>";
      $country['languages'] = $langStr;
      
      // Format the population to be human-readable
      $country['population'] = number_format( $country['population'] );
      
      // Format the flag as an HTML image
      $country['flag'] = "<img src='" . $country['flag'] . "'/>";
      
      // Format the region and subregion if null
      if ( $country['region'] == "" ) $country['region'] = "[None]";
      if ( $country['subregion'] == "" ) $country['subregion'] = "[None]";
      
      // Combine name and population strings for sorting
      $outArr[ $country['name'] . $country['population'] ] = $country;
    }
    ksort( $outArr ); // Sort the data alphabetically by key
  }
  
  /*
   * Function:  addRegionCount
   * Purpose:   Generic function to increment the number of times a region is
   *            found in the API response. Written to also be usable to keep
   *            track of subregions, too.
   */
  function addRegionCount( $val, &$outArr )
  {      
    if ( empty($outArr[ $val ]) )
      $outArr[ $val ] = 1;
    else
      $outArr[ $val ] = $outArr[ $val ] + 1;
  }
?>
