<?php

class Euclid
{
	/**
	 * 最大公約数を取得
	 * 計算量：O(log(min(a,b)))
	 * @param int $a
	 * @param int $b
	 * @return int 最大公約数
	 */
	public static function gcd(int $a, int $b): int
	{
		return ($a % $b) ? gcd($b, $a % $b) : $b;
	}

	/**
	 * 最小公倍数を取得
	 * 計算量：O(log(min(a,b)))
	 * @param int $a
	 * @param int $b
	 * @return int 最小公倍数
	 */
	public static function lcm($a, $b): int
	{
		return intdiv($a, gcd($a, $b)) * $b;
	}
}
