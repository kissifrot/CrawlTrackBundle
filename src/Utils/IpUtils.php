<?php
/**
 * IP Utilities. Based on file ip_in_range.php by Paul Gregg <pgregg@pgregg.com>
 * Source website: http://www.pgregg.com/projects/php/ip_in_range/
 */

namespace WebDL\CrawltrackBundle\Utils;

class IpUtils
{
    /**
     * Convert an arbitrary sized decimal (in float format) to an array of 16-bit integers
     */
    private static function float2LargeArray(float $n): array
    {
        $result = [];
        while ($n > 0) {
            $result[] = ($n & 0xffff);
            list($n,) = explode('.', sprintf('%F', $n / 65536.0));
            # note we don't want to use "%0.F" as it will get rounded which is bad.
        }
        return $result;
    }

    /**
     * Convert our large array format back to an arbitrary sized whole number float
     */
    private static function largeArray2Float(array $a): float
    {
        $factor = 1.0;
        $result = 0.0;
        foreach ($a as $element) {
            $result += ($factor * $element);
            $factor <<= 16;
        }
        list($result,) = explode('.', sprintf('%F', $result));

        return $result;
    }

    /**
     * Perform a bitwise AND operation of $a and $b
     * We only need to operate on the minimum number of elements because any extra elements
     * in any array would be negated by the AND with the implied zeros in the smaller array
     */
    private static function largeArrayAND(array $a, array $b): array
    {
        $indexes = min(\count($a), \count($b));
        $c = [];
        for ($i = 0; $i < $indexes; $i++) {
            $c[] = $a[$i] & $b[$i];
        }

        return $c;
    }

    private static function floatAND(float $a, float $b): float
    {
        return
            self::largeArray2Float(
                self::largeArrayAND(self::float2LargeArray($a), self::float2LargeArray($b))
            );
    }

    private static function iP2Float(string $ipString): float
    {
        return (float)sprintf('%u', ip2long($ipString));
    }

    private static function iP6FloatA(string $ipString): array
    {
        $ip6 = explode(':', $ipString);
        # We want the least significant as [0]
        return array_reverse($ip6);
    }

    private static function largeBin2FloatA(string $binaryStr): array
    {
        $bits = str_split($binaryStr, 16);
        $result = [];
        foreach ($bits as $bit) {
            $result[] = (float)sprintf('%u', bindec($bit));
        }

        return array_reverse($result);
    }

    /**
     * Determines whether the given IP is within range or not
     * @param $ip
     * @param $range
     * @return bool
     * @throws \Exception
     */
    public static function iPInRange($ip, $range): bool
    {
        if (false !== strpos($ip, '.')) { // regular IPv4
            if (false !== strpos($range, '/')) {
                // $range is in IP/NETMASK format
                list($range, $netmask) = explode('/', $range, 2);
                if (false !== strpos($netmask, '.')) {
                    // $netmask is a 255.255.0.0 format, or 255.*
                    $nets = explode('.', $netmask);
                    while (\count($nets) < 4) {
                        $nets[] = '*';
                    }
                    $netmask = implode('.', $nets);
                    // by now we have ensured that we have 4 octets of the netmask a.b.c.d
                    $netmask = str_replace('*', '0', $netmask);
                    $netmaskDec = self::iP2Float($netmask);
                } else {
                    // $netmask is a CIDR size block
                    // fix the range argument
                    $x = explode('.', $range);
                    while (\count($x) < 4) {
                        $x[] = '0';
                    }
                    $range = implode('.', $x);

                    $wildcardDec = (2 ** (32 - $netmask)) - 1;
                    // $netmaskDec = ~$wildcardDec;
                    $netmaskDec = (float)((2 ** 32) - (2 ** (32 - $netmask)));
                }

                return (self::floatAND(self::iP2Float($ip), $netmaskDec) === self::floatAND(self::iP2Float($range), $netmaskDec));
            } else {
                // range might be 255.255.*.* or 1.2.3.0-1.2.3.255
                if (false !== strpos($range, '*')) { // a.b.*.* format
                    // Just convert to A-B format by setting * to 0 for A and 255 for B
                    $lower = str_replace('*', '0', $range);
                    $upper = str_replace('*', '255', $range);
                    $range = "$lower-$upper";
                }

                if (false !== strpos($range, '-')) { // A-B format
                    list($lower, $upper) = explode('-', $range, 2);
                    $lower_dec = self::iP2Float($lower);
                    $upper_dec = self::iP2Float($upper);
                    $ip_dec = self::iP2Float($ip);
                    return (($ip_dec >= $lower_dec) && ($ip_dec <= $upper_dec));
                }

                throw new \InvalidArgumentException('Range argument is not in 1.2.3.4/24 or 1.2.3.4/255.255.255.0 format');
            }
        }
        if (false !== strpos($ip, ':')) { // IPv6
            // Parse out the $range
            if (false !== strpos($range, '/')) {
                // $range is in IPv6/NETMASK format
                list($range, $netBits) = explode('/', $range, 2);
                $netmaskBinstr = str_pad('', $netBits, '1') . str_pad('', 128 - $netBits, '0');
            }
            if (preg_match('/::$/', $range)) {
                $range = preg_replace('/::$/', '', $range);
                $x = explode(':', $range);
                while (\count($x) < 8) {
                    $x[] = '0';
                }
                $range = implode(':', $x);
            }
            return (
                self::largeArrayAND(self::iP6FloatA($ip), self::largeBin2FloatA($netmaskBinstr))
                === self::largeArrayAND(self::iP6FloatA($range), self::largeBin2FloatA($netmaskBinstr))
            );
        }

        return false;
    }
}
