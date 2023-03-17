<?php

// $log = '08-Jun-2012 1:00 AM 4ABCDEFGHI
// 09-Jun-2012 1:00 AM 1ABCDEFGHI
// 09-Jun-2012 9:23 AM 3ABCDEFGHI
// 10-Jun-2012 1:00 AM 2ABCDEFGHI
// 10-Jun-2012 2:03 AM 2ABCDEFGHI
// 10-Jun-2012 1:00 AM 1ABCDEFGHI
// 10-Jun-2012 7:23 AM 3ABCDEFGHI
// 10-Jun-2012 9:23 AM 3ABCDEFGHI
// 11-Jun-2012 1:00 AM 1ABCDEFGHI
// 11-Jun-2012 2:12 AM 2ABCDEFGHI
// 11-Jun-2012 8:23 AM 3ABCDEFGHI
// 12-Jun-2012 10:21 PM 1ABCDEFGHI';

$log = file_get_contents('log.txt');

function find_consecutive_visitors($log) {

    $customer_dict = array();
    $consecutive_visitors = array();
    $lines = preg_split('/\r\n|\r|\n/', $log);
    
    foreach ($lines as $line) {

        $fields = preg_split('/\s+/', trim($line));

        $timestamp = date('Y-m-d',strtotime($fields[0] . ' ' . $fields[1]));
      
        $customer_id = $fields[3];

        if (!isset($customer_dict[$customer_id])) {
            $customer_dict[$customer_id] = array();
        }
        array_push($customer_dict[$customer_id], $timestamp);
    }

    foreach ($customer_dict as $customer_id => $visit_times) {
        sort($visit_times);
        $visit_times = array_values(array_unique($visit_times));

        for ($i = 0; $i < count($visit_times) - 2; $i++) {

        	$earlier = new DateTime($visit_times[$i+1]);
			$later = new DateTime($visit_times[$i]);


            $diff1 = $earlier->diff($later);


            $earlier1 = new DateTime($visit_times[$i+2]);
			$later1 = new DateTime($visit_times[$i+1]);

            $diff2 = $earlier1->diff($later1);
            
            if ($diff1->days == 1 && $diff2->days == 1) {
                array_push($consecutive_visitors, $customer_id);
                break;
            }
        }
    }

    return array_unique($consecutive_visitors);
}

$response = find_consecutive_visitors($log);

if(!empty($response)){
    echo "<pre>";
    print_r($response);
}else{
    echo "No consecutive visitor found!";
}
