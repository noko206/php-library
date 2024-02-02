<?php

/**
 * 座標圧縮
 *
 * @param list<int> &$a 座標圧縮したい配列
 * @return list<int> 復元用配列
 */
function compress(array &$a): array
{
	$a = array_unique($a);
	sort($a);
	return array_flip($a);
}
