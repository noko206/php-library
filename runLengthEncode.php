<?php

/**
 * ランレングス圧縮
 * 計算量：O(|s|)
 * @param string $s 圧縮したい文字列
 * @return array 要素が[文字列, 連続して現れる回数]となる配列
 */
function runLengthEncode(string $s): array
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