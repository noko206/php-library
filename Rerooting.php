<?php

class Rerooting
{
	/** @var int */
	private $n;

	/** @var list<int> */
	private $dpV;

	/** @var list<list<int>> */
	private $dpE;

	/** @var list<list<int>> */
	private $to;

	/** @var callable(int, int): int */
	private $merge;

	/** @var callable(int): int */
	private $addRoot;

	/** @var callable(): int */
	private $e;

	/**
	 * @param int $n;
	 * @param callable(int, int): int $merge
	 * @param callable(int): int $addRoot
	 * @param callable(): int $e
	 */
	public function __construct(int $n, callable $merge, callable $addRoot, callable $e)
	{
		$this->n = $n;
		$this->to = array_fill(0, $n, []);
		$this->dpE = array_fill(0, $n, []);;
		$this->dpV = array_fill(0, $n, []);
		$this->merge = $merge;
		$this->addRoot = $addRoot;
		$this->e = $e;
	}

	/**
	 * 有向辺$u->$vを張る
	 *
	 * @param int $u 0-indexed
	 * @param int $v 0-indexed
	 * @return void
	 */
	public function addEdge(int $u, int $v): void
	{
		assert(0 <= $u && $u < $this->n);
		assert(0 <= $v && $v < $this->n);
		$this->to[$u][] = $v;
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		$this->dfs();
		$this->dfsAll(($this->e)());
	}

	/**
	 * 頂点のDP値
	 *
	 * @param int $idx 0-indexed
	 * @return int
	 */
	public function getV(int $idx): int
	{
		assert(0 <= $idx && $idx < $this->n);
		return $this->dpV[$idx];
	}

	/**
	 * 頂点から辺で結ばれている各頂点を根とする部分木のDP値
	 *
	 * @param int $idx
	 * @return list<int>
	 */
	public function getE(int $idx): array
	{
		assert(0 <= $idx && $idx < $this->n);
		return $this->dpE[$idx];
	}

	/**
	 * 木DP
	 *
	 * @param int $v
	 * @param int $p
	 * @return int
	 */
	private function dfs(int $v = 0, int $p = -1): int
	{
		$dpX = ($this->e)();
		$deg = count($this->to[$v]);
		$this->dpE[$v] = array_fill(0, $deg, ($this->e)());
		for ($i = 0; $i < $deg; ++$i) {
			$u = $this->to[$v][$i];
			if ($u === $p) {
				continue;
			}
			$this->dpE[$v][$i] = $this->dfs($u, $v);
			$dpX = ($this->merge)($dpX, $this->dpE[$v][$i]);
		}
		return ($this->addRoot)($dpX);
	}

	/**
	 * 残りの部分木に対応するDP値を計算
	 *
	 * @param int $dpP
	 * @param int $v
	 * @param int $p
	 * @return void
	 */
	private function dfsAll(int $dpP, int $v = 0, int $p = -1): void
	{
		$deg = count($this->to[$v]);
		for ($i = 0; $i < $deg; ++$i) {
			if ($this->to[$v][$i] === $p) {
				$this->dpE[$v][$i] = $dpP;
			}
		}
		$dpL = array_fill(0, $deg + 1, ($this->e)());
		$dpR = array_fill(0, $deg + 1, ($this->e)());
		for ($i = 0; $i < $deg; ++$i) {
			$dpL[$i + 1] = ($this->merge)($dpL[$i], $this->dpE[$v][$i]);
		}
		for ($i = $deg - 1; $i >= 0; --$i) {
			$dpR[$i] = ($this->merge)($dpR[$i + 1], $this->dpE[$v][$i]);
		}
		$this->dpV[$v] = ($this->addRoot)($dpL[$deg]);
		for ($i = 0; $i < $deg; ++$i) {
			$u = $this->to[$v][$i];
			if ($u === $p) {
				continue;
			}
			$this->dfsAll(($this->addRoot)(($this->merge)($dpL[$i], $dpR[$i + 1])), $u, $v);
		}
	}
}
