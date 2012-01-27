<div class="navbar">
	<div class="navbar-inner">
		<div class="container">
			<h3 class="brand">
				<a href="#">ShopHub</a>
			</h3>

			<!--
			<form action="" class="pull-left">
				<input type="text" placeholder="Search">
			</form>
			-->

			<?= HTML::menu($menu) ?>

			<div class="btn-group language-picker pull-right">
				<a class="btn no-click" href="#">Change language</a>
				<a class="btn dropdown-toggle" data-toggle="dropdown" href="#">
				<span class="caret"></span>
				</a>
				<ul class="dropdown-menu pull-right">
					<li><a href="#">English</a></li>
					<li><a href="#">Nederlands</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="container">