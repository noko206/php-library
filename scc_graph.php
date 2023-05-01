<?php

/**
 * 有向グラフを強連結成分分解する
 */
class Scc_Graph
{
	/** @var int 頂点数 */
	private $n;
	/** @var array 辺の集合|要素は[from,to] */
	private $edges;

	/**
	 * プロパティの初期化
	 * 計算量：O(1)
	 * @param int $n 頂点数
	 */
	public function __construct(int $n)
	{
		$this->n = $n;
		$this->edges = [];
	}

	/**
	 * 頂点fromから頂点toへ有向辺を追加
	 * 計算量：O(1)
	 * @param int $from 0≦from＜n
	 * @param int $to 0≦to＜n
	 */
	public function add_edge(int $from, int $to): void
	{
		assert(0 <= $from && $from < $this->n);
		assert(0 <= $to && $to < $this->n);
		$this->edges[] = [$from, $to];
	}

	/**
	 * Compressed Sparse Row(CSR)を取得
	 * 計算量：O(n+m) mは辺数
	 * @return array [start,elist]
	 */
	private function csr(): array
	{
		$start = array_fill(0, $this->n + 1, 0);
		$elist = array_fill(0, count($this->edges), 0);
		foreach ($this->edges as list($from, $to)) {
			++$start[$from + 1];
		}
		for ($i = 1; $i <= $this->n; ++$i) {
			$start[$i] += $start[$i - 1];
		}
		$counter = $start;
		foreach ($this->edges as list($from, $to)) {
			$elist[$counter[$from]++] = $to;
		}
		return [$start, $elist];
	}

	/**
	 * Tarjanのアルゴリズムでグラフの強連結成分分解を行う
	 * 計算量：O(n+m) mは辺数
	 * @return array [グラフの強連結成分数, 各頂点が属する強連結成分のID]
	 */
	private function scc_ids(): array
	{
		list($start, $elist) = $this->csr();
		$now_ord = 0;
		$group_num = 0;
		$visited = [];
		$low = array_fill(0, $this->n, 0);
		$ord = array_fill(0, $this->n, -1);
		$ids = array_fill(0, $this->n, 0);
		$dfs = function (int $v) use (&$dfs, $start, $elist, &$now_ord, &$group_num, &$visited, &$low, &$ord, &$ids): void {
			$low[$v] = $now_ord;
			$ord[$v] = $now_ord;
			++$now_ord;
			$visited[] = $v;
			for ($i = $start[$v]; $i < $start[$v + 1]; ++$i) {
				$to = $elist[$i];
				if ($ord[$to] === -1) {
					$dfs($to);
					$low[$v] = min($low[$v], $low[$to]);
				} else {
					$low[$v] = min($low[$v], $ord[$to]);
				}
			}
			if ($low[$v] === $ord[$v]) {
				while (true) {
					$u = array_pop($visited);
					$ord[$u] = $this->n;
					$ids[$u] = $group_num;
					if ($u === $v) break;
				}
				++$group_num;
			}
		};
		for ($i = 0; $i < $this->n; ++$i) {
			if ($ord[$i] === -1) $dfs($i);
		}
		foreach ($ids as &$x) {
			$x = $group_num - 1 - $x;
		}
		return [$group_num, $ids];
	}

	/**
	 * 以下の条件を満たす「頂点のリスト」のリストを取得
	 * 計算量：O(n+m) mは辺数
	 * ・全ての頂点がちょうど1つずつ、どれかのリストに含まれる
	 * ・内側のリストと強連結成分が一対一に対応 (リスト内での頂点の順序は未定義)
	 * ・外側のリストはトポロジカルソートされる
	 * @return array 強連結成分分解された頂点のリスト
	 */
	public function scc(): array
	{
		list($group_num, $ids) = $this->scc_ids();
		$counts = array_fill(0, $group_num, 0);
		foreach ($ids as $x) {
			++$counts[$x];
		}
		$groups = array_fill(0, $group_num, []);
		for ($i = 0; $i < $this->n; ++$i) {
			$groups[$ids[$i]][] = $i;
		}
		return $groups;
	}
}
