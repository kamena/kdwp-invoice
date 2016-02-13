<?php
define( 'CONJUNCTION', ' и ' );
define( 'HUNDREDS', 'стотин');
define( 'THOUSANDS', ' хиляди');

function convert_for_tenth( $string, $first_num, $second_num, $dictionary ) {
    $string = $dictionary[$first_num];
    $string .= CONJUNCTION;
    $string .= $dictionary[$second_num];

    return $string;
}

function convert_for_hundred( $first_num, $string, $second_num, $last_num, $dictionary ) {
    if ( $first_num < 400 ) {
       $string = $dictionary[$first_num];
       $string .= " ";
    } else {
        $first_num = $first_num / 100;
        $string = $dictionary[$first_num];
        $string .= HUNDREDS;
    }
    if ( $second_num < 21 || $last_num == 0) {
        if ( $second_num != 0 ) {
            $string .= CONJUNCTION;
            $string .= $dictionary[$second_num];
        }
    } else {
        $second_num = $second_num - $last_num;
        $string .= " ";
        $string .= $dictionary[$second_num];
        $string .= CONJUNCTION;
        $string .= $dictionary[$last_num];
    }

    return $string;
}

function convertor( $number ) {
    $dictionary  = array(
        0       => '',
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
        300     => 'триста',
        1000    => 'хиляда' );

	$string = "";
    switch(true) {
    	case ($number < 21 || $number == 100 || $number == 200 || $number == 300
    		|| $number == 30 || $number == 40 || $number == 50 || $number == 60
    		|| $number == 70 || $number == 80 || $number == 90 || $number == 1000) :
    		$string = $dictionary[$number];
        break;

        case ($number > 20 && $number < 100):
        	$second_num = $number % 10;
        	$first_num = $number - $second_num;
        	$string = convert_for_tenth( $string, $first_num, $second_num, $dictionary );
        break;
        case ($number > 100 && $number < 1000) :
        	$second_num = $number % 100;
        	$first_num = $number - $second_num;
        	$last_num = $second_num % 10;
            $string .= convert_for_hundred( $first_num, $string, $second_num, $last_num, $dictionary );
        break;
        case ( $number > 1000 && $number < 1000000 ) :

            $second_num = $number % 1000;
            $first_num = $number - $second_num;
            $third_num = $second_num % 100;
            $last_num = $third_num % 10;

            if ( $first_num < 2000 ) {
               $string = $dictionary[$first_num];
            } else {
                $first_num = $first_num / 1000;
                if ( $first_num < 21 ) {
                    $string = $dictionary[$first_num];
                    $string .= THOUSANDS; 
                } else if ( $first_num < 101 ) {
                    $second_num_2 = $first_num % 10;
                    $first_num_2 = $first_num - $second_num_2;
                    $string .= convert_for_tenth( $string, $first_num, $second_num, $dictionary );
                    $string .= THOUSANDS . CONJUNCTION; 
                } else if ( $first_num < 1000 ) {
                    $second_num_2 = $first_num % 100;
                    $first_num_2 = $first_num - $second_num_2;
                    $last_num_2 = $second_num_2 % 10;
                    $string .= convert_for_hundred( $first_num_2, $string, $second_num_2, $last_num_2, $dictionary );
                    $string .= THOUSANDS . CONJUNCTION;                  
                }

            }

            $string .= convert_for_hundred( $second_num, $string, $third_num, $last_num, $dictionary );
        break;
    }

    return $string;
}

function num_string_convertor( $number ) {

	if ( !is_numeric( $number ) ) {
        return false;
    }
    
    $string = convertor( $number );
    $string .= " лева";

    if ( $number*100 % 100 != 0 ) {
    	$coin_num = $number*100 % 100;
    	$string .= CONJUNCTION;
    	$string .= convertor( $coin_num );
    	$string .= " стотинки";
    }
    
    return $string;
}
