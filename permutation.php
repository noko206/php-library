<?php

class Permutation
{
	/**
	 * 配列aを[l,r)に対する次の順列に更新
	 * 計算量：ならしO(1)
	 * @param mixed[] &$a 次の順列に更新したい配列
	 * @param int $l 区間の左端
	 * @param ?int $r 区間の右端
	 * @return bool true:次の順列が存在する|false:次の順列が存在しない
	 */
	public static function next(array &$a, int $l = 0, ?int $r = null): bool
	{
		$n = count($a);
		$r ??= $n;
		assert(0 <= $l && $l <= $r && $r <= $n);

		for ($i = $r - 2; $i >= $l; --$i) {
			if ($a[$i] >= $a[$i + 1]) continue;
			for ($j = $r - 1; $j >= $i + 1; --$j) {
				if ($a[$i] >= $a[$j]) continue;
				list($a[$i], $a[$j]) = [$a[$j], $a[$i]];
				$p = $i + 1;
				$q = $r - 1;
				while ($p < $q) {
					list($a[$p], $a[$q]) = [$a[$q], $a[$p]];
					++$p;
					--$q;
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * 配列aを[l,r)に対する前の順列に更新
	 * 計算量：ならしO(1)
	 * @param mixed[] &$a 前の順列に更新したい配列
	 * @param int $l 区間の左端
	 * @param ?int $r 区間の右端
	 * @return bool true:前の順列が存在する|false:前の順列が存在しない
	 */
	public static function prev(array &$a, int $l = 0, ?int $r = null): bool
	{
		$n = count($a);
		$r ??= $n;
		assert(0 <= $l && $l <= $r && $r <= $n);

		for ($i = $r - 2; $i >= $l; --$i) {
			if ($a[$i] <= $a[$i + 1]) continue;
			for ($j = $r - 1; $j >= $i + 1; --$j) {
				if ($a[$i] <= $a[$j]) continue;
				list($a[$i], $a[$j]) = [$a[$j], $a[$i]];
				$p = $i + 1;
				$q = $r - 1;
				while ($p < $q) {
					list($a[$p], $a[$q]) = [$a[$q], $a[$p]];
					++$p;
					--$q;
				}
				return true;
			}
		}
		return false;
	}

	/**
	 * [0,1,...,n-1]~[n-1,n-2,...,0]の順列を返すジェネレータを作成
	 * 計算量：ならしO(1)
	 * @param int $n 順列の長さ
	 * @return Generator 順列を返すジェネレータ
	 */
	public static function next_generator(int $n): Generator
	{
		assert(1 <= $n);
		$a = range(0, $n - 1);
		do {
			yield $a;
		} while(self::next($a));
	}

	/**
	 * [n-1,n-2,...,0]~[0,1,...,n-1]の順列を返すジェネレータを作成
	 * 計算量：ならしO(1)
	 * @param int $n 順列の長さ
	 * @return Generator 順列を返すジェネレータ
	 */
	public static function prev_generator(int $n): Generator
	{
		assert(1 <= $n);
		$a = array_reverse(range(0, $n - 1));
		do {
			yield $a;
		} while(self::prev($a));
	}
}
