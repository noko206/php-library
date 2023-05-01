<?php

/**
 * ランレングス圧縮と復元を行うクラス
 */
class Run_Length
{
	/**
	 * 圧縮
	 * @param string $s 圧縮したい文字列
	 * @return array 要素が[文字列, 連続した現れる回数]となる配列
	 */
	public static function encode(string $s): array
	{
		list($ans, $i, $j, $n) = [[], 0, 0, strlen($s)];
		while ($i < $n) {
			while ($s[$i] === $s[$j] && $j < $n) {
				++$j;
			}
			$ans[] = [$s[$i], $j - $i];
			$i = $j;
		}
		return $ans;
	}

	/**
	 * 復元
	 * @param array $a 要素が[文字列, 連続した現れる回数]となる配列
	 * @return string 復元された文字列
	 */
	public static function decode(array $a): string
	{
		$ans = '';
		foreach ($a as list($s, $cnt)) {
			$ans .= str_repeat($s, $cnt);
		}
		return $ans;
	}
}
