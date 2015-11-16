<?php
/**
 * 
 */
class bingo{
	/**
	 * Contains the CSS. Usualy I would use a css-file and have this included via the css-function below
	 */
	var $css = <<<EOL
<style type="text/css">
	.cardContainer{
		width:312px;
		display:block;
	}
	.rowContainer{
		width:312px;
		display:block;
	}
	.numberContainer{
		width:50px;
		display:block;
		float:left;
		border: 1px solid #000000;
	}
	.numberContainer.drawn,.drawnRow div  {
		background-color:#008000;
	}
	#bingoTitle{
		display:none;
	}
</style>
EOL;
	
	/**
	 * Contains the js needed for the function. Normaly I would use a library like JQuery or Prototype and have a sepperate JS file and have this added via the JS-function below
	 */
	var $js = <<<EOL
<script type="text/javascript">
	var drawn = new Array();
	function drawNumbers(){
		var cont = true;
		var nr = Math.floor(Math.random() * 61) + 10;
		if(typeof drawn[nr] != 'undefined'){
			//do nothing...
		} else {
			var elm = document.getElementById('nr' + nr);
			drawn[nr] = nr;
			if(elm){ //the number is on the card!
				// elm.className = elm.className  + ' drawn';
				colls.forEach(function(co) {
   					 co.forEach(function(it,ind){
   					 	if(it == nr){
   					 		collsFound[ind]++;
   					 	}
   					 });
				});
				rows.forEach(function(ro) {
   					 ro.forEach(function(it,ind){
   					 	if(it == nr){
   					 		rowsFound[ind]++;
   					 	}
   					 });
				});
			}
			var drawnCont = document.getElementById('chozenNumbers');
			var comma = (drawnCont.innerHTML == '') ? '': ',';
			drawnCont.innerHTML = drawnCont.innerHTML + comma + nr;  
		}
		//Now check if there is a bingo. if so, then stop and shout it out!
		rowsFound.forEach(function(it,ind){
			if(it == 6){
				cont = false;
				var elms = document.getElementsByClassName('col' + ind);
				for(var i=0,il=elms.length;i<il;i++){
					elms[i].className = elms[i].className + ' drawn';
				}
			}
		});
		collsFound.forEach(function(it,ind){
			if(it == 6){
				cont = false;
				var elmRow = document.getElementById('row' + (ind+1));
				elmRow.className = elmRow.className + ' drawnRow';
			}
		});
		if(cont === true){
			setTimeout(drawNumbers,200);
		} else {
			document.getElementById('bingoTitle').style.display = 'block';
		}
	}
	drawNumbers();
</script>
EOL;
	
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
<h1 id="bingoTitle">Bingo at Swis!</h1>
EOL;

	var $dynamiJsTpl = <<<EOL
<script type="text/javascript">
var rows = new Array();
var colls = new Array();
var rowsFound = new Array();
var collsFound = new Array();
EOL;

	function __construct(){
		
	}
	
	/**
	 * Basic debug function to return the array readable....
	 * @author P.Welling
	 */
	function pre($arr){
		$return = '<pre>'.print_r($arr,true).'</pre>';
		return $return;
	}
	
	/**
	 * Generates the card and returns the output
	 * @author P.Welling
	 * Uses:
	 * - bingo::generateRowNumbers()
	 */
	function generateCard(){
		$rowNumbers = array();
		$colls = array();
		$rows = '';
		for($i=1,$il=7;$i<$il;$i++){
			$tmp = explode('<--break-->',$this->rowTpl);
			$header = $tmp[0];
			$content = $tmp[1];
			$footer = $tmp[2];
			$header = str_replace('[row]',$i,$header);
			$rows .= $header;
			$nrs = $this->generateRowNumbers($i);
			$rowNumbers[] = $nrs;
			
			foreach($nrs AS $key=>$nr){
				$nrBlock = str_replace('[nr]',$nr,$content);
				$nrBlock = str_replace('[col]',$key,$nrBlock);
				if(!isset($colls[$key])){
					$colls[$key] = array();
				}
				$colls[$key][] = $nr;
				$rows .= $nrBlock;
			}
			$rows .= $footer;
		}
		$return = str_replace('[rows]',$rows,$this->cardTpl);
		$return .= $this->dynamiJsTpl;
		foreach($rowNumbers AS $key=>$nrs){
			$return .= 'rows['.$key.'] = ['.implode(',',$nrs).'];'.PHP_EOL;
			$return .= 'rowsFound['.$key.'] = 0;'.PHP_EOL;
		}
		foreach($colls AS $key=>$nrs){
			$return .= 'colls['.$key.'] = ['.implode(',',$nrs).'];'.PHP_EOL;
			$return .= 'collsFound['.$key.'] = 0;'.PHP_EOL;
		}
		$return .= '</script>'.PHP_EOL;
		return $return;
	}
	
	/**
	 * Returns nrs for the designated row of the card
	 */
	function generateRowNumbers($row){
		$min = $row * 10;
		$max = $min + 9;
		$nrs = range($min,$max);
		shuffle($nrs); 
		$return = array_slice($nrs, 0, 6);
		sort($return);
		return $return;
	}
	
	function generateCss(){
		return $this->css;
	}
	
	function generateJs(){
		return $this->js;
	}

}

$bingo = new bingo;

echo $bingo->generateCss();
echo $bingo->generateCard(); 
echo $bingo->generateJs();

?>