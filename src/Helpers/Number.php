<?php
/**
 * This file is part of the O2System PHP Framework package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author         Steeve Andrian Salim
 * @copyright      Copyright (c) Steeve Andrian Salim
 */
// ------------------------------------------------------------------------

/**
 * Number Helpers
 *
 * @package        O2System
 * @subpackage     helpers
 * @category       Helpers
 * @author         Circle Creative Dev Team
 * @link           http://circle-creative.com/products/o2system-codeigniter/user-guide/helpers/number.html
 */
// ------------------------------------------------------------------------

if ( ! function_exists( 'currency_format' ) ) {
    /**
     * Number Price
     *
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @param   int    $price      Price Num
     * @param   string $currency   Price Currency
     * @param   int    $decimal    Num of decimal
     * @param   bool   $accounting Is Accounting Mode
     *
     * @return  string
     */
    function currency_format( $price, $currency = null, $decimal = 0, $accounting = false )
    {
        $currency = isset( $currency ) && ! empty( $currency ) ? $currency : o2system()->config->units[ 'currency' ];
        $currency = trim( $currency );

        if ( is_bool( $decimal ) ) {
            $accounting = $decimal;
            $decimal = 0;
        }

        if ( $accounting == true ) {
            return '<span data-role="price-currency" data-currency="' . $currency . '" class="pull-left">' . $currency . '</span> <span data-role="price-nominal" data-price="' . (int)$price . '" class="pull-right">' . number_format(
                    (int)$price,
                    (int)$decimal,
                    ',',
                    '.'
                ) . '</span>';
        }

        return $currency . ' ' . number_format( (int)$price, (int)$decimal, ',', '.' );
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'unit_format' ) ) {
    /**
     * Number Unit
     *
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @param   int    $price      Price Num
     * @param   string $currency   Price Currency
     * @param   int    $decimal    Num of decimal
     * @param   bool   $accounting Is Accounting Mode
     *
     * @return  string
     */
    function unit_format( $amount, $unit, $decimal = 0, $accounting = false )
    {
        $unit = trim( $unit );

        if ( is_bool( $decimal ) ) {
            $accounting = $decimal;
            $decimal = 0;
        }

        if ( $accounting == true ) {
            return '<span data-role="number-amount" class="pull-left" data-amount="' . (int)$amount . '">' . number_format(
                    (int)$amount,
                    (int)$decimal,
                    ',',
                    '.'
                ) . '</span> <span data-role="number-unit" class="pull-right">&nbsp;' . $unit . '</span>';
        }

        return number_format( (int)$amount, (int)$decimal, ',', '.' ) . ' ' . $unit;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'is_positive' ) ) {
    /**
     * Is Positive
     *
     * Check if an positive number
     *
     * @param   int $number Number
     *
     * @return  bool
     */
    function is_positive( $number )
    {
        if ( $number > 0 ) {
            return true;
        }

        return false;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'is_negative' ) ) {
    /**
     * Is Negative
     *
     * Check if an negative number
     *
     * @param   int $number Number
     *
     * @return  bool
     */
    function is_negative( $number )
    {
        if ( $number < 0 ) {
            return true;
        }

        return false;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'is_odd' ) ) {
    /**
     * Is Odd
     *
     * Check if an odd number
     *
     * @param   int $number Number
     *
     * @return  bool
     */
    function is_odd( $number )
    {
        if ( $number % 2 == 0 ) {
            return true;
        }

        return false;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'is_even' ) ) {
    /**
     * Is Even
     *
     * Check if an even number
     *
     * @param   int $number Number
     *
     * @return  bool
     */
    function is_even( $number )
    {
        if ( $number % 2 == 0 ) {
            return false;
        }

        return true;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'prices_ranges' ) ) {
    /**
     * Prices Ranges
     *
     * @param   int $start_price Start Price
     * @param   int $end_price   End Price
     * @param   int $multiply    Multiply Step
     *
     * @return  array
     */
    function prices_ranges( $start_price, $end_price, $multiply = 0 )
    {
        $start_price = str_replace( '.', '', $start_price );
        $end_price = str_replace( '.', '', $end_price );
        $multiply = str_replace( '.', '', $multiply );
        $multiplier = $multiply * 20;
        $num_range = $end_price / $start_price;
        $num_step = $multiplier / $start_price / 100;

        $ranges = [];
        foreach ( range( 0, $num_range, $num_step ) as $num_price ) {
            if ( $num_price == 0 ) {
                $ranges[] = $start_price;
            } else {
                $ranges[] = $num_price * $start_price / 2 * 10;
            }
        }

        $prices = [];
        for ( $i = 0; $i < count( $ranges ); $i++ ) {
            if ( $ranges[ $i ] == $end_price ) {
                break;
            } else {
                $prices[ $ranges[ $i ] ] = ( $ranges[ $i + 1 ] == 0 ) ? $ranges[ $i ] * 2 : $ranges[ $i + 1 ];
            }
        }

        return $prices;
    }
}
// ------------------------------------------------------------------------

