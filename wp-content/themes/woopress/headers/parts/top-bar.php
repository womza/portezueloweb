<?php if (etheme_get_option('top_bar')): ?>
	<div class="top-bar">
		<div class="container">
				<div class="languages-area">
					<?php if((!function_exists('dynamic_sidebar') || !dynamic_sidebar('languages-sidebar'))): ?>
						<div class="languages">
							<ul class="links">
								<li class="active">EN</li>
								<li><a href="#">FR</a></li>
								<li><a href="#">GE</a></li>
							</ul>
						</div>
						<div class="currency">
							<ul class="links">
								<li><a href="#">£</a></li>
								<li><a href="#">€</a></li>
								<li class='active'>$</li>
							</ul>
						</div>
					<?php endif; ?>	
				</div>

				<div class="top-links">
					<?php etheme_top_links(); ?>
				</div>
		</div>
	</div>
<?php endif; ?>