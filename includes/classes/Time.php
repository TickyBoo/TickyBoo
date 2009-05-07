<?php

class Time {

	function StringToTime( $datetime = false ) {
		if ( $datetime ) {
			$timecode = strtotime( $datetime );
			//echo date('Y-m-d H:i:s', $DateStr);
			return $timecode;
		} else {
			return time();
		}
	}

	/**************************************************************
	* TITLE : Countdown to any particular date or event           *
	* Credits    : Louai Munajim                                  *
	* Notes      : Original script has been modified to           *
	*            produce difference                               *
	*                in seconds, it's more effective as well!     *
	***************************************************************/
	/* USES
	* / Say you want to count how long a order has remaining but it doenst have a date to compair against
	* / set $timediff the the amount in minutes you want to count too so it has 5 days before its canceled
	* / booked on the 2008-02-20 it will add 5 days to that to equal 2008-02-25. Then it will take todays date from the booked date
	* / 2008-02-25 - today(2008-02-22) = 0000-00-03 days remaing and return it as an int.
	* / Other function is give it a time in the future and will return how many minutes remaining.
	*/
	function countdown( $countdown_time, $timediff = 0 ) {
		if ( $timediff != 0 ) {
			$countdown_diff = $countdown_time + ( 60 * $timediff );
			$today = time();

			$diff = $countdown_diff - $today;
		} else {
			$today = time();
			$diff = $countdown_time - $today;
		}
		if ( $diff < 0 )
			$diff = 0;
		$dl = floor( $diff / 60 / 60 / 24 );
		$hl = floor( ($diff - $dl * 60 * 60 * 24) / 60 / 60 );
		$ml = floor( ($diff - $dl * 60 * 60 * 24 - $hl * 60 * 60) / 60 );
		$sl = floor( ($diff - $dl * 60 * 60 * 24 - $hl * 60 * 60 - $ml * 60) );
		$jml = floor( $diff / 60 );
		// OUTPUT
		//echo "Today's date ".date("F j, Y, g:i:s A")."<br/>";
		//echo "Countdown date ".date("F j, Y, g:i:s A",$countdown_time)."<br/>";
		//echo "\n<br>";
		$return = array( 'days' => $dl, 'hours' => $hl, 'mins' => $ml, 'seconds' => $sl,
			'justmins' => $jml );
		return $return;
	}
}

?>