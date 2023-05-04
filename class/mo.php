<?php

class Mo
{
	private $n;
	private $lr;

	public function __construct(int $n)
	{
		$this->n = $n;
	}

	public function add(int $l, int $r): void
	{
		$this->lr[] = [$l, $r];
	}

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

	public function build(callable $add, callable $erase, callable $out): void
	{
		$this->sub_build($add, $add, $erase, $erase, $out);
	}
}
