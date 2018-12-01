
<?php $href = $this->url(array('module' => 'adcustommodule', 'controller' => 'index', 'action' => 'tournament')); ?>

<?php if(!$this->feedOnly): ?>
<style type="text/css">
.tabs_alt.tabs_parent.tab_collapsed {
    display: none;
}

.ad_store_links {
    clear: both;
    margin: 0px 0px 20px;
}

.ad_hiphop_ul {
    padding: 10px 0px 20px;
    text-align: center;
}

.ad_hiphop_ul li {
    display: inline-block;
    width: 31.5%;
    margin-right: 10px;
    margin-bottom: 20px;
}

.ad_store_image img {
    width: 100%;
	height: 130px;
}

.ad_hiphop_ul li:nth-child(3n) {
    margin-right: 0;
}

#global_content .ad_store_info .ad_store_title {
    margin-bottom: 0;
	font-size:14px;
	font-weight:600;
	height:22px;
}

.ad_store_desc {
    font-size: 13px;
}

.ad_content_category {
    float: right;
}

.ad_content_username {
    margin-right: 5px;
}

.ad_store_links a:before {
    content: "";
    background-image: url('/public/admin/w-star.png');
    background-repeat: no-repeat;
    background-size: contain;
    width: 44px;
    height: 30px;
    display: inline-block;
    vertical-align: middle;
    margin-right: 2px;
}

.ad_store_links a{
	font-weight:bold;
}

.sd_feed_header .sd_feed_headersection.sd_feed_worldwide{
	margin-right:-5.5% !important;
}

.sd_feed_header .sd_feed_headersection.sd_feed_mostpopular{
	width: 143px;
}
#online_user_list {
	display:none;
	position: absolute;
	background: #f4f6f7;
    box-shadow: 1px 1px 1px #ddd;
    display: none;
    z-index: 90;
    margin-top: 0px;
	margin-left:-30px;
}

#sd_filter_online_offline:hover ul{
 display:block !important;
}
#sd_filter_online_offline {
	float: left;
}
#online_user_list li {
   padding: 5px 10px;
}
#online_user_list li:hover  {
    background: #eee;
}
#online_user_list li a{
   font-size: 14px;
   display:block;
}
#online_user_list li a img{
   max-width: 15px;
   max-height:15px;
   float:right;
}
#online_user_list li .ad_green, #online_user_list li .ad_grey{
	padding: 6px;
}

#list_blog_poll_etc {
	display:none;
	position: absolute;
	background: #f4f6f7;
    box-shadow: 1px 1px 1px #ddd;
    display: none;
    z-index: 90;
    margin-top: 0px;
	margin-left:-30px;
}
#sd_filter_blogs_polls_etc:hover ul{
 display:block !important;
 margin-left: -25px;
}

#sd_filter_blogs_polls_etc {
	float: left;
}
#list_blog_poll_etc li {
   padding: 5px 10px;
}
#list_blog_poll_etc li:hover  {
    background: #eee;
}
#list_blog_poll_etc li a{
   font-size: 14px;
   display:block;
   padding: 0px 5px;
}
#list_blog_poll_etc li a img{
   max-width: 15px;
   max-height:15px;
   float:right;
}
#list_blog_poll_etc li.active, #sd_filter_online_offline li.active {
    background: #eee
}

.sd_feed_header .sd_feed_headersection.sd_feed_worldwide{
    margin-right:-5.5% !important;
}

.sd_feed_header .sd_feed_headersection.sd_feed_mostpopular{
    width: 143px;
}
.generic_layout_container .layout_adcustommodule_hiphop_middle_section > h3{
    display:none!important;
}
.load_more_container span{
    font-size: 14px;
    font-weight: bold;
	
}

.load_more_container{
	text-align: center !important;
}
</style>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script type="text/javascript" src="slick/slick.min.js"></script>

<?php $this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Activity/externals/styles/custom.css'); ?>

<script type="text/javascript">
    jQuery.noConflict();
</script>

