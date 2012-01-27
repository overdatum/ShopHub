<script type="text/javascript">
	var PQP_DETAILS = true;
	var PQP_HEIGHT = "short";
	
	addEvent(window, 'load', loadCSS);

	function changeTab(tab) {
		var pQp = document.getElementById('pQp');
		hideAllTabs();
		addClassName(pQp, tab, true);
	}
	
	function hideAllTabs() {
		var pQp = document.getElementById('pQp');
		removeClassName(pQp, 'console');
		removeClassName(pQp, 'speed');
		removeClassName(pQp, 'queries');
		removeClassName(pQp, 'memory');
		removeClassName(pQp, 'files');
	}
	
	function toggleDetails(){
		var container = document.getElementById('pqp-container');
		
		if(PQP_DETAILS){
			addClassName(container, 'hideDetails', true);
			PQP_DETAILS = false;
		}
		else{
			removeClassName(container, 'hideDetails');
			PQP_DETAILS = true;
		}
	}
	function toggleHeight(){
		var container = document.getElementById('pqp-container');
		
		if(PQP_HEIGHT == "short"){
			addClassName(container, 'tallDetails', true);
			PQP_HEIGHT = "tall";
		}
		else{
			removeClassName(container, 'tallDetails');
			PQP_HEIGHT = "short";
		}
	}
	
	function loadCSS() {
		var sheet = document.createElement("link");
		sheet.setAttribute("rel", "stylesheet");
		sheet.setAttribute("type", "text/css");
		sheet.setAttribute("href", "/pqp/css/pQp.css");
		document.getElementsByTagName("head")[0].appendChild(sheet);
		setTimeout(function(){document.getElementById("pqp-container").style.display = "block"}, 10);
	}
	
	
	//http://www.bigbold.com/snippets/posts/show/2630
	function addClassName(objElement, strClass, blnMayAlreadyExist){
	   if ( objElement.className ){
	      var arrList = objElement.className.split(' ');
	      if ( blnMayAlreadyExist ){
	         var strClassUpper = strClass.toUpperCase();
	         for ( var i = 0; i < arrList.length; i++ ){
	            if ( arrList[i].toUpperCase() == strClassUpper ){
	               arrList.splice(i, 1);
	               i--;
	             }
	           }
	      }
	      arrList[arrList.length] = strClass;
	      objElement.className = arrList.join(' ');
	   }
	   else{  
	      objElement.className = strClass;
	      }
	}

	//http://www.bigbold.com/snippets/posts/show/2630
	function removeClassName(objElement, strClass){
	   if ( objElement.className ){
	      var arrList = objElement.className.split(' ');
	      var strClassUpper = strClass.toUpperCase();
	      for ( var i = 0; i < arrList.length; i++ ){
	         if ( arrList[i].toUpperCase() == strClassUpper ){
	            arrList.splice(i, 1);
	            i--;
	         }
	      }
	      objElement.className = arrList.join(' ');
	   }
	}

	//http://ejohn.org/projects/flexible-javascript-events/
	function addEvent( obj, type, fn ) {
	  if ( obj.attachEvent ) {
	    obj["e"+type+fn] = fn;
	    obj[type+fn] = function() { obj["e"+type+fn]( window.event ) };
	    obj.attachEvent( "on"+type, obj[type+fn] );
	  } 
	  else{
	    obj.addEventListener( type, fn, false );	
	  }
	}
</script>
<div id="pqp-container" class="pQp" style="display:none">
<div id="pQp" class="console">
﻿  <table id="pqp-metrics" cellspacing="0">
﻿  ﻿  <tr>
﻿  ﻿  ﻿  <td class="green" onclick="changeTab('console');">
﻿  ﻿  ﻿  ﻿  <var><?php echo count($logs['console']);?></var>
﻿  ﻿  ﻿  ﻿  <h4>Console</h4>
﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  <td class="blue" onclick="changeTab('speed');">
﻿  ﻿  ﻿  ﻿  <var><?php echo $speedTotals['total']?></var>
﻿  ﻿  ﻿  ﻿  <h4>Load Time</h4>
﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  <td class="purple" onclick="changeTab('queries');">
﻿  ﻿  ﻿  ﻿  <var><?php echo $queryTotals['count']; ?> Queries</var>
﻿  ﻿  ﻿  ﻿  <h4>Database</h4>
﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  <td class="orange" onclick="changeTab('memory');">
﻿  ﻿  ﻿  ﻿  <var><?php echo $memoryTotals['used']?></var>
﻿  ﻿  ﻿  ﻿  <h4>Memory Used</h4>
﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  <td class="red" onclick="changeTab('files');">
﻿  ﻿  ﻿  ﻿  <var><?php echo count($files); ?> Files</var>
﻿  ﻿  ﻿  ﻿  <h4>Included</h4>
﻿  ﻿  ﻿  </td>
﻿  ﻿  </tr>
﻿  </table>

