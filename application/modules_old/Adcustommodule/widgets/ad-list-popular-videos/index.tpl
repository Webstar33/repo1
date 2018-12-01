<?php

$attachmenttable = Engine_Api::_()->getDbtable('attachments', 'activity');

$cattable = Engine_Api::_()->getDbtable('categories', 'activity');


?>
<style>

.layout_adcustommodule_ad_list_popular_videos {
    background: none !important;
    border: 0 !important;
    padding: 5px !important;
}

.style1 ul li {
    border-bottom: 1px solid #ddd;
    padding: 10px 0px;
}

.style1 .photo img {
    width: 100%;
	max-width: 100%;
}

.style1 span.ad_creation_date {
    float: right;
}

.style1 .title a {
    font-weight: 700;
}

.style4 .title a {
    font-weight: 700;
}

.style4 .photo img {
    width: 100%;
	max-width: 100%;
}

.style4 ul li {
    border-bottom: 1px solid #ddd;
    padding: 10px 0px;
}

.style4 span.ad_category {
    float: right;
}

.ad_custom_widgets_styles .embed_video .ytp-title-text {
    display: none;
}

</style>

<div class="ad_custom_widgets_styles">

<?php if($this->styletype == 'style1'): ?>

<div class="generic_list_wrapper_ad style1">
    <ul class="generic_list_widget_ad generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ):
	  
	      // increment count
        $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		<div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		</div>
		  
		  
          <div class="info">
            <div class="title">
             <?php $content = $item->getTitle(); 
			 
			    $pos=strpos($content, ' ', 30);
				$title = substr($content,0,$pos ); 
				
				echo $this->htmlLink($item->getHref(), $title); 
			?>
            </div>
            <div class="stats">
			<span class="ad_owner_title">
             <?php
                $owner = $item->getOwner();
                echo $this->translate('by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
              ?>
			 </span>
			 <span class="ad_creation_date">
				<?php echo $this->timestamp($item->creation_date) ?>
			 </span>
            </div>
          
			<div class="ad_rating_wrapper">
				<span class="star_rating_wrapper">
				<?php for( $x=1; $x<=$item->rating; $x++ ): ?>
				  <span class="rating_star_generic rating_star"></span>
				<?php endfor; ?>
				<?php if( (round($item->rating) - $item->rating) > 0): ?>
				  <span class="rating_star_generic rating_star_half"></span>
				<?php endif; ?>
				<?php for( $x=5; $x>round($item->rating); $x-- ): ?>
				  <span class="rating_star_generic rating_star_empty"></span>
				<?php endfor; ?>
				</span>
			</div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>


<?php if($this->styletype == 'style2'): ?>

<div class="generic_list_wrapper style2">
    <ul class="generic_list_widget generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ):   // increment count
        $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		<div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		</div>
		
          <div class="info">
            <div class="title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <div class="stats">
              <?php if( $this->popularType == 'rating' ): ?>
                <?php echo $this->translate('%s / %s', $this->locale()->toNumber(sprintf('%01.1f', $item->rating)), $this->locale()->toNumber('5.0')) ?>
              <?php elseif( $this->popularType == 'comment' ): ?>
                <?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>
              <?php else /*if( $this->popularType == 'view' )*/: ?>
                <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
              <?php endif; ?>
            </div>
            <div class="owner">
              <?php
                $owner = $item->getOwner();
                echo $this->translate('Posted by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
              ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>

<?php if($this->styletype == 'style3'): ?>

<div class="generic_list_wrapper style3">
    <ul class="generic_list_widget generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ):   // increment count
        $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		<div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		</div>
          <div class="info">
            <div class="title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <div class="stats">
              <?php if( $this->popularType == 'rating' ): ?>
                <?php echo $this->translate('%s / %s', $this->locale()->toNumber(sprintf('%01.1f', $item->rating)), $this->locale()->toNumber('5.0')) ?>
              <?php elseif( $this->popularType == 'comment' ): ?>
                <?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>
              <?php else /*if( $this->popularType == 'view' )*/: ?>
                <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
              <?php endif; ?>
            </div>
            <div class="owner">
              <?php
                $owner = $item->getOwner();
                echo $this->translate('Posted by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
              ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>

<?php if($this->styletype == 'style4'): ?>

<div class="generic_list_wrapper_ad style4">
    <ul class="generic_list_widget_ad generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ): 
	  
	    $attachrow = $attachmenttable->fetchRow($attachmenttable->select()->where('type = ?','video')->where('id = ?',$item->video_id));
		
		//$firstcategory = '';
		$secondcategory= '';
		if(count($attachrow) == '1') {
		   	$actionid = $attachrow['action_id'];
			$action = Engine_Api::_()->getItem('activity_action', $actionid);
			
			//$cat = $cattable->fetchRow($cattable->select()->where('category_id = ?',$action->category_id));
			//$firstcategory = $cat['title'];
			
			$subcat = $cattable->fetchRow($cattable->select()->where('category_id = ?',$action->subcategory_id));
			$secondcategory = $subcat['title'];
		}
	
        $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		
          <div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		  </div>
		
		   
          <div class="info"> 
		  
            <div class="title">
              <?php $content = $item->getTitle(); 
			 
			    $pos=strpos($content, ' ', 20);
				$title = substr($content,0,$pos ); 
				
				echo $this->htmlLink($item->getHref(), $title); 
			   ?>
           </div>
		   
		   
			<div class="owner">
					  <span class="ad_owner_title">
					 <?php
						$owner = $item->getOwner();
						echo $this->translate('%1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
					  ?>
					 </span>
					 
					 <span class="ad_category">
							<?php echo $secondcategory; ?>
					</span>
			</div>
			
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>

<?php if($this->styletype == 'style5'): ?>

<div class="generic_list_wrapper style5">
    <ul class="generic_list_widget generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ):  
	  
	  $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		<div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		</div>
          <div class="info">
            <div class="title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
            <div class="stats">
              <?php if( $this->popularType == 'rating' ): ?>
                <?php echo $this->translate('%s / %s', $this->locale()->toNumber(sprintf('%01.1f', $item->rating)), $this->locale()->toNumber('5.0')) ?>
              <?php elseif( $this->popularType == 'comment' ): ?>
                <?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>
              <?php else /*if( $this->popularType == 'view' )*/: ?>
                <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
              <?php endif; ?>
            </div>
            <div class="owner">
              <?php
                $owner = $item->getOwner();
                echo $this->translate('Posted by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
              ?>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>

<?php if($this->styletype == 'style6'): ?>

<div class="generic_list_wrapper style6">
    <ul class="generic_list_widget generic_list_widget_large_photo">
      <?php foreach( $this->paginator as $item ):  
	  $embedded = "";
        if ($item->status == 1) {
            $embedded = $item->getRichContent(true);
        }
        if ($item->type == 'upload' && $item->status == 1) {
            if (!empty($item->file_id)) {
                $storage_file = Engine_Api::_()->getItem('storage_file', $item->file_id);
                if ($storage_file) {
                    $video_location = $storage_file->map();
                    $video_extension = $storage_file->extension;
                }
            }
        }
		?>
        <li>
		<div class="photo">
			<?php if( $item->type == 'upload' ): ?>
			<div id="video_embed" class="video_embed">
			<?php echo $this->htmlLink($item->getHref(), $this->itemPhoto($item, 'thumb.normal'), array('class' => 'thumb')) ?>
			</div>
			<?php else: ?>
			<div class="video_embed">
			<?php echo $embedded ?>
			</div>
			<?php endif; ?>
		</div>
		
          <div class="info">
            <div class="title">
              <?php echo $this->htmlLink($item->getHref(), $item->getTitle()) ?>
            </div>
			
			 <div class="owner">
              <?php
                $owner = $item->getOwner();
                echo $this->translate('by %1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
              ?>
            </div>
			
            <div class="stats">
              <?php if( $this->popularType == 'rating' ): ?>
                <?php echo $this->translate('%s / %s', $this->locale()->toNumber(sprintf('%01.1f', $item->rating)), $this->locale()->toNumber('5.0')) ?>
              <?php elseif( $this->popularType == 'comment' ): ?>
                <?php echo $this->translate(array('%s comment', '%s comments', $item->comment_count), $this->locale()->toNumber($item->comment_count)) ?>
              <?php else /*if( $this->popularType == 'view' )*/: ?>
                <?php echo $this->translate(array('%s view', '%s views', $item->view_count), $this->locale()->toNumber($item->view_count)) ?>
              <?php endif; ?>
            </div>
           
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
</div>

<?php endif; ?>

</div>
