var drawn = new Array();
var rows = new Array();
var colls = new Array();
var rowsFound = new Array();
var collsFound = new Array();

function drawNumbers() {
	$('#startButton').hide();
	var cont = true;
	var nr = Math.floor(Math.random() * 61) + 10;
	if ( typeof drawn[nr] != 'undefined') {
		//do nothing...
	} else {
		var elm = $('#nr' + nr);
		drawn[nr] = nr;
		if (elm.length) {//the number is on the card!
			//elm.className = elm.className  + ' drawn';
			elm.addClass('drawn');
			colls.forEach(function(co) {
				co.forEach(function(it, ind) {
					if (it == nr) {
						collsFound[ind]++;
					}
				});
			});
			rows.forEach(function(ro) {
				ro.forEach(function(it, ind) {
					if (it == nr) {
						rowsFound[ind]++;
					}
				});
			});
		}
		var drawnCont = $('#chozenNumbers');
		var drawnContHtml = drawnCont.html();
		var comma = (drawnContHtml == '') ? '' : ',';
		drawnCont.html(drawnContHtml + comma + nr);
	}
	//Now check if there is a bingo. if so, then stop and shout it out!
	rowsFound.forEach(function(it, ind) {
		if (it == 6) {
			cont = false;
			var elms = $('#col' + ind);
			for (var i = 0,il = elms.length; i < il; i++) {
				elms[i].className = elms[i].addClass('drawnWon');
			}
		}
	});
	collsFound.forEach(function(it, ind) {
		if (it == 6) {
			cont = false;
			var elmRow = $('#row' + (ind + 1));
			elmRow.addClass('drawnRow');
		}
	});
	if (cont === true) {
		setTimeout(drawNumbers, 200);
	} else {
		$('#bingoTitle').show();
		$('#replayBtn').show();
	}
}

