<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Widget
 * @author     Stars Developer
 */
?>
<?php
  $this->headScript()
    ->appendFile($this->layout()->staticBaseUrl . 'externals/mdetect/mdetect' . ( APPLICATION_ENV != 'development' ? '.min' : '' ) . '.js')
    ->appendFile($this->layout()->staticBaseUrl . 'application/modules/Core/externals/scripts/composer.js');
?>

<?php $this->headLink()->prependStylesheet($this->layout()->staticBaseUrl . 'application/modules/Activity/externals/styles/custom.css'); ?>

<?php if( (empty($this->feedOnly) || !$this->endOfFeed ) &&
    (empty($this->getUpdate) && empty($this->checkUpdate)) ): ?>
 
<div class='sd_feed_header'>
    <div class='sd_feed_headersection sd_feed_mostpopular'>
        <?php echo $this->translate("Most Popular"); ?>
    </div>
    <div class='sd_feed_headersection sd_feed_worldwide'>
        <?php echo $this->translate("Worldwide"); ?>
    </div>
    <div class='sd_feed_headersection sd_feed_options'>
        <?php echo $this->translate("All"); ?>
    </div>
</div>
<script type='text/javascript'>
function showPostContainer(element){
    $("sd_post_container").setStyle("display","block");
    $(element).setStyle("display","none");
}
function hidePostContainer(element){
    $("sd_post_container").setStyle("display","none");
    $$(".sd_post_feed_button button").setStyle("display","block");
}
function showSubCategory(element){
    var category = $(element).get("value");
    if($$(".sd_composer_subcategories select").length > 0){
        $$(".sd_composer_subcategories select").setStyle("display","none");
        $$(".sd_composer_subcategories select").set("disabled","disabled");
    }
    if(category.length <= 0){
        return;
    }
    var subCatElement = $("compose_subcategory"+category);
    if(!subCatElement){
        return;
    }
    subCatElement.setStyle("display","");
    subCatElement.set("disabled",null);
}
window.feedFilterRequest = 0;
window.feedFilterCat = "<?php echo $this->category_id; ?>";
window.feedFilterSubCat = "<?php echo $this->subcategory_id; ?>";
function filterFeedCategory(element,cat,subCat){
    if(!$('activity-feed')){
        return;
    }
    if(window.feedFilterRequest){
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
    window.feedFilterRequest = 1;
    var loader = en4.core.loader.clone();
    loader.addClass("sd_loader");
    var filters = $("sd_feed_filters");
    var subject_guid = '<?php echo $this->subjectGuid ?>';
    var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
    var request = new Request.HTML({
          url : url,
          data : {
            format : 'html',
            'feedOnly' : true,
            'nolayout' : true,
            'subject' : subject_guid,
            'category_id': cat,
            'subcategory_id': subCat
          },
          evalScripts : true,
          onRequest: function(){
              loader.inject(filters.getElement(".sd_feed_categories"),"after");
          },
          onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
                loader.destroy();
                window.feedFilterRequest = 0;
                $('activity-feed').empty();
                Elements.from(responseHTML).inject($('activity-feed'));
                if(!Elements.from(responseHTML) || Elements.from(responseHTML).length <= 0){
                    $("fail_msg").setStyle("display","block");
                }else{
                    $("fail_msg").setStyle("display","none");
                }
                
                en4.core.runonce.trigger();
                Smoothbox.bind($('activity-feed'));
          }
        });
       request.send();
}
</script>    
<?php endif; ?>