<div class="hiphop_page_wrapper">

    <div class='sd_feed_header'>
		<div class='sd_feed_headersection sd_feed_mostpopular'>
			<a href='javascript:void(0);' id="<?php echo $this->popularCol; ?>" class="sd_active_pouplar"><?php echo $this->translate("Most Popular"); ?></a>
			<ul>
			<li class='ad_display_pop active'>
			<a class="most_popular_tab" id="most_popular_tab" href='javascript:void(0);' onclick="loadMostPopularFeed(this);"><?php echo $this->translate("Most Popular"); ?></a>
			</li>
			<li class="ad_display_new">
			<a class="new_video_tab" id="new_video_tab" href='javascript:void(0);' onclick="loadNewFeed(this);"><?php echo $this->translate("New"); ?></a>
			</li>
			<!--<li>
			<a href='javascript:void(0);'><?php echo $this->translate("Classic"); ?></a>
			</li>-->
			</ul>
		</div>
		<div class='sd_feed_headersection sd_feed_worldwide'>
		<a href="javascript:void(0);" onclick='Smoothbox.open("./adcustommodule/location/edit") '><?php echo $this->translate("Worldwide"); ?></a>
		<!-- <a href='javascript:void(0);' onclick='showLocationFilter(this);'><?php echo $this->translate("Worldwide"); ?></a> -->
		<div class='sd_feed_location_filter' style='display: none;'>
		<input type='text' id='location' name='location'/>
		<input type='hidden' id='locationParams' name='locationParams'/>
		<div id='map' style="position: relative; height: 270px; width: 270px; overflow: hidden;"></div>
		</div>
		</div>
		<div class='sd_feed_headersection sd_feed_options'>
			<div id="sd_filter_online_offline">
				<a href='javascript:void(0);'><img src="/public/admin/health-monitor.png" /></a>
				<ul id="online_user_list">
					<li class="active">
						<a href="javascript:void(0);" onclick="loadAllOnlineFeed(this, 0);"><?php echo $this->translate("All"); ?></a>
					</li>
					<li class="">
						<a class="online_video_tab" id="online_video_tab" href="javascript:void(0);" onclick="loadAllOnlineFeed(this, 1);"><?php echo $this->translate("Online"); ?><span class="ad_green"></span></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadAllOnlineFeed(this, 2);"><?php echo $this->translate("Offline"); ?><span class="ad_grey"></span></a>
					</li>
					<li>
						<span class=""></span><a href="javascript:void(0);" onclick="loadAllOnlineFeed(this, 3);"><?php echo $this->translate("Featured"); ?></a>
					</li>
				</ul>
			</div>
			<div id="sd_filter_blogs_polls_etc">
				<a href='javascript:void(0);'><img src="/public/admin/all_icon.png" /></a>

				<ul id="list_blog_poll_etc">
					<li class="active">
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'all');"><?php echo $this->translate("All"); ?></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'status');"><?php echo $this->translate("Posts"); ?></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'video');"><?php echo $this->translate("Video"); ?></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'music_playlist');"><?php echo $this->translate("Mp3"); ?></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'blog');"><?php echo $this->translate("Blogs"); ?></a>
					</li>
					<li>
						<a href="javascript:void(0);" onclick="loadObjectTypeFeed(this, 'poll');"><?php echo $this->translate("Polls"); ?></a>
					</li>
				</ul>
			</div>
			<a href='javascript:void(0);' class="subscription_button_element" onclick="subscribeNotificationChannel(this);"><img src="/public/admin/social_network.png" /></a>
			<a href='javascript:void(0);'> <?php echo $this->translate('33k'); ?></a> 			
		</div>
	</div>
	<script type='text/javascript'>
en4.core.runonce.add(function(){
    if(document.getElementById('location')) {
        var autocompleteSECreateLocation = new google.maps.places.Autocomplete(document.getElementById('location'));
        <?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/location_feed.tpl'; ?>
    }
});

function showLocationFilter(element){
    $(element).getParent().getElement(".sd_feed_location_filter").toggle();
    var latLong = $("locationParams").value;
    if(latLong.length > 0){
        var latLong = JSON.parse(latLong);
        var latitude = parseInt(latLong.latitude);
        var longitude = parseInt(latLong.longitude);
        var geoLocation = {lat: latitude, lng: longitude};
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 8,
            center: geoLocation,
        });
        var marker = new google.maps.Marker({
            position: geoLocation,
            map: map
        });
    }else{
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 10,
            center: {lat: -34.397, lng: 150.644}
        });
    }
}

