<?php

/**
 * いもす法
 */
class Imos
{
	/** @var int */
	private $n;

	/** @var list<int> */
	private $data;

	/**
	 * @param int $n
	 */
	public function __construct(int $n)
	{
		$this->n = $n;
		$this->data = array_fill(0, $n + 1, 0);
	}

	/**
	 * [$l, $r)
	 * @param int $l 0-indexed
	 * @param int $r 0-indexed
	 * @param int $x
	 * @return void
	 */
	public function add(int $l, int $r, int $x): void
	{
		assert(0 <= $l && $l <= $r && $r <= $this->n);
		$this->data[$l] += $x;
		$this->data[$r] -= $x;
	}

	/**
	 * @return void
	 */
	public function build(): void
	{
		for ($i = 0; $i < $this->n; ++$i) {
			$this->data[$i + 1] += $this->data[$i];
		}
	}

	/**
	 * @param int $idx 0-indexed
	 * @return int
	 */
	public function get(int $idx): int
	{
		assert(0 <= $idx && $idx < $this->n);
		return $this->data[$idx];
	}
}
