<?php

/**
 * 座標圧縮
 *
 * @param list<int> &$a 座標圧縮したい配列(座標圧縮後の配列に上書きされる)
 * @return list<int> 圧縮用配列
 */
function compress(array &$a): array
{
	$a = array_unique($a);
	sort($a);
	return array_flip($a);
}
