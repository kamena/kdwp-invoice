<?php
function convertor($number, $dictionary, $conjunction) {
	$string = "";
    switch(true) {
    	case ($number < 21 || $number == 100 || $number == 200 || $number == 300
    		|| $number == 30 || $number == 40 || $number == 50 || $number == 60
    		|| $number == 70 || $number == 80 || $number == 90) :
    		$string = $dictionary[$number];
            break;

        case ($number > 20 && $number < 100):
        	$second_num = $number % 10;
        	$first_num = $number - $second_num;
        	$string = $dictionary[$first_num];
        	$string .= $conjunction;
        	$string .= $dictionary[$second_num];
        	break;
        case ($number > 100 && $number < 1000) :
        	$second_num = $number % 100;
        	$first_num = $number - $second_num;
        	$last_num = $second_num % 10;
        	$string = $dictionary[$first_num];
        	if ( $second_num < 21 || $last_num == 0) {
        		$string .= $conjunction;
        		$string .= $dictionary[$second_num];
        	} else {
        		$second_num = $second_num - $last_num;
        		$string .= $dictionary[$second_num];
        		$string .= $conjunction;
        		$string .= $dictionary[$last_num];
        	}
        	break;
    }
    return $string;
}

function num_string_convertor( $number ) {
	$conjunction = ' и ';

	$dictionary  = array(
	1       => 'едно',
	2       => 'две',
	3       => 'три',
	4       => 'четири',
	5       => 'пет',
	6       => 'шест',
	7       => 'седем',
	8       => 'осем',
	9       => 'девет',
	10      => 'двесет',
	11      => 'единадесет',
	12      => 'дванадесет',
	13      => 'тринадесет',
	14      => 'четиринадесет',
	15      => 'петнадесет',
	16      => 'шестнадесет',
	17      => 'седемнад',
	18      => 'осемнадесет',
	19      => 'деветнадесет',
	20      => 'двадесет',
	30      => 'тридесет',
	40      => 'четиридесет',
	50      => 'петдесет',
	60      => 'шестдесет',
	70      => 'седемдесет',
	80      => 'осемдесет',
	90      => 'деветдесет',
	100     => 'сто',
	200     => 'двеста',
	300     => 'трист',
	1000    => 'хиляда',
	1000000 => 'милион' );

	if ( !is_numeric( $number ) ) {
        return false;
    }
    
    $string = convertor($number, $dictionary, $conjunction);
    $string .= " лева";

    if ( $number*100 % 100 != 0 ) {
    	$coin_num = $number*100 % 100;
    	$string .= $conjunction;
    	$string .= convertor($coin_num, $dictionary, $conjunction);
    	$string .= " стотинки";
    }
    
    return $string;
}