<?php if( (!empty($this->feedOnly) || !$this->endOfFeed ) &&
    (empty($this->getUpdate) && empty($this->checkUpdate)) ): ?>
  <script type="text/javascript">
    en4.core.runonce.add(function() {
      
      var activity_count = <?php echo sprintf('%d', $this->activityCount) ?>;
      var next_id = <?php echo sprintf('%d', $this->nextid) ?>;
      var subject_guid = '<?php echo $this->subjectGuid ?>';
      var endOfFeed = <?php echo ( $this->endOfFeed ? 'true' : 'false' ) ?>;

      var activityViewMore = window.activityViewMore = function(next_id, subject_guid) {
        if( en4.core.request.isRequestActive() ) return;
        
        var url = '<?php echo $this->url(array('module' => 'core', 'controller' => 'widget', 'action' => 'index', 'content_id' => $this->identity), 'default', true) ?>';         
        $('feed_viewmore').style.display = 'none';
        $('feed_loading').style.display = '';
        
          var request = new Request.HTML({
          url : url,
          data : {
            format : 'html',
            'maxid' : next_id,
            'feedOnly' : true,
            'nolayout' : true,
            'subject' : subject_guid,
            'category_id': window.feedFilterCat,
            'subcategory_id': window.feedFilterSubCat
          },
          evalScripts : true,
          onSuccess : function(responseTree, responseElements, responseHTML, responseJavaScript) {
            Elements.from(responseHTML).inject($('activity-feed'));
            en4.core.runonce.trigger();
            Smoothbox.bind($('activity-feed'));
          }
        });
       request.send();
      }
      
      if( next_id > 0 && !endOfFeed ) {
        $('feed_viewmore').style.display = '';
        $('feed_loading').style.display = 'none';
        $('feed_viewmore_link').removeEvents('click').addEvent('click', function(event){
          event.stop();
          activityViewMore(next_id, subject_guid);
        });
      } else {
        $('feed_viewmore').style.display = 'none';
        $('feed_loading').style.display = 'none';
      }
      
    });
  </script>
<?php endif; ?>

<?php if( !empty($this->feedOnly) && empty($this->checkUpdate)): // Simple feed only for AJAX
  echo $this->activityLoop($this->activity, array(
    'action_id' => $this->action_id,
    'viewAllComments' => $this->viewAllComments,
    'viewAllLikes' => $this->viewAllLikes,
    'similarActivities' => $this->similarActivities,
    'getUpdate' => $this->getUpdate,
    'viewMaxPhoto' => $this->viewMaxPhoto
  ));
  return; // Do no render the rest of the script in this mode
endif; ?>

<?php if( !empty($this->checkUpdate) ): // if this is for the live update
  if ($this->activityCount)
  echo "<script type='text/javascript'>
          document.title = '($this->activityCount) ' + activityUpdateHandler.title;
          activityUpdateHandler.options.next_id = ".$this->firstid.";
        </script>

        <div class='tip'>
          <span>
            <a href='javascript:void(0);' onclick='javascript:activityUpdateHandler.getFeedUpdate(".$this->firstid.");$(\"feed-update\").empty();'>
              {$this->translate(array(
                  '%d new update is available - click this to show it.',
                  '%d new updates are available - click this to show them.',
                  $this->activityCount),
                $this->activityCount)}
            </a>
          </span>
        </div>";
  return; // Do no render the rest of the script in this mode
endif; ?>

<?php if( !empty($this->getUpdate) ): // if this is for the get live update ?>
   <script type="text/javascript">
     activityUpdateHandler.options.last_id = <?php echo sprintf('%d', $this->firstid) ?>;
   </script>
<?php endif; ?>

