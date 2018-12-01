

<div class="layout_core_menu_main custom_main_menu">
	<div class="main_menu_navigation">
	<?php

	 echo $this->navigation()
	  ->menu()
	  ->setContainer($this->navigation)
	  ->setPartial(array('_custommainmenunav.tpl', 'adcustommodule'))
	  ->render();
	  
	?>
	</div>
</div>