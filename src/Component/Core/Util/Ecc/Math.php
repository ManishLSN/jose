<?php

/*
 * The MIT License (MIT)
 *
 * Copyright (c) 2014-2017 Spomky-Labs
 *
 * This software may be modified and distributed under the terms
 * of the MIT license.  See the LICENSE file for details.
 */

namespace Jose\Component\Core\Util\Ecc;

final class Math
{
    /**
     * @param \GMP $first
     * @param \GMP $other
     *
     * @return int
     */
    public static function cmp(\GMP $first, \GMP $other): int
    {
        return gmp_cmp($first, $other);
    }

    /**
     * @param \GMP $first
     * @param \GMP $other
     *
     * @return bool
     */
    public static function equals(\GMP $first, \GMP $other): bool
    {
        return gmp_cmp($first, $other) === 0;
    }

    /**
     * @param \GMP $number
     * @param \GMP $modulus
     *
     * @return \GMP
     */
    public static function mod(\GMP $number, \GMP $modulus): \GMP
    {
        return gmp_mod($number, $modulus);
    }

    /**
     * @param \GMP $augend
     * @param \GMP $addend
     *
     * @return \GMP
     */
    public static function add(\GMP $augend, \GMP $addend): \GMP
    {
        return gmp_add($augend, $addend);
    }

    /**
     * @param \GMP $minuend
     * @param \GMP $subtrahend
     *
     * @return \GMP
     */
    public static function sub(\GMP $minuend, \GMP $subtrahend): \GMP
    {
        return gmp_sub($minuend, $subtrahend);
    }

    /**
     * @param \GMP $multiplier
     * @param \GMP $multiplicand
     *
     * @return \GMP
     */
    public static function mul(\GMP $multiplier, \GMP $multiplicand): \GMP
    {
        return gmp_mul($multiplier, $multiplicand);
    }

    /**
     * @param \GMP $base
     * @param int  $exponent
     *
     * @return \GMP
     */
    public static function pow(\GMP $base, int $exponent): \GMP
    {
        return gmp_pow($base, $exponent);
    }

    /**
     * @param \GMP $first
     * @param \GMP $other
     *
     * @return \GMP
     */
    public static function bitwiseAnd(\GMP $first, \GMP $other): \GMP
    {
        return gmp_and($first, $other);
    }

    /**
     * @param \GMP $first
     * @param \GMP $other
     *
     * @return \GMP
     */
    public static function bitwiseXor(\GMP $first, \GMP $other): \GMP
    {
        return gmp_xor($first, $other);
    }

    /**
     * @param \GMP $value
     *
     * @return string
     */
    public static function toString(\GMP $value): string
    {
        return gmp_strval($value);
    }

    /**
     * @param \GMP $a
     * @param \GMP $m
     *
     * @return \GMP
     */
    public static function inverseMod(\GMP $a, \GMP $m): \GMP
    {
        return gmp_invert($a, $m);
    }

    /**
     * @param string $number
     * @param int    $from
     * @param int    $to
     *
     * @return string
     */
    public static function baseConvert(string $number, int $from, int $to): string
    {
        return gmp_strval(gmp_init($number, $from), $to);
    }
}