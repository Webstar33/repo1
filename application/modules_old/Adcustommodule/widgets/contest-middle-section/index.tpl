<?php

?>

<style type="text/css">

.ad_contest_ul {
    padding: 20px 0px;
    text-align: center;
}

.ad_contest_ul li {
    display: inline-block;
    width: 31.5%;
    margin-right: 10px;
    margin-bottom: 20px;
}

.ad_contest_image img {
    width: 100%;
}

.ad_contest_ul li:nth-child(3n) {
    margin-right: 0;
}

#global_content .ad_contest_info .ad_contest_title {
    margin-bottom: 0;
	font-size:14px;
	font-weight:600;
}

.ad_contest_desc {
    font-size: 13px;
}

.ad_content_category {
    float: right;
}

.ad_content_username {
    margin-right: 5px;
}

.ad_contest_links a:before {
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

.ad_contest_links a{
	font-weight:bold;
}


</style>

<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="slick/slick.min.js"></script>
  
   <script type="text/javascript">
   
    jQuery.noConflict();
	
    jQuery(document).ready(function(){
		jQuery(".sd_feed_categories").css("width",'96%');
		jQuery("#sd_feed_filters").on('click', 'button.slick-next', function () {
			  jQuery(".sd_feed_categories").css("width",'92%');
              jQuery(".sd_feed_categories").css("margin-left",'25px');  
        });
		
      jQuery('.sd_feed_categories').slick({
       dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 7,
  slidesToScroll: 3,
  responsive: [
    {
      breakpoint: 1024,
      settings: {
        slidesToShow: 3,
        slidesToScroll: 3,
        infinite: true,
        dots: false
      }
    },
    {
      breakpoint: 600,
      settings: {
        slidesToShow: 2,
        slidesToScroll: 2
      }
    },
    {
      breakpoint: 480,
      settings: {
        slidesToShow: 1,
        slidesToScroll: 1
      }
    }
    // You can unslick at a given breakpoint now by adding:
    // settings: "unslick"
    // instead of a settings object
  ]
      });
    });
  </script>
  

<?php $apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey();
    $this->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?libraries=places&key=$apiKey")
?>

<?php $this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Activity/externals/styles/custom.css'); ?>


<script type='text/javascript'>


function filterFeedCategory(element,cat,subCat){
    if(!$('activity-feed')){
        return;
    }
  
    window.feedFilterCat = cat;
    window.feedFilterSubCat = subCat;
    $("sd_feed_filters").getElements("li").removeClass("active");
    var parentLi = $(element).getParent();
    parentLi.addClass("active");
    var mainParent = parentLi.getParent("li");
    if(mainParent && mainParent.hasClass("has_subcategories")){
        mainParent.addClass("active");
    }
    
}

</script> 


<div class="contest_page_wrapper">

    <div class='sd_feed_header'>
		<div class='sd_feed_headersection sd_feed_mostpopular'>
			<a href='javascript:void(0);' class="sd_active_pouplar"><?php echo $this->translate("Contests"); ?></a>
			<ul>
			<li class='active'>
			<a href='javascript:void(0);'><?php echo $this->translate("Contests"); ?></a>
			</li>
			<li>
			<a href='javascript:void(0);'><?php echo $this->translate("New"); ?></a>
			</li>
			<li>
			<a href='javascript:void(0);'><?php echo $this->translate("Classic"); ?></a>
			</li>
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
			<a href='javascript:void(0);'><img src="/public/admin/health-monitor.png" /></a>
			<a href='javascript:void(0);' onclick='loadAllFeed(this);'><img src="/public/admin/all_icon.png" /></a>       
			<a href='javascript:void(0);'><img src="/public/admin/social_network.png" /></a>
			<a href='javascript:void(0);'> <?php echo $this->translate('33k'); ?></a> 			
		</div>
	</div>
			
	<div class="sd_feed_header_seperator"></div>

	<div class="sd_feed_filters" id="sd_feed_filters">
		<?php $allCategories= array (
		'1' => "Photo",
		'2' => "Music",
		'3' => "Sports",
		'4' => "Women",
		'5' => "Kids",
		'6' => "18&Over",
		'7' => "Movies",
		'8' => "Gaming",
		'9' => "Love",
		'10' => "Religion",
		'11' => "LoL",
		'12' => "Sci&Tec",
		'13' => "Repost",
		'14' => "Friends",
		'15' => "Schools",
		'16' => "Playlists",
		'17' => "Groups",
		'18' => "Family",
		); ?>
		<?php if(count($allCategories)): ?>
			<ul class="sd_feed_categories">
				<li class="active">
					<a href="javascript:void(0);" onclick="filterFeedCategory(this,'','');"><?php echo $this->translate("All"); ?></a>                
				</li>
			<?php foreach($allCategories as $key => $category): ?>
					<li class="">
						<a href="javascript:void(0);" onclick="filterFeedCategory(this,'<?php echo $key; ?>','');"><?php echo $category; ?></a>
					</li>
			<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>
			
	<div id="activity-feed"></div>		
	<div class="ad_contest_top_section">
	
		<ul class="ad_contest_ul">
          <li class="ad_contest_li">
		  <?php $href = $this->url(array('module' => 'adcustommodule', 'controller' => 'index', 'action' => 'tournament')); ?>
			<div class="ad_contest_image">
			    <a href="<?php echo $href; ?>"><img src="/application/modules/Activity/externals/images/contest/con_img_1.jpg" /></a>
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="<?php echo $href; ?>">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>	
			</div>
		  </li>
          <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_2.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>	
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_3.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			    <img src="/application/modules/Activity/externals/images/contest/con_img_4.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_5.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_6.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		</ul>

		<div class="ad_contest_links">
			<a href="javascript:void(0);" class="ad_content_vote">Vote Now</a>
        </div> 

	</div>

	<div class="ad_contest_bottom_section">

		<ul class="ad_contest_ul">
          <li class="ad_contest_li">
			<div class="ad_contest_image">
			    <img src="/application/modules/Activity/externals/images/contest/con_img_7.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
          <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_8.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_9.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			    <img src="/application/modules/Activity/externals/images/contest/con_img_10.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_11.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_12.jpg" />
			</div>
			<div class="ad_contest_info">
			   <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>	
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_13.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_14.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		  <li class="ad_contest_li">
			<div class="ad_contest_image">
			     <img src="/application/modules/Activity/externals/images/contest/con_img_15.jpg" />
			</div>
			<div class="ad_contest_info">
			    <h3 class="ad_contest_title"> <a href="#">Demo/This is a Demo </a></h3>
				<p class="ad_contest_desc">
					<span class="ad_content_username"> <a href="#">Webstar Ent.</a> </span>
					<span class="ad_content_category"> <a href="#">Hip Hop,Music</a></span>
				</p>		
			</div>
		  </li>
		</ul>

	</div>

</div>