﻿  <div id='pqp-console' class='pqp-box'>
﻿  ﻿  <?php if(count($logs['console']) == 0): ?>
﻿  ﻿  ﻿  <h3>This panel has no log items.</h3>
﻿  ﻿  <?php else: ?>
﻿  ﻿  ﻿  <table class='side' cellspacing='0'>
﻿  ﻿  ﻿  <tr>
﻿  ﻿  ﻿  ﻿  <td class='alt1'><var><?php echo $logs['logCount'];?></var><h4>Logs</h4></td>
﻿  ﻿  ﻿  ﻿  <td class='alt2'><var><?php echo $logs['errorCount'];?></var> <h4>Errors</h4></td>
﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  <tr>
﻿  ﻿  ﻿  ﻿  <td class='alt3'><var><?php echo $logs['memoryCount'];?></var> <h4>Memory</h4></td>
﻿  ﻿  ﻿  ﻿  <td class='alt4'><var><?php echo $logs['speedCount'];?></var> <h4>Speed</h4></td>
﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  </table>
﻿  ﻿  ﻿  <table class='main' cellspacing='0'>
﻿  ﻿  ﻿  ﻿  <?php foreach($logs['console'] as $i => $log):?>
﻿  ﻿  ﻿  ﻿  ﻿  <tr class='log-<?php echo $log['type']; ?>'>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <td class='type'><?php echo $log['type']; ?></td>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <td class="<?php echo $i%2 == 0 ? '':'alt'; ?>">
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php if($log['type'] == 'log' || $log['type'] == 'error'): ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <div><?php echo nl2br($log['data']); ?></div>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php elseif($log['type'] == 'memory'): ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <div><b><?php echo $log['data']; ?></b> <em><?php echo $log['dataType']; ?></em>: <?php echo nl2br($log['name']); ?> </div>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php elseif($log['type'] == 'speed'): ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <div><b><?php echo $log['data']; ?></b> <em><?php echo $log['name']; ?></em></div>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php elseif($log['type'] == 'error'): ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <div><em>Line <?php echo $log['line']; ?></em> : <?php echo $log['data']; ?> <pre><?php echo $log['file']; ?></pre></div>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php endif; ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  ﻿  <?php endforeach; ?>
﻿  ﻿  ﻿  </table>
﻿  ﻿  <?php endif; ?>
﻿  </div>

﻿  <div id="pqp-speed" class="pqp-box">
﻿  ﻿  <table class='side' cellspacing='0'>
﻿  ﻿  ﻿  <tr><td><var><?php echo $speedTotals['total']; ?></var><h4>Load Time</h4></td></tr>
﻿  ﻿  ﻿  <tr><td class='alt'><var><?php echo $speedTotals['allowed']; ?> s</var> <h4>Max Execution Time</h4></td></tr>
﻿  ﻿  </table>
﻿  ﻿  <?php if(count($profile) == 0): ?>
﻿  ﻿  ﻿  <h3>This panel has no log items.</h3>
﻿  ﻿  <?php else: ?>

﻿  ﻿  ﻿  <table class='main' cellspacing='0'>
﻿  ﻿  ﻿  <?php foreach($profile as $i => $log):?>
﻿  ﻿  ﻿  ﻿  ﻿  <tr class='log-speed'>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <td class="<?php echo $i%2 == 0 ? '':'alt'; ?>"><b><?php echo $this->getReadableTime($log[4]/$log[1]); ?></b> <?php echo $log[0]; ?></td>
﻿  ﻿  ﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  <?php endforeach; ?>
﻿  ﻿  ﻿  </table>
﻿  ﻿  <?php endif; ?>
﻿  </div>

﻿  <div id='pqp-queries' class='pqp-box'>
﻿  ﻿  <table class='side' cellspacing='0'>
﻿  ﻿  <tr><td><var><?php echo $queryTotals['count']; ?></var><h4>Total Queries</h4></td></tr>
﻿  ﻿  <tr><td class='alt'><var><?php echo $this->getReadableTime($queryTotals['time']); ?></var> <h4>Total Time</h4></td></tr>
﻿  ﻿  <tr><td><var><?php echo $queryTotals['duplicates']; ?></var> <h4>Duplicates</h4></td></tr>
﻿  ﻿  </table>
﻿  ﻿  <?php if($queryTotals['count'] == 0): ?>
﻿  ﻿  ﻿  <h3>This panel has no log items.</h3>
﻿  ﻿  <?php else: ?>