function updateLocation(location){
    var clearLocation = " <a href='javascript:void(0);' onclick='clearLocation(event,this);'><i class='fa fa-times'></i></a>";
    location = location+clearLocation;
    $$(".sd_feed_worldwide > a").set("html",location);
    $$(".sd_feed_location_filter").toggle();
    loadLocationFeed($$(".sd_feed_worldwide > a")[0]);
}
function clearLocation(event,element){
    event.preventDefault();
    $$(".sd_feed_worldwide > a").set("html","<?php echo $this->translate("Worldwide"); ?>");
    $("location").value = "";
    $("locationParams").value = "";
    $$(".sd_feed_location_filter").toggle();
    window.locationFeedFilter = "";
    filterFeedCategory($$(".sd_feed_worldwide > a")[0],'','');
}

jQuery.noConflict();
window.mostLocationFeedRequest = 0;
window.locationFeedFilter = "";
window.new_post = 0;
window.isOnline = 0;
window.mostPopularFeedFilter = 1;

function loadLocationFeed(element){
    window.locationFeedFilter = $("locationParams").value;
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var subject_guid = '<?php echo $this->subjectGuid ?>';
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
    console.log(url);
    var request = new Request.HTML({
          url : url,
          data : {
            format : 'html',
            'feedOnly' : true,
            'getUpdate' : true,
            'subject' : subject_guid,
            'isOnline' : window.isOnline,
            'new_post': window.new_post,
            'mostpopular': window.mostPopularFeedFilter,
            'locationParams': window.locationFeedFilter
          },
          evalScripts : true,
          onRequest: function(){
              loader.inject($(element),"after");
          },
          onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
                console.log('sandeep 11 n loadLocationFeed');
                console.log(responseHTML);
                loader.destroy();
                window.mostLocationFeedRequest = 0;
                jQuery('.activity-feed').empty();
                jQuery('.activity-feed').html(responseHTML);
                //Elements.from(responseHTML).inject(jQuery('.activity-feed'));
                if(!Elements.from(responseHTML) || Elements.from(responseHTML).length <= 0){
                    $("fail_msg").setStyle("display","block");
                }else{
                    $("fail_msg").setStyle("display","none");
                }
                
                en4.core.runonce.trigger();
                Smoothbox.bind(jQuery('.activity-feed'));
          }
        });
       request.send();
}

function loadMostPopularFeed(element, offset=null){
	
    var id = '<?php echo $this->popularCol; ?>';
    $(id).innerHTML = 'Most Popular';
    $$('.ad_display_pop').addClass('active');
    $$('.ad_display_new').removeClass('active');
    $$('.tab_1432.layout_adcustommodule_hiphop_middle_section').hide();
    $$('.tab_1430.layout_adcustommodule_hiphop_middle_section').show();
	
    window.mostPopularFeedFilter = 1;
    window.new_post = 0;
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var subject_guid = '<?php echo $this->subjectGuid ?>';
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
    var request = new Request.HTML({
            url : url,
            data : {
                format : 'html',
                'feedOnly' : true,
                'nolayout' : true,
                'getUpdate' : true,
                'subject' : subject_guid,
                'isOnline' : window.isOnline,
                'new_post': window.new_post,
                'mostpopular': window.mostPopularFeedFilter,
                'locationParams': window.locationFeedFilter,
                'offset':offset
            },
            evalScripts : true,
            onRequest: function(){
                if(offset==null){
                    loader.inject($(element),"after");
                }else{
                    $('load_more_container').empty();
                    loader.inject(jQuery('ad_store_top_section'),"after");
                }
            },
            onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {         
            console.log(responseHTML);
            console.log('sandeep test');       
                loader.destroy();
                window.mostPopularFeedRequest = 0;
                
                if(offset==null){
                    jQuery('ad_store_top_section').empty();
                }
                jQuery('.activity-feed').empty();
                jQuery('.activity-feed').html(responseHTML);
               // Elements.from(responseHTML).inject(jQuery('ad_store_top_section'));
                en4.core.runonce.trigger();
            }
        });
    request.send();
}

