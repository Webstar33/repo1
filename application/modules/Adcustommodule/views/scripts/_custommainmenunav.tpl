<?php

?>
<style type="text/css">

.layout_adcustommodule_custom_menu_main .layout_core_menu_main ul ul.dropdown-content {
    display: none;
    position: absolute;
    background-color: #f1f1f1;
    min-width: 160px;
    box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
    z-index: 1;
}

body .layout_core_menu_main .navigation li ul a {
    padding: 10px !important;
	width:160px;
	
}

body .layout_core_menu_main ul ul li:hover a {
    color: #5561EC;
    text-decoration: none;
    background: #FFF;
	border-radius: 0px;
}

.layout_core_menu_main ul ul li {
    border-bottom: 1px solid #ddd;
}

.layout_adcustommodule_custom_menu_main .layout_core_menu_main .dropdown-content a:hover {
  /*background-color: #ddd*/
}

.layout_adcustommodule_custom_menu_main .layout_core_menu_main ul li:hover .dropdown-content {
    display: block;
}

.layout_core_menu_main .navigation ul li:last-child {
    float: none;
}

</style>

<?php 

$navigation = Engine_Api::_()
      ->getApi('menus', 'core')
      ->getNavigation('custom_35');
?>

<ul class="navigation">
  <?php foreach( $this->container as $link ): ?>
    <li class="<?php echo $link->get('active') ? 'active' : '' ?>">
      <a href='<?php echo $link->getHref() ?>' class="<?php echo $link->getClass() ? ' ' . $link->getClass() : ''  ?>"
        <?php if( $link->get('target') ): ?>target='<?php echo $link->get('target') ?>' <?php endif; ?> >
        <span><?php echo $this->translate($link->getlabel()) ?></span>
      </a>
	  <?php if($link->getlabel() == 'More'): ?>
	     <ul class="dropdown-content">
			<?php
			// Render the more menu
				 foreach($navigation as $nav): ?>
					<li class="<?php echo $nav->get('active') ? 'active' : '' ?>">
						<a href='<?php echo $nav->getHref() ?>' class="<?php echo $nav->getClass() ? ' ' . $nav->getClass() : ''  ?>"
						<?php if( $nav->get('target') ): ?>target='<?php echo $nav->get('target') ?>' <?php endif; ?> >
						<!--<i class="fa <?php echo $nav->get('icon') ? $nav->get('icon') : 'fa-star' ?>"></i>-->
						<span><?php echo $this->translate($nav->getlabel()) ?></span>
						</a>
					</li>
	          <?php endforeach; ?>
			</ul>
	<?php endif; ?>	 
    </li>
  <?php endforeach; ?>
</ul>
