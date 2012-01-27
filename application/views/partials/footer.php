<br>
<?php
//<pre style="position: fixed; bottom: -10px; left: 0px; right: 0px;">
//$connection = DB::connection();
//foreach ($connection->queries as $query) {
//	echo date('Y-m-d H:i:s').' - ' . $query['sql'] . ' (' . implode(',', $query['bindings']) . ') [' . $query['time'] . 'ms]<br>';
//}
//</pre>
?>
			<?php echo Asset::container('footer')->styles(); ?>
			<?php echo Asset::container('footer')->scripts(); ?>
		</div>
	</body>
</html>