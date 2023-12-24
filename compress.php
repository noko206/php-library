<?php

/**
 * 座標圧縮
 * @param array $a 座標圧縮したい配列
 * @return array [座圧配列、元に戻す配列]
 */
function compress(array $a): array
{
	$decompress = array_unique($a);
	sort($decompress);
	$compress = array_flip($decompress);
	return [$compress, $decompress];
}