﻿  ﻿  ﻿  ﻿  <table class='main' cellspacing='0'>
﻿  ﻿  ﻿  ﻿  ﻿  <?php foreach($queries as $i => $query):?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <tr>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <td class="<?php echo $i%2 == 0 ? '':'alt'; ?>">
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <?php echo $query[0]; ?>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <em>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  Count: <b><?php echo $query[1]; ?></b> &middot;
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  Total: <b><?php echo $this->getReadableTime($query[4]); ?></b> &middot;
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  Average: <b><?php echo $this->getReadableTime($query[4]/$query[1]); ?></b> &middot;
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  Min: <b><?php echo $this->getReadableTime($query[2]); ?></b> &middot;
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  Max: <b><?php echo $this->getReadableTime($query[3]); ?></b>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  </em>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  ﻿  </td>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  ﻿  <?php endforeach; ?>
﻿  ﻿  ﻿  ﻿  </table>
﻿  ﻿  <?php endif; ?>
﻿  </div>

﻿  <div id="pqp-memory" class="pqp-box">
﻿  ﻿  <table class='side' cellspacing='0'>
﻿  ﻿  ﻿  <tr><td><var><?php echo $memoryTotals['used']; ?></var><h4>Used Memory</h4></td></tr>
﻿  ﻿  ﻿  <tr><td class='alt'><var><?php echo $memoryTotals['total']; ?></var> <h4>Total Available</h4></td></tr>
﻿  ﻿  </table>
﻿  ﻿  <?php if($logs['memoryCount'] == 0): ?>
﻿  ﻿  ﻿  <h3>This panel has no log items.</h3>
﻿  ﻿  <?php else: ?>

﻿  ﻿  ﻿  <table class='main' cellspacing='0'>
﻿  ﻿  ﻿  <?php foreach($memory as $i => $log):?>
﻿  ﻿  ﻿  ﻿  ﻿  <tr class='log-<?php echo $log['type']; ?>'>
﻿  ﻿  ﻿  ﻿  ﻿  ﻿  <td class="<?php echo $i%2 == 0 ? '':'alt'; ?>"><b><?php echo $log['data']; ?></b> <em><?php echo $log['dataType']; ?></em>: <?php echo nl2br($log['name']); ?></td>
﻿  ﻿  ﻿  ﻿  ﻿  </tr>
﻿  ﻿  ﻿  <?php endforeach; ?>
﻿  ﻿  ﻿  </table>
﻿  ﻿  <?php endif; ?>
﻿  </div>

﻿  <div id='pqp-files' class='pqp-box'>
﻿  ﻿  ﻿  <table class='side' cellspacing='0'>
﻿  ﻿  ﻿  ﻿  <tr><td><var><?php echo $fileTotals['count']; ?></var><h4>Total Files</h4></td></tr>
﻿  ﻿  ﻿  ﻿  <tr><td class='alt'><var><?php echo $fileTotals['size']; ?></var> <h4>Total Size</h4></td></tr>
﻿  ﻿  ﻿  ﻿  <tr><td><var><?php echo $fileTotals['largest']; ?></var> <h4>Largest</h4></td></tr>
﻿  ﻿  ﻿  </table>
﻿  ﻿  ﻿  <table class='main' cellspacing='0'>
﻿  ﻿  ﻿  ﻿  <?php foreach($files as $i => $file): ?>
﻿  ﻿  ﻿  ﻿  ﻿  <tr><td class="<?php echo $i%2 == 0 ? '':'alt'; ?>"><b><?php echo $file['size'];?></b> <?php echo $file['name'];?></td></tr>
﻿  ﻿  ﻿  ﻿  <?php endforeach; ?>
﻿  ﻿  ﻿  </table>
﻿  </div>

﻿  <table id="pqp-footer" cellspacing="0">
﻿  ﻿  <tr>
﻿  ﻿  ﻿  <td class="credit">
﻿  ﻿  ﻿  ﻿  <a href="http://particletree.com/features/php-quick-profiler/" target="_blank">
﻿  ﻿  ﻿  ﻿  <strong>PHP</strong>
﻿  ﻿  ﻿  ﻿  <b class="green">Q</b><b class="blue">u</b><b class="purple">i</b><b class="orange">c</b><b class="red">k</b>
﻿  ﻿  ﻿  ﻿  Profiler</a></td>
﻿  ﻿  ﻿  <td class="actions">
﻿  ﻿  ﻿  ﻿  <a href="#" onclick="toggleDetails();return false">Details</a>
﻿  ﻿  ﻿  ﻿  <a class="heightToggle" href="#" onclick="toggleHeight();return false">Height</a>
﻿  ﻿  ﻿  </td>
﻿  ﻿  </tr>
﻿  </table>
</div>
</div>