if ( ! function_exists( 'words_format' ) ) {
    /**
     * Number To Words
     *
     * Convert Number To Words
     *
     * @param   int $number  Number
     * @param   int $decimal Num of decimals
     *
     * @return  string
     */
    function words_format( $number, $decimal = 4 )
    {
        $stext = [
            "Nol",
            "Satu",
            "Dua",
            "Tiga",
            "Empat",
            "Lima",
            "Enam",
            "Tujuh",
            "Delapan",
            "Sembilan",
            "Sepuluh",
            "Sebelas",
        ];
        $say = [
            "Ribu",
            "Juta",
            "Milyar",
            "Triliun",
            "Biliun",
            // remember limitation of float
            "--apaan---"  ///setelah biliun namanya apa?
        ];
        $w = "";
        if ( $number < 0 ) {
            $w = "Minus ";
            //make positive
            $number *= -1;
        }
        $snumber = number_format( $number, $decimal, ",", "." );
        $strnumber = explode( ".", substr( $snumber, 0, strrpos( $snumber, "," ) ) );
        //parse decimalnya
        $decimal = substr( $snumber, strrpos( $snumber, "," ) + 1 );
        $isone = substr( $number, 0, 1 ) == 1;
        if ( count( $strnumber ) == 1 ) {
            $number = $strnumber[ 0 ];
            switch ( strlen( $number ) ) {
                case 1 :
                case 2 :
                    if ( ! isset( $stext[ $strnumber[ 0 ] ] ) ) {
                        if ( $number < 19 ) {
                            $w .= $stext[ substr( $number, 1 ) ] . " Belas";
                        } else {
                            $w .= $stext[ substr( $number, 0, 1 ) ] . " Puluh " . ( intval(
                                    substr( $number, 1 )
                                ) == 0
                                    ? ""
                                    : $stext[ substr(
                                        $number,
                                        1
                                    ) ] );
                        }
                    } else {
                        $w .= $stext[ $strnumber[ 0 ] ];
                    }
                    break;
                case 3 :
                    $w .= ( $isone ? "Seratus" : words_format( substr( $number, 0, 1 ) ) . " Ratus" ) . " " . ( intval(
                            substr(
                                $number,
                                1
                            )
                        ) == 0
                            ? ""
                            : words_format(
                                substr( $number, 1 )
                            ) );
                    break;
                case 4 :
                    $w .= ( $isone ? "Seribu" : words_format( substr( $number, 0, 1 ) ) . " Ribu" ) . " " . ( intval(
                            substr(
                                $number,
                                1
                            )
                        ) == 0
                            ? ""
                            : words_format(
                                substr( $number, 1 )
                            ) );
                    break;
                default :
                    break;
            }
        } else {
            $text = $say[ count( $strnumber ) - 2 ];
            $w = ( $isone && strlen( $strnumber[ 0 ] ) == 1 && count( $strnumber ) <= 3 ? "Se" . strtolower(
                    $text
                ) : words_format( $strnumber[ 0 ] ) . ' ' . $text );
            array_shift( $strnumber );
            $i = count( $strnumber ) - 2;
            foreach ( $strnumber as $k => $v ) {
                if ( intval( $v ) ) {
                    $w .= ' ' . words_format( $v ) . ' ' . ( $i >= 0 ? $say[ $i ] : "" );
                }
                $i--;
            }
        }
        $w = trim( $w );
        if ( $decimal = intval( $decimal ) ) {
            $w .= " Koma " . words_format( $decimal );
        }

        return trim( $w );
    }
}

// ------------------------------------------------------------------------
if ( ! function_exists( 'calculate' ) ) {
    /**
     * Calculate
     *
     * Calculate from string
     *
     * @param   string $formula
     *
     * @return  string
     */
    function calculate( $formula )
    {
        static $function_map = [
            'floor'   => 'floor',
            'ceil'    => 'ceil',
            'round'   => 'round',
            'sin'     => 'sin',
            'cos'     => 'cos',
            'tan'     => 'tan',
            'asin'    => 'asin',
            'acos'    => 'acos',
            'atan'    => 'atan',
            'abs'     => 'abs',
            'log'     => 'log',
            'pi'      => 'pi',
            'exp'     => 'exp',
            'min'     => 'min',
            'max'     => 'max',
            'rand'    => 'rand',
            'fmod'    => 'fmod',
            'sqrt'    => 'sqrt',
            'deg2rad' => 'deg2rad',
            'rad2deg' => 'rad2deg',
        ];

        // Remove any whitespace
        $formula = strtolower( preg_replace( '~\s+~', '', $formula ) );

        // Empty formula
        if ( $formula === '' ) {
            trigger_error( 'Empty formula', E_USER_ERROR );

            return null;
        }

        // Illegal function
        $formula = preg_replace_callback(
            '~\b[a-z]\w*\b~',
            function ( $match ) use ( $function_map ) {
                $function = $match[ 0 ];
                if ( ! isset( $function_map[ $function ] ) ) {
                    trigger_error( "Illegal function '{$match[0]}'", E_USER_ERROR );

                    return '';
                }

                return $function_map[ $function ];
            },
            $formula
        );

        // Invalid function calls
        if ( preg_match( '~[a-z]\w*(?![\(\w])~', $formula, $match ) > 0 ) {
            trigger_error( "Invalid function call '{$match[0]}'", E_USER_ERROR );

            return null;
        }

        // Legal characters
        if ( preg_match( '~[^-+/%*&|<>!=.()0-9a-z,]~', $formula, $match ) > 0 ) {
            trigger_error( "Illegal character '{$match[0]}'", E_USER_ERROR );

            return null;
        }

        return eval( "return({$formula});" );
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists( 'hertz_format' ) ) {
    /**
     * Number To Hertz
     *
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @param   int $num       Number
     * @param   int $precision Num of precision
     *
     * @return  string
     */
    function hertz_format( $num, $precision = 1 )
    {
        if ( $num >= 1000000000000 ) {
            $num = round( $num / 1099511627776, $precision );
            $unit = 'THz';
        } elseif ( $num >= 1000000000 ) {
            $num = round( $num / 1073741824, $precision );
            $unit = 'GHz';
        } elseif ( $num >= 1000000 ) {
            $num = round( $num / 1048576, $precision );
            $unit = 'MHz';
        } elseif ( $num >= 1000 ) {
            $num = round( $num / 1024, $precision );
            $unit = 'KHz';
        } else {
            $unit = 'Hz';

            return number_format( $num ) . ' ' . $unit;
        }

        return number_format( $num, '', $precision ) . ' ' . $unit;
    }
}

// ------------------------------------------------------------------------

if ( ! function_exists( 'roman_format' ) ) {
    /**
     * Number to Roman
     *
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @param   int $num Number
     *
     * @return  string
     */
    function roman_format( $num )
    {
        $romans = [
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        ];

        $return = '';

        while ( $num > 0 ) {
            foreach ( $romans as $rom => $arb ) {
                if ( $num >= $arb ) {
                    $num -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }

        return $return;
    }
}

if ( ! function_exists( 'short_format' ) ) {
    function short_format( $num, $precision = 0, $divisors = null )
    {
        // Setup default $divisors if not provided
        if ( ! isset( $divisors ) ) {
            $divisors = [
                pow( 1000, 0 ) => '', // 1000^0 == 1
                pow( 1000, 1 ) => 'K', // Thousand
                pow( 1000, 2 ) => 'M', // Million
                pow( 1000, 3 ) => 'B', // Billion
                pow( 1000, 4 ) => 'T', // Trillion
                pow( 1000, 5 ) => 'Qa', // Quadrillion
                pow( 1000, 6 ) => 'Qi', // Quintillion
            ];
        }

        // Loop through each $divisor and find the
        // lowest amount that matches
        foreach ( $divisors as $divisor => $shorthand ) {
            if ( $num < ( $divisor * 1000 ) ) {
                // We found a match!
                break;
            }
        }

        // We found our match, or there were no matches.
        // Either way, use the last defined value for $divisor.
        return number_format( $num / $divisor, $precision ) . $shorthand;
    }
}

if ( ! function_exists( 'ordinal_format' ) ) {
    function ordinal_format( $number )
    {
        $ends = [ 'th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th' ];

        if ( ( ( $number % 100 ) >= 11 ) && ( ( $number % 100 ) <= 13 ) ) {
            return $number . 'th';
        } else {
            return $number . $ends[ $number % 10 ];
        }
    }
}

if ( ! function_exists( 'byte_format' ) ) {
    /**
     * Formats a numbers as bytes, based on size, and adds the appropriate suffix
     *
     * @param    mixed    will be cast as int
     * @param    int
     *
     * @return    string
     */
    function byte_format( $num, $precision = 1 )
    {
        language()->loadFile( 'number' );

        if ( $num >= 1000000000000 ) {
            $num = round( $num / 1099511627776, $precision );
            $unit = language()->getLine( 'TERABYTE_ABBR' );
        } elseif ( $num >= 1000000000 ) {
            $num = round( $num / 1073741824, $precision );
            $unit = language()->getLine( 'GIGABYTE_ABBR' );
        } elseif ( $num >= 1000000 ) {
            $num = round( $num / 1048576, $precision );
            $unit = language()->getLine( 'MEGABYTE_ABBR' );
        } elseif ( $num >= 1000 ) {
            $num = round( $num / 1024, $precision );
            $unit = language()->getLine( 'KILOBYTE_ABBR' );
        } else {
            $unit = language()->getLine( 'BYTES' );

            return number_format( $num ) . ' ' . $unit;
        }

        return number_format( $num, $precision ) . ' ' . $unit;
    }
}
