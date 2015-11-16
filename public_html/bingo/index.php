<?php
include('bingo.php');
$bingo = new bingo;
?>
<link rel="stylesheet" href="/bingo/css/bingo.css"/>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

<script type="text/javascript" src="/bingo/js/bingo.js"></script>
<h1>Now Play Bingo!!!</h1>
<?php
echo $bingo -> generateCard();
?>
<br clear="all" />
<input type="button" onclick="drawNumbers();" value="Play!" id="startButton" />
<input type="button" onclick="window.location=window.location;" id="replayBtn" style="display:none;" value="Replay" />