<?php if( $this->enableComposer ): ?>
  <div class="activity-post-container nolinks" id='sd_post_container'>
        <a href="javascript:void(0);" class="sd_close_composer" onclick="hidePostContainer(this);"><i class="fa fa-close"></i></a>            
        <form method="post" action="<?php echo $this->url(array('module' => 'activity', 'controller' => 'index', 'action' => 'post'), 'default', true) ?>" class="activity" enctype="application/x-www-form-urlencoded" id="activity-form">
          <div class="sd_poster_photo">
              <?php $photoUser = $this->viewer; ?>
              <?php if(!empty($this->subject)) { $photoUser = $this->subject; } ?>
              <?php echo $this->htmlLink($photoUser->getHref(),$this->itemPhoto($photoUser,'thumb.icon',$photoUser->getTitle())); ?>
          </div>
          <textarea id="activity_body" cols="1" rows="1" name="body" placeholder="<?php echo $this->escape($this->translate('Post Something...')) ?>"></textarea>
          <input type="hidden" name="return_url" value="<?php echo $this->url() ?>" />
          <?php if( $this->viewer() && $this->subject() && !$this->viewer()->isSelf($this->subject())): ?>
            <input type="hidden" name="subject" value="<?php echo $this->subject()->getGuid() ?>" />
          <?php endif; ?>
          <?php if( $this->formToken ): ?>
            <input type="hidden" name="token" id="token" value="<?php echo $this->formToken ?>" />
          <?php endif ?>
          <div id="compose-menu" class="compose-menu">
            <button id="compose-submit" type="submit"><?php echo $this->translate("Share") ?></button>            
          </div>
            <div class="compose_categories">
               <?php if(count($this->allCategories)): ?>
                    <select id='compose_category' name='category_id' onchange="showSubCategory(this);">
                        <option value=""><?php echo $this->translate("Categories"); ?></option>
                        <?php foreach($this->allCategories as $category): ?>
                            <option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                    <div class="sd_composer_subcategories" id="sd_composer_subcategories" style="display: inline-block;">
                    <?php foreach($this->allCategories as $category): ?>
                        <?php if(!empty($category['sub_categories'])): ?>
                            <select class="sd_feed_subcategories" style="display: none;" id="compose_subcategory<?php echo $category['id']; ?>" name='subcategory_id'>
                                <option value=""><?php echo $this->translate("Sub-Cat"); ?></option>
                                <?php foreach($category['sub_categories'] as $category): ?>
                                    <option value="<?php echo $category['id']; ?>"><?php echo $category['title']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>
                    <img class="sd_webstar_img" src="<?php echo $this->layout()->staticBaseUrl; ?>public/admin/w-star.png"/>
                <?php endif; ?>
            </div>
        </form>

        <script type="text/javascript">
          var composeInstance;
          en4.core.runonce.add(function() {
            // @todo integrate this into the composer
            if( true ) {
              composeInstance = new Composer('activity_body', {
                menuElement : 'compose-menu',
                baseHref : '<?php echo $this->baseUrl() ?>',
                lang : {
                  'Post Something...' : '<?php echo $this->string()->escapeJavascript($this->translate('Post Something...')) ?>'
                },
                submitCallBack : en4.activity.post
              });
            }
          });

        </script>

        <?php foreach( $this->composePartials as $partial ): ?>
          <?php echo $this->partial($partial[0], $partial[1], array(
            'composerType' => 'activity'
          )) ?>
        <?php endforeach; ?>

  </div>
<?php endif; ?>

<div class="sd_feed_header_seperator"></div>

<div class="sd_feed_filters" id="sd_feed_filters">
    <?php if(count($this->allCategories)): ?>
        <ul class="sd_feed_categories">
            <li class="active">
                <a href="javascript:void(0);" onclick="filterFeedCategory(this,'','');"><?php echo $this->translate("All"); ?></a>                
            </li>
        <?php foreach($this->allCategories as $key => $category): ?>
            <?php if( $key < 8 ): ?>
                <li class="<?php if(!empty($category['sub_categories'])){ echo 'has_subcategories';  } ?>">
                    <a href="javascript:void(0);" onclick="filterFeedCategory(this,'<?php echo $category['id']; ?>','');"><?php echo $category['title']; ?></a>
                </li>
            <?php endif;?>
        <?php endforeach; ?>
        
        <?php if (count($this->allCategories) > 8):?>
            <li class="tab_closed more_tab" onclick="moreTabSwitch($(this));">
                <div class="tab_pulldown_contents_wrapper">
                    <div class="tab_pulldown_contents">
                        <ul>
                            <?php foreach($this->allCategories as $key => $category): ?>
                                <?php if( $key >= 8 ): ?>
                                    <li class="<?php if(!empty($category['sub_categories'])){ echo 'has_subcategories';  } ?>">
                                        <a href="javascript:void(0);" onclick="filterFeedCategory(this,'<?php echo $category['id']; ?>','');"><?php echo $category['title']; ?></a>
                                    </li>
                                <?php endif;?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <a href="javascript:void(0);"><?php echo $this->translate('More +') ?><span></span></a>
            </li>
        <?php endif; ?>
        </ul>
    <?php endif; ?>
    <div class='sd_post_feed_button'>
        <button onclick="showPostContainer(this);"><?php echo $this->translate("Post"); ?></button>
    </div>
