<?php

function engulfing($open, $high, $low, $close){

	$engulfing = array();

	foreach ($open as $key => $value) {
			
		$engulfing[$key] = false;
		$C_BodyHi[$key] = max($close[$key], $open[$key]);
		$C_BodyLo[$key] = min($close[$key], $open[$key]);
		$C_Body[$key] = $C_BodyHi[$key] - $C_BodyLo[$key];

		if($key>=20){ 

			$avg = trader_ema($C_Body, 14);
			$last_key = array_keys($avg)[count($avg)-1];
			$C_BodyAvg[$key] = $avg[$last_key];
			$C_SmallBody[$key] = ($C_Body[$key] < $C_BodyAvg[$key]) ? true : false ;
			$C_LongBody[$key] = ($C_Body[$key] > $C_BodyAvg[$key]) ? true : false ;
			$C_UpShadow[$key] = $high[$key] - $C_BodyHi[$key];
			$C_DnShadow[$key] = $C_BodyLo[$key] - $low[$key];
			$C_WhiteBody[$key] = ($open[$key] < $close[$key]) ? true : false ;
			$C_BlackBody[$key] = ($open[$key] > $close[$key]) ? true : false ;

			if($key>=21){

				$C_EngulfingBearish = $C_BlackBody[$key] && $C_LongBody[$key] && $C_WhiteBody[$key-1] && $C_SmallBody[$key-1] && $close[$key] <= $open[$key-1] 
				&&  $open[$key] >= $close[$key-1] && ( $close[$key] < $open[$key-1] || $open[$key] > $close[$key-1]) ? true : false ;

				$C_EngulfingBullish = $C_WhiteBody[$key] && $C_LongBody[$key] && $C_BlackBody[$key-1] && $C_SmallBody[$key-1] && $close[$key] >= $open[$key-1] 
				&& $open[$key] <= $close[$key-1] && ( $close[$key] > $open[$key-1] || $open[$key] < $close[$key-1] ) ? true : false ;

				if($C_EngulfingBearish) $engulfing[$key] = 'bearish';
				
				if($C_EngulfingBullish) $engulfing[$key] = 'bullish';
			}
		}
	}

	return $engulfing;
}

