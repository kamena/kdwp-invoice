<?php
define( 'CONJUNCTION', __(' и ', 'kdwpinvoice') );
define( 'HUNDREDS', __('стотин', 'kdwpinvoice') );
define( 'THOUSANDS', __(' хиляди', 'kdwpinvoice') );

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
        1       => __('едно', 'kdwpinvoice'),
        2       => __('две', 'kdwpinvoice'),
        3       => __('три', 'kdwpinvoice'),
        4       => __('четири', 'kdwpinvoice'),
        5       => __('пет', 'kdwpinvoice'),
        6       => __('шест', 'kdwpinvoice'),
        7       => __('седем', 'kdwpinvoice'),
        8       => __('осем', 'kdwpinvoice'),
        9       => __('девет', 'kdwpinvoice'),
        10      => __('двесет', 'kdwpinvoice'),
        11      => __('единадесет', 'kdwpinvoice'),
        12      => __('дванадесет', 'kdwpinvoice'),
        13      => __('тринадесет', 'kdwpinvoice'),
        14      => __('четиринадесет', 'kdwpinvoice'),
        15      => __('петнадесет', 'kdwpinvoice'),
        16      => __('шестнадесет', 'kdwpinvoice'),
        17      => __('седемнад', 'kdwpinvoice'),
        18      => __('осемнадесет', 'kdwpinvoice'),
        19      => __('деветнадесет', 'kdwpinvoice'),
        20      => __('двадесет', 'kdwpinvoice'),
        30      => __('тридесет', 'kdwpinvoice'),
        40      => __('четиридесет', 'kdwpinvoice'),
        50      => __('петдесет', 'kdwpinvoice'),
        60      => __('шестдесет', 'kdwpinvoice'),
        70      => __('седемдесет', 'kdwpinvoice'),
        80      => __('осемдесет', 'kdwpinvoice'),
        90      => __('деветдесет', 'kdwpinvoice'),
        100     => __('сто', 'kdwpinvoice'),
        200     => __('двеста', 'kdwpinvoice'),
        300     => __('триста', 'kdwpinvoice'),
        1000    => __('хиляда', 'kdwpinvoice') 
    );

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
            $second_num = $second_num - $third_num;

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

            $string .= " " . convert_for_hundred( $second_num, $string, $third_num, $last_num, $dictionary );
        break;
    }

    return $string;
}

function num_string_convertor( $number ) {

	if ( !is_numeric( $number ) ) {
        return false;
    }
    
    $string = convertor( $number );
    $string .= " " . __("лева", 'kdwpinvoice');

    if ( $number*100 % 100 != 0 ) {
    	$coin_num = $number*100 % 100;
    	$string .= CONJUNCTION;
    	$string .= convertor( $coin_num );
    	$string .= " " . __("стотинки", 'kdwpinvoice');
    }
    
    return $string;
}
