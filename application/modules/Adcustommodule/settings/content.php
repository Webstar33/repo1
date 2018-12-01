<?php

return array(
  array(
    'title' => 'Top Images Menu',
    'description' => 'Display Menu as a images box',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.top-images-menu',
  ),
  array(
    'title' => 'Custom Main Menu',
    'description' => 'Shows the site-wide main menu. You can edit its contents in your menu editor.',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.custom-menu-main',
    'requirements' => array(
      'header-footer',
    ),
  ),
   array(
    'title' => 'Contest Middle Section',
    'description' => 'Shows the contest content according to category',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.contest-middle-section',
  ),
  
   array(
    'title' => 'Store Middle Section',
    'description' => 'Shows the store content according to category',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.store-middle-section',
  ),
  
   array(
    'title' => 'Hip-hop Middle Section',
    'description' => 'Shows the Hip Hop content',
    'category' => 'AD Custom widget',
    'type' => 'widget',
	'isPaginated' => true,
    'name' => 'adcustommodule.hiphop-middle-section',
	 'adminForm' => array(
      'elements' => array(
        array(
          'Radio',
          'popularType',
          array(
            'label' => 'Popular Type',
            'multiOptions' => array(
              'rating' => 'Rating',
              'view' => 'Views',
              'comment' => 'Comments',
			  'creation' => 'Recent',
            ),
            'value' => 'view',
          )
        ),
		)
	   ),
  ),
  
   array(
    'title' => 'Hot Middle Section',
    'description' => 'Shows the Hot page according to category',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.hot-middle-section',
  ),
  
  array(
    'title' => 'Custom Styles Popular Videos',
    'description' => 'Displays a list of most viewed videos.',
    'category' => 'AD Custom widget',
    'type' => 'widget',
    'name' => 'adcustommodule.ad-list-popular-videos',
    'isPaginated' => true,
    'defaultParams' => array(
      'title' => 'Popular Videos',
    ),
    'requirements' => array(
      'no-subject',
    ),
    'adminForm' => array(
      'elements' => array(
        array(
          'Radio',
          'popularType',
          array(
            'label' => 'Popular Type',
            'multiOptions' => array(
              'rating' => 'Rating',
              'view' => 'Views',
              'comment' => 'Comments',
			  'creation' => 'Recent',
            ),
            'value' => 'view',
          )
        ),
		array(
          'Select',
          'styleType',
          array(
            'label' => 'Style Type',
            'multiOptions' => array(
              'style1' => 'Style 1',
              'style2' => 'Style 2',
              'style3' => 'Style 3',
			  'style4' => 'Style 4',
			  'style5' => 'Style 5',
			  'style6' => 'Style 6',
            ),
            'value' => 'style1',
          )
        ),
      )
    ),
  ),
) ?>