</div>

<?php if ($this->updateSettings && !$this->action_id): // wrap this code around a php if statement to check if there is live feed update turned on ?>
  <script type="text/javascript">
    var activityUpdateHandler;
    en4.core.runonce.add(function() {
      try {
          activityUpdateHandler = new ActivityUpdateHandler({
            'baseUrl' : en4.core.baseUrl,
            'basePath' : en4.core.basePath,
            'identity' : 4,
            'delay' : <?php echo $this->updateSettings;?>,
            'last_id': <?php echo sprintf('%d', $this->firstid) ?>,
            'subject_guid' : '<?php echo $this->subjectGuid ?>'
          });
          setTimeout("activityUpdateHandler.start()",1250);
          //activityUpdateHandler.start();
          window._activityUpdateHandler = activityUpdateHandler;
      } catch( e ) {
        //if( $type(console) ) console.log(e);
      }
    });
  </script>
<?php endif;?>

  <div class="tip" id="fail_msg" style="display: none;">
    <span>
      <?php $url = $this->url(array('module' => 'user', 'controller' => 'settings', 'action' => 'privacy'), 'default', true) ?>
      <?php echo $this->translate('The post was not added to the feed. Please check your %1$sprivacy settings%2$s.', '<a href="'.$url.'">', '</a>') ?>
    </span>
  </div>

<?php // If requesting a single action and it doesn't exist, show error ?>
<?php if( !$this->activity ): ?>
  <?php if( $this->action_id ): ?>
    <h2><?php echo $this->translate("Activity Item Not Found") ?></h2>
    <p>
      <?php echo $this->translate("The page you have attempted to access could not be found.") ?>
    </p>
  <?php return; else: ?>
    <div class="tip" id="no-feed-tip">
      <span>
        <?php echo $this->translate("Nothing has been posted here yet - be the first!") ?>
      </span>
    </div>
  <?php return; endif; ?>
<?php endif; ?>

<div id="feed-update"></div>

<?php echo $this->activityLoop($this->activity, array(
  'action_id' => $this->action_id,
  'viewAllComments' => $this->viewAllComments,
  'viewAllLikes' => $this->viewAllLikes,
  'similarActivities' => $this->similarActivities,
  'getUpdate' => $this->getUpdate,
  'viewMaxPhoto' => $this->viewMaxPhoto
)) ?>

<div class="feed_viewmore" id="feed_viewmore" style="display: none;">
  <?php echo $this->htmlLink('javascript:void(0);', $this->translate('View More'), array(
    'id' => 'feed_viewmore_link',
    'class' => 'buttonlink icon_viewmore'
  )) ?>
</div>

<div class="feed_viewmore" id="feed_loading" style="display: none;">
  <i class="fa-spinner fa-spin fa"></i>
  <?php echo $this->translate("Loading ...") ?>
</div>

      
<script type="text/javascript">
  en4.core.runonce.add(function() {
    var moreTabSwitch = window.moreTabSwitch = function(el) {
      el.toggleClass('tab_open');
      el.toggleClass('tab_closed');
    }
  });
</script>     