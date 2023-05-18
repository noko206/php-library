<?php

/**
 * Mo's Algorithm
 * 区間[0,n)に対するq個の区間[l,r)に関するクエリをO(n√q)で処理できる
 */
class Mo
{
	/** @var int 区間の長さ */
	private $n;
	/** @var array クエリの区間[l,r)の配列 */
	private $lr;

	/**
	 * プロパティの初期化
	 * 計算量：O(1)
	 * @param int $n 区間の長さ
	 */
	public function __construct(int $n)
	{
		$this->n = $n;
		$this->lr = [];
	}

	/**
	 * クエリの追加｜[l,r) 0-indexed
	 * 計算量：O(1)
	 * @param int $l
	 * @param int $r
	 * @return void
	 */
	public function add(int $l, int $r): void
	{
		$this->lr[] = [$l, $r];
	}

	/**
	 * ビルド
	 * 計算量：O(n√q)
	 * @param callable $add_left 左の区間を伸ばしたときの処理
	 * @param callable $add_right 右の区間を伸ばしたときの処理
	 * @param callable $erase_left 左の区間を縮めたときの処理
	 * @param callable $erase_right 右の区間を縮めたときの処理
	 * @param callable $out クエリ[l,r)の計算結果を出力する処理
	 * @return void
	 */
	private function sub_build(callable $add_left, callable $add_right, callable $erase_left, callable $erase_right, callable $out): void
	{
		$q = count($this->lr);
		$bs = intdiv($this->n, min($this->n, (int)sqrt($q)));
		$ord = range(0, $q - 1);
		usort($ord, function(int $a, int $b) use($bs): bool {
			list($la, $ra) = $this->lr[$a];
			list($lb, $rb) = $this->lr[$b];
			$ablock = intdiv($la, $bs);
			$bblock = intdiv($lb, $bs);
			if ($ablock !== $bblock) return $ablock < $bblock;
			return ($ablock & 1) ? $ra > $rb : $ra < $rb;
		});
		$l = 0;
		$r = 0;
		foreach ($ord as $idx) {
			list($left, $right) = $this->lr[$idx];
			while ($l > $left) $add_left(--$l);
			while ($r < $right) $add_right($r++);
			while ($l < $left) $erase_left($l++);
			while ($r > $right) $erase_right(--$r);
			$out($idx);
		}
	}

	/**
	 * ビルド
	 * 計算量：O(n√q)
	 * @param callable $add 区間を伸ばしたときの処理
	 * @param callable $erase 区間を縮めたときの処理
	 * @param callable $out クエリ[l,r)の計算結果を出力する処理
	 * @return void
	 */
	public function build(callable $add, callable $erase, callable $out): void
	{
		$this->sub_build($add, $add, $erase, $erase, $out);
	}
}
