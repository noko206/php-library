<?php

/**
 * ダブリング
 */
class Doubling
{
	/** @var int */
	const MAX_SIZE_DP = 61;

	/** @var int */
	private $n;

	/** @var list<list<int>> */
	private $dp;

	/**
	 * @param int $n
	 */
	public function __construct(int $n)
	{
		$this->n = $n;
		$this->dp = array_fill(0, self::MAX_SIZE_DP, array_fill(0, $n, 0));
		$this->dp[0] = range(0, $n - 1);
	}

	/**
	 * @param int $v 0-indexed
	 * @param int $u 0-indexed
	 * @return void
	 */
	public function addEdge(int $v, int $u): void
	{
		assert(0 <= $v && $v < $this->n);
		assert(0 <= $u && $u < $this->n);
		$this->dp[0][$v] = $u;
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		for ($i = 0; $i < self::MAX_SIZE_DP - 1; ++$i) {
			for ($j = 0; $j < $this->n; ++$j) {
				$this->dp[$i + 1][$j] = $this->dp[$i][$this->dp[$i][$j]];
			}
		}
	}

	/**
	 * @param int $idx 0-indexed
	 * @param int $k
	 * @return int
	 */
	public function query(int $idx, int $k): int
	{
		assert(0 <= $idx && $idx < $this->n);
		$ans = $idx;
		for ($i = 0; $i < self::MAX_SIZE_DP; ++$i) {
			if ($k & (1 << $i)) {
				$ans = $this->dp[$i][$ans];
			}
		}
		return $ans;
	}
}