function loadNewFeed(element, offset=null){
	
    var id = '<?php echo $this->popularCol; ?>';
    $(id).innerHTML = 'New';
    $$('.ad_display_pop').removeClass('active');
    $$('.ad_display_new').addClass('active');
    $$('.tab_1430.layout_adcustommodule_hiphop_middle_section').hide();
    $$('.tab_1432.layout_adcustommodule_hiphop_middle_section').show();
	
    window.new_post = 1;
    window.mostPopularFeedFilter = 0;
    
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var subject_guid = '<?php echo $this->subjectGuid ?>';
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
        
    var request = new Request.HTML({
            url : url,
            data : {
                format : 'html',
                'feedOnly' : true,
                'nolayout' : true,
                'getUpdate' : true,
                'subject' : subject_guid,
                'isOnline' : window.isOnline,
                'new_post': window.new_post,
                'mostpopular': window.mostPopularFeedFilter,
                'locationParams': window.locationFeedFilter
            },
            evalScripts : true,
            onRequest: function(){
              if(offset==null){
                    loader.inject($(element),"after");
                }else{
                    jQuery('load_more_container').empty();
                    loader.inject(jQuery('ad_store_top_section'),"after");
                }
            },
            onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
                console.log('sandeep 33');
                console.log(responseHTML);
                loader.destroy();
                window.allFeedRequest = 0;
                window.popularFeedFilter = 0;
                if(offset==null){
                    jQuery('.activity-feed').empty();
                }
                jQuery('.activity-feed').empty();
                jQuery('.activity-feed').html(responseHTML);
               // Elements.from(responseHTML).inject(jQuery('.activity-feed'));
                en4.core.runonce.trigger();
                
            }
        });
    request.send();
}

function loadAllOnlineFeed(element, elemVal, offset=null){
    
    window.isOnline = 1;

    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var subject_guid = '<?php echo $this->subjectGuid ?>';
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';
        
    var request = new Request.HTML({
            url : url,
            data : {
                format : 'html',
                'feedOnly' : true,
                'nolayout' : true,
                'getUpdate' : true,
                'subject' : subject_guid,
                'isOnline' : window.isOnline,
                'new_post': window.new_post,
                'mostpopular': window.mostPopularFeedFilter,
                'locationParams': window.locationFeedFilter
            },
            evalScripts : true,
            onRequest: function(){
              //loader.inject($(element));
                if(offset==null){
                    loader.inject($(element));
                }else{
                    jQuery('load_more_container').empty();
                    loader.inject($('ad_store_top_section'),"after");
                }
            },
            onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
                console.log('sandeep 22');
                console.log(responseTree);

                loader.destroy();
                window.allFeedRequest = 0;
                window.popularFeedFilter = 0;
                if(offset==null){
                    jQuery('ad_store_top_section').empty();
                }
                jQuery('.activity-feed').empty();
                jQuery('.activity-feed').html(responseHTML);
               // Elements.from(responseHTML).inject(jQuery('ad_store_top_section'));
                en4.core.runonce.trigger();
            }
        });
    request.send();
}

function loadMoreVideo(element){	
    var total = $("#")
    var total = document.getElementsByName("total_video")[0].value;//$("input[name='total_video']").val();
    var offset = document.getElementsByName("offset")[0].value;

    if(window.mostPopularFeedFilter == 1){
        loadMostPopularFeed($('most_popular_tab'), offset);
    }else if(window.new_post == 1){
        loadNewFeed($('new_video_tab'), offset);
    }else if(window.isOnline == 1){
        loadAllOnlineFeed($('online_video_tab'), 1, offset);
    }        
}

