<?php
/**
 *
 */
class bingo {
	/**
	 * Contains the template for the row-container. usualy I would leave this outside of the class for the designers
	 */
	var $rowTpl = <<<EOL
<div class="rowContainer" id="row[row]">
	<--break-->
	<div class="numberContainer col[col]" id="nr[nr]">[nr]</div>
	<--break-->
</div>
EOL;
	/**
	 * Contains the template for the card-container. usualy I would leave this outside of the class for the designers
	 */
	var $cardTpl = <<<EOL
<div class="cardContainer">
	[rows]
</div>
<br clear="all" />
<div id="chozenNumbers"></div>
<h1 id="bingoTitle">Bingo!</h1>
EOL;

	var $dynamiJsTpl = '<script type="text/javascript">{0}</script>';

	function __construct() {

	}

	/**
	 * Basic debug function to return the array readable....
	 * @author P.Welling
	 */
	function pre($arr) {
		$return = '<pre>' . print_r($arr, true) . '</pre>';
		return $return;
	}

	/**
	 * Generates the card and returns the output
	 * @author P.Welling
	 * Uses:
	 * - bingo::generateRowNumbers()
	 */
	function generateCard() {
		$rowNumbers = array();
		$colls = array();
		$rows = '';
		for ($i = 1, $il = 7; $i < $il; $i++) {
			$tmp = explode('<--break-->', $this -> rowTpl);
			$header = $tmp[0];
			$content = $tmp[1];
			$footer = $tmp[2];
			$header = str_replace('[row]', $i, $header);
			$rows .= $header;
			$nrs = $this -> generateRowNumbers($i);
			$rowNumbers[] = $nrs;

			foreach ($nrs AS $key => $nr) {
				$nrBlock = str_replace('[nr]', $nr, $content);
				$nrBlock = str_replace('[col]', $key, $nrBlock);
				if (!isset($colls[$key])) {
					$colls[$key] = array();
				}
				$colls[$key][] = $nr;
				$rows .= $nrBlock;
			}
			$rows .= $footer;
		}
		$return = str_replace('[rows]', $rows, $this -> cardTpl);
		
		$tmp = '';
		foreach ($rowNumbers AS $key => $nrs) {
			$tmp .= 'rows[' . $key . '] = [' . implode(',', $nrs) . '];' . PHP_EOL;
			$tmp .= 'rowsFound[' . $key . '] = 0;' . PHP_EOL;
		}
		foreach ($colls AS $key => $nrs) {
			$tmp .= 'colls[' . $key . '] = [' . implode(',', $nrs) . '];' . PHP_EOL;
			$tmp .= 'collsFound[' . $key . '] = 0;' . PHP_EOL;
		}
		$return .= str_replace('{0}',$tmp,$this -> dynamiJsTpl);
		return $return;
	}

	/**
	 * Returns nrs for the designated row of the card
	 */
	function generateRowNumbers($row) {
		$min = $row * 10;
		$max = $min + 9;
		$nrs = range($min, $max);
		shuffle($nrs);
		$return = array_slice($nrs, 0, 6);
		sort($return);
		return $return;
	}

}

?>