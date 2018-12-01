<?php

?>

<div class="topimages_wrapper">
<div id='core_menu_topimages_menu'>
  <ul>
    <?php foreach( $this->navigation as $link ): ?>
	  
	  <?php $icon = $link->get('icon');
         if($icon) {
            $image = $icon;
		 }else {
            $image = "public/admin/no_image.png";	
		 }	
      ?>		 
      <li>
	  <a href="<?php echo $link->getHref(); ?>" target="<?php echo $link->get('target'); ?>" class="ad_image_box_link">
			<img src="<?php echo $image; ?>" />
			<div class="ad_label"> <?php echo $this->translate($link->getLabel()); ?> </div>
	  </a>
      </li>
	  <?php endforeach; ?>
	</ul>
</div>
</div>