<!-- This file is used to markup the administration form of the widget. -->
<div class="bootstrap-wrapper">
	<p>
		<label for="<?php echo $mdl_title_id ?>"><?php _e( 'Title:', 'mdl-currency' ) ?></label>
		<input class="widefat form-control" id="<?php echo $mdl_title_id ?>" type="text" name="<?php echo $mdl_title_name ?>" value="<?php echo $title ?>">
	</p>
	<p>
		<label for="<?php echo $mdl_currencies_id?>"><?php _e( 'Currency:', 'mdl-currency' ) ?></label>
		<select multiple data-live-search="true" data-width="100%" title="Choose one or more exchange rates (multiple selection is allowed)" class="widefat selectpicker" name="<?php echo $mdl_currencies_name . "[]" ?>" id="<?php echo $mdl_currencies_id ?>">
			
			<?php 

				foreach ($bnm_rates as $val) {

					$sel = in_array($val['NumCode'], $currencies) ? 'selected=""' : '';

					echo '<option title= "' . $val["CharCode"] . '" value="' .$val["NumCode"] . '"' . $sel .'>' . $val["Name"] . '</option>';
				}
			 ?>

		</select>
	</p>
	<p>
		<label for="<?php echo $mdl_lang_id ?>"><?php _e( 'In which language should the data be displayed', 'mdl-currency' ) ?></label>
		<select class="form-control" name="<?php echo $mdl_lang_name ?>" id="<?php echo $mdl_lang_id ?>">
			<option value="en"<?php selected( $rates_lang, 'en' ) ?>>English</option>
			<option value="ru"<?php selected( $rates_lang, 'ru' ) ?>>Русский</option>
			<option value="ro"<?php selected( $rates_lang, 'ro' ) ?>>Română</option>
		</select>
	</p>
</div>		