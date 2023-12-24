<?php

/**
 * ランレングス圧縮の復元
 * 計算量：O(|s|) (|s|は復元後の文字列の長さ)
 * @param array $a 要素が[文字列, 連続して現れる回数]となる配列
 * @return string 復元された文字列
 */
function runLengthDecode(array $a): string
{
	$ans = '';
	foreach ($a as list($s, $cnt)) {
		$ans .= str_repeat($s, $cnt);
	}
	return $ans;
}
