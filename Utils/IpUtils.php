<?php
/**
 * IP Utilities. Based on file ip_in_range.php by Paul Gregg <pgregg@pgregg.com>
 * Source website: http://www.pgregg.com/projects/php/ip_in_range/
 */

namespace WebDL\CrawltrackBundle\Utils;

class IpUtils
{
    /**
     * Convert an arbitrary sized decimal (in float format) to and array of 16-bit integers
     *
     * @param $n
     * @return array
     */
    private static function float2LargeArray($n) {
        $result = array();
        while ($n > 0) {
            array_push($result, ($n & 0xffff));
            list($n, $dummy) = explode('.', sprintf("%F", $n/65536.0));
            # note we don't want to use "%0.F" as it will get rounded which is bad.
        }
        return $result;
    }

    /**
     * Convert our largearray format back to an arbitrary sized whole number float
     *
     * @param $a
     * @return float
     */
    private static function largeArray2Float($a) {
        $factor = 1.0;
        $result = 0.0;
        foreach ($a as $element) {
            $result += ($factor * $element);
            $factor = $factor << 16;
        }
        list($result, $dummy) = explode('.', sprintf("%F", $result));
        return $result;
    }


    /**
     * Perform a bitwise AND operation of $a and $b
     * We only need to operate on the minimum number of elements because any extra elements
     * in any array would be negated by the AND with the implied zeros in the smaller array
     *
     * @param $a
     * @param $b
     * @return array
     */
    private static function largeArrayAND($a, $b) {
        $indexes = min(count($a), count($b));
        $c = array();
        for ($i=0; $i<$indexes; $i++) {
            array_push($c, $a[$i] & $b[$i]);
        }
        return $c;
    }

    private static function largeArrayOR($a, $b) {
        $indexes = max(count($a), count($b));
        $c = array();
        for ($i=0; $i<$indexes; $i++) {
            if (!isset($a[$i])) $a[$i] = 0;
            if (!isset($b[$i])) $b[$i] = 0;
            array_push($c, $a[$i] | $b[$i]);
        }
        return $c;
    }

    private static function floatAND($a, $b) {
        return
            self::largeArray2Float(
                self::largeArrayAND( self::Float2LargeArray($a), self::Float2LargeArray($b) )
            );
    }

    /**
     * @param $ipstring
     * @return float
     */
    private static function iP2Float($ipstring) {
        $num = (float)sprintf("%u",ip2long($ipstring));
        return $num;
    }

    private static function iP6FloatA($ipstring) {
        $ip6 = explode(':', $ipstring);
        $num = array_reverse($ip6); # We want the least significant as [0]
        return $num;
    }

    private static function largeBin2FloatA($binarystr) {
        $bits = str_split($binarystr, 16);
        $result = array();
        foreach ($bits as $bit) {
            array_push($result, (float)sprintf("%u", bindec($bit)));
        }
        $result = array_reverse($result);
        return $result;
    }

    /**
     * Determines whether the given IP is within range or not
     * @param $ip
     * @param $range
     * @return bool
     * @throws \Exception
     */
    public static function iPInRange($ip, $range) {
        if (strpos($ip, '.') !== false) { // regular IPv4
            if (strpos($range, '/') !== false) {
                // $range is in IP/NETMASK format
                list($range, $netmask) = explode('/', $range, 2);
                if (strpos($netmask, '.') !== false) {
                    // $netmask is a 255.255.0.0 format, or 255.*
                    $nets = explode('.', $netmask);
                    while(count($nets) < 4) $nets[] = '*';
                    $netmask = implode('.', $nets);
                    // by now we have ensured that we have 4 octets of the netmask a.b.c.d
                    $netmask = str_replace('*', '0', $netmask);
                    $netmaskDec = self::iP2Float($netmask);
                } else {
                    // $netmask is a CIDR size block
                    // fix the range argument
                    $x = explode('.', $range);
                    while(count($x)<4) $x[] = '0';
                    $range = implode('.', $x);

                    # Strategy 2 - Use math to create it
                    $wildcardDec = pow(2, (32-$netmask)) - 1;
                    $netmaskDec = ~ $wildcardDec;
                    $netmaskDec = (float)(pow(2,32) - pow(2, (32-$netmask)));

                }

                return ( self::floatAND(self::iP2Float($ip), $netmaskDec) == self::floatAND(self::iP2Float($range), $netmaskDec) );
            } else {
                // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
                if (strpos($range, '*') !==false) { // a.b.*.* format
                    // Just convert to A-B format by setting * to 0 for A and 255 for B
                    $lower = str_replace('*', '0', $range);
                    $upper = str_replace('*', '255', $range);
                    $range = "$lower-$upper";
                }

                if (strpos($range, '-')!==false) { // A-B format
                    list($lower, $upper) = explode('-', $range, 2);
                    $lower_dec = self::iP2Float($lower);
                    $upper_dec = self::iP2Float($upper);
                    $ip_dec = self::iP2Float($ip);
                    return ( ($ip_dec>=$lower_dec) && ($ip_dec<=$upper_dec) );
                }

                throw new \Exception('Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format');
            }
        }
        if (strpos($ip, ':') !== false) { // IPv6
            // Parse out the $range
            if (strpos($range, '/') !== false) {
                // $range is in IPv6/NETMASK format
                list($range, $netbits) = explode('/', $range, 2);
                $netmaskBinstr = str_pad('', $netbits, '1') . str_pad('', 128-$netbits, '0');
            }
            if (preg_match('/::$/', $range)) {
                $range = preg_replace('/::$/', '', $range);
                $x = explode(':', $range);
                while(count($x) < 8) $x[] = '0';
                $range = implode(':', $x);
            }
            return ( self::largeArrayAND(self::iP6FloatA($ip), self::largeBin2FloatA($netmaskBinstr)) == self::largeArrayAND(self::iP6FloatA($range), self::largeBin2FloatA($netmaskBinstr)) );
        }

        return false;
    }
}
