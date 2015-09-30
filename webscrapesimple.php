<?php
    /*
        Discogs Shipping Bundler
        Created by Daniel Biwer
        danbiwer@gmail.com
    */

    // Defining the basic cURL function
    function curl($url) {
        // Assigning cURL options to an array
        $options = Array(
            CURLOPT_RETURNTRANSFER => TRUE,  // Setting cURL's option to return the webpage data
            CURLOPT_FOLLOWLOCATION => TRUE,  // Setting cURL to follow 'location' HTTP headers
            CURLOPT_AUTOREFERER => TRUE, // Automatically set the referer where following 'location' HTTP headers
            CURLOPT_CONNECTTIMEOUT => 120,   // Setting the amount of time (in seconds) before the request times out
            CURLOPT_TIMEOUT => 120,  // Setting the maximum amount of time for cURL to execute queries
            CURLOPT_MAXREDIRS => 10, // Setting the maximum number of redirections to follow
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1a2pre) Gecko/2008073000 Shredder/3.0a2pre ThunderBrowse/3.2.1.8",  // Setting the useragent
            CURLOPT_URL => $url, // Setting cURL's URL option with the $url variable passed into the function
        );
         
        $ch = curl_init();  // Initialising cURL 
        curl_setopt_array($ch, $options);   // Setting cURL's options using the previously assigned array data in $options
        $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
        curl_close($ch);    // Closing cURL 
        return $data;   // Returning the data from the function 
    }


    // Defining the basic scraping function
    function scrape_between($data, $start, $end){
        $data = stristr($data, $start); // Stripping all data from before $start
        $data = substr($data, strlen($start));  // Stripping $start
        $stop = stripos($data, $end);   // Getting the position of the $end of the data to scrape
        $data = substr($data, 0, $stop);    // Stripping all data from after and including the $end of the data to scrape
        return $data;   // Returning the scraped data from the function
    }


    function returnSellers($url){
	    $continue=TRUE;
	   $x = 0;//count
	    
	   
	    while($continue==TRUE){
		    $results_page = curl($url); 
		
		    $results_page = scrape_between($results_page, "<div class=\"hide_mobile community_data_text\">", "<div id=\"site_footer_wrap\">");
		     
		    //$separate_results = explode("<td class=\"seller_info\">", $results_page);
		    $separate_results = explode("<td class=\"seller_info\">", $results_page);
		
		    foreach ($separate_results as $separate_result) {
		        if ($separate_result != "") {
		            $temp = scrape_between($separate_result, "profile\">", "</a>");
		            if($temp){
                        $x++;
		            	$results_urls[] = $temp;
                    }
		        }
		    }
		    
		            // Searching for a 'Next' link. If it exists scrape the url and set it as $url for the next loop of the scraper
	        if (strpos($results_page, "pagination_next")) {
	            $continue = TRUE;
	            $url = scrape_between($results_page, "<span>&hellip;</span>", "pagination_next");
	            $url = scrape_between($results_page, "<span>&hellip;</span>", "class=");
	
	            $url = "http://www.discogs.com" . scrape_between($url, "href=\"", "\"");
	        } else {
	            $continue = FALSE;  // Setting $continue to FALSE if there's no 'Next' link
	        }
	        sleep(rand(3,5));   // Sleep for 3 to 5 seconds. Useful if not using proxies. We don't want to get into trouble.
	    }
        $result = array($results_urls,$x);
	    return $result;
    }


    function createResults($list,$shipping){
        $finalList = array();
        $finalListCount = 0;
        $arraySize = count($list);
        for($i=0;$i < $arraySize;$i++){//traverse urls with master/release ids
            if($list[$i][0]=="m"){//if master id
                $t = "http://www.discogs.com/sell/list?master_id=" . substr($list[$i],1) . "&limit=250";
                if($shipping=="US")
                    $t.="&ships_from=United+States";
                if($shipping=="UK")
                    $t.="&ships_from=United+Kingdom";
            }
            if($list[$i][0]=="r"){//if release id

                $t = "http://www.discogs.com/sell/release/" . substr($list[$i],1) . "?ev=rb&limit=250";
                if($shipping=="US")
                    $t.="&ships_from=United+States";
                if($shipping=="UK")
                    $t.="&ships_from=United+Kingdom";
            }


            $y = returnSellers($t);
            $sellers = $y[0];
            $sellersCount = $y[1];
           
            for($n = 0; $n < $sellersCount; $n++){
                $found = 0;
                for($t = 0; $t < $finalListCount; $t++){
                    if($finalList[$t][0]==$sellers[$n]){
                        if(!in_array($list[$i],$finalList[$t][2])){//check for duplicates
                            $finalList[$t][1]++;
                            $finalList[$t][2][]= $list[$i];//add release id to list
                        }
                        $found = 1;
                        break 1;
                    }
                }
                if(!$found){//if not found, create new entry at end of array
                    $finalList[] = array($sellers[$n],1,array($list[$i]));
                    $finalListCount++;
                }
            }



            
        }
        return $finalList;
    }

    function printArray($x){
        $num = count($x);
        for($i=0; $i < $num; $i++){
            if($x[$i][1]>1){
                print_r($x[$i]);
                echo "\n";
            }
        }
    }

    function drawTable($x){//create HTML table from data

        $message = '<html><head><style>table, th, td {border:1px solid black;border-collapse: collapse;}';
        $message .= 'th, td {padding : 5px;}</style></head>';
        $message .= '<body>';
        $message .= '<table>';
        $message .= '<tr><th>Seller</th><th>Items</th><th>IDs</th></tr>';
        $num = count($x);
        for($i=0;$i<$num;$i++){
            if($x[$i][1]>1){
                
                $message .= '<tr><td>' . $x[$i][0] . '</td>';
                $message .= '<td>' . $x[$i][1] . '</td>';
                $message .= '<td>';
                $message .= implode(", ",$x[$i][2]);
                
                $message .= '</td></tr>';
            }
        }
        $message .= '</table></body></html>';

        return $message;
    }



    $L = $_REQUEST['listing'];
    $email = $_REQUEST['email'];
    $shipping = $_REQUEST['shipping'];



    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($L)) {

    
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        $result = createResults($L,$shipping);
      
        
        $message = drawTable($result);
        
        /*if($message == 0){
            $message = "There were no sellers who carried more than one of these items: ";
            foreach($L as $val){
                $message = $message . "<br>" . $val;
            }
        }*/
        
        mail($email, 'Results', $message, $headers, '-fDiscogsBundler@dbiwer.com');
    }



?>