</script>    
	
	<div class="tip" id="fail_msg" style="display: none;">
		<span>****
			<?php $url = $this->url(array('module' => 'user', 'controller' => 'settings', 'action' => 'privacy'), 'default', true) ?>
			<?php echo $this->translate('The post was not added to the feed. Please check your %1$sprivacy settings%2$s.', '<a href="'.$url.'">', '</a>') ?>
		</span>
	</div>
	
	<div class="sd_feed_header_seperator"></div>

	<div id="activity-feed-ad" ></div>	
    <div class="activity-feed">	
	<div class="ad_store_top_section " id="ad_store_top_section">
	
	<ul class="ad_hiphop_ul widget/hip-hipmiddle-section/index.tpl" id="ad_hiphop_ul">
		 <?php 
		  $count = 1;
		  if($this->ispopularvideoexist == 1) : 
		foreach( $this->paginator as $item ):
			
		$secondcategory= 'Hip Hop';
		
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
        <li class="ad_store_li">
		
          <div class="ad_store_image">
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
		
		   
          <div class="ad_store_info"> 
		  
            <div class="ad_store_title_wrapper">
			<h3 class="ad_store_title">
              <?php $content = $item->getTitle(); 
			 
			    $pos=strpos($content, ' ', 20);
				$title = substr($content,0,$pos ); 
				
				echo $this->htmlLink($item->getHref(), $title); 
			   ?>
			</h3>
           </div>
		   
		   <p class="ad_store_desc">
				<span class="ad_content_username">  
					<?php
						$owner = $item->getOwner();
						echo $this->translate('%1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
					?> 
				  </span>
				<span class="ad_content_category">  
					<?php if(!empty($secondcategory)) {
							$scat = str_replace(" ","-",$secondcategory);
							$secondcat = strtolower($scat);
							
							echo '<a href="pages/'.$secondcat.'">'.$secondcategory.'</a>';
					   }else{
						   echo "Update";
					   } ?>
					 </span>
		   </p>	
          </div>
        </li>
		
		
      <?php endforeach; 
	   else: ?>
	     <div class="tip">
		   <span> <?php echo $this->translate('No videos Posted yet!'); ?> </span>
		 </div>
	   <?php endif; ?>
    </ul>
       
</div>	
	</div>

        	
</div>
<?php else: ?>
	<ul class="ad_hiphop_ul widget/hip-hipmiddle-section/index.tpl">
		 <?php 
		  $count = 1;
		  if($this->ispopularvideoexist == 1) : 
		foreach( $this->paginator as $item ): 
		$secondcategory= 'Hip Hop';
		
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
        <li class="ad_store_li" id="<?php echo $item->video_id; ?>">
		
          <div class="ad_store_image">
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
		
		   
          <div class="ad_store_info"> 
		  
            <div class="ad_store_title_wrapper">
			<h3 class="ad_store_title">
              <?php $content = $item->getTitle(); 
			 
			    $pos=strpos($content, ' ', 20);
				$title = substr($content,0,$pos ); 
				
				echo $this->htmlLink($item->getHref(), $title); 
			   ?>
			</h3>
           </div>
		   
		   <p class="ad_store_desc">
					<span class="ad_content_username">  
						<?php
							$owner = $item->getOwner();
							echo $this->translate('%1$s', $this->htmlLink($owner->getHref(), $owner->getTitle()));
						?> 
					  </span>
					<span class="ad_content_category">  
					    <?php if(!empty($secondcategory)) {
							    $scat = str_replace(" ","-",$secondcategory);
								$secondcat = strtolower($scat);
								
							    echo '<a href="pages/'.$secondcat.'">'.$secondcategory.'</a>';
						   }else{
							   echo "Update";
						   } ?>
						 </span>
		   </p>	
          </div>
        </li>
		
		<?php
		      $count++; ?>
      <?php endforeach; 
	   else: ?>
	     <div class="tip">
		   <span> <?php echo $this->translate('No videos Posted yet!'); ?> </span>
		 </div>
	   <?php endif; ?>
    </ul>
        
<?php endif; ?>
<?php  if($this->isloadmorecreate){ ?>
<div id="load_more_container" class="load_more_container">
<a href="javascript:;" onclick="loadMoreVideo(this);"><span>Load More</span></a>
<input type="hidden" name="total_video" id="total_video" value="<?php echo $this->totalvideo ; ?>">
<input type="hidden" name="offset" id="offset" value="<?php echo $this->nextoffset; ?>">
</div>
<?php } ?>

