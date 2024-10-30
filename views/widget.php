<!-- This file is used to markup the public-facing widget. -->

 <div class="mdl-curr">
 	<h2 class="widget-title"><?php echo $title; ?></h2>
 	<ul>

 		<?php

 			foreach ($currencies as $cur) {

 				foreach ($bnm_rates as $val) {
 					
 					if ( $cur == $val['NumCode'] ) {
 						echo '<li class="mdl-item"><span class="dashicons dashicons-arrow-right-alt2"></span>' . $val['Name'] . ' : <strong>' . $val['Value'] .  '</strong></li>';
 					}
 				}
 			}

 		 ?>

 	</ul>
 </div>