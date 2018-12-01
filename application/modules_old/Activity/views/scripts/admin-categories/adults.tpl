<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */
?>

<script type="text/javascript">

  var SortablesInstance;

  window.addEvent('domready', function() {
    $$('.item_label').addEvents({
      mouseover: showPreview,
      mouseout: showPreview
    });
  });

  var showPreview = function(event) {
    try {
      element = $(event.target);
      element = element.getParent('.admin_menus_item').getElement('.item_url');
      if( event.type == 'mouseover' ) {
        element.setStyle('display', 'block');
      } else if( event.type == 'mouseout' ) {
        element.setStyle('display', 'none');
      }
    } catch( e ) {
      //alert(e);
    }
  }


  window.addEvent('load', function() {
    SortablesInstance = new Sortables('category_list', {
      clone: true,
      constrain: false,
      handle: '.item_label',
      onComplete: function(e) {
        reorder(e);
      }
    });
  });

 var reorder = function(e) {
     var menuitems = $(e).getParent("#category_list").getChildren();
     var ordering = {};
     var categories = [];
     var i = 1;
     for (var menuitem in menuitems)
     {
         var element = $(menuitems[menuitem]);
         if(element){
            var child_id = element.get("data-id");
            categories[child_id] = i;
            i++;
        }
     }
    ordering['format'] = 'json';
    ordering['categories'] = categories;
    
    // Send request
    var url = '<?php echo $this->url(array('action' => 'order')) ?>';
    var request = new Request.JSON({
      'url' : url,
      'method' : 'POST',
      'data' : ordering,
      onSuccess : function(responseJSON) {
      }
    });

    request.send();
  }

  function ignoreDrag()
  {
    event.stopPropagation();
    return false;
  }

</script>
<style type="text/css">
#category_list {
    position: relative;
}
#category_list tr {
    position: relative;
}
</style>
<h2><?php echo $this->translate("Activity Plugin") ?></h2>

<div class='tabs'>
  <ul class="navigation">
    <li class="">
        <a class="home_page_category" href="/admin/activity/categories"><?php echo $this->translate('Home Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="music_page_category" href="/admin/activity/categories/music"><?php echo $this->translate('Music Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/photo"><?php echo $this->translate('Photo Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/sports"><?php echo $this->translate('Sports Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/kids"><?php echo $this->translate('Kids Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/women"><?php echo $this->translate('Women Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/school"><?php echo $this->translate('School Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/gaming"><?php echo $this->translate('Game Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/movies"><?php echo $this->translate('Movies Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/fashion"><?php echo $this->translate('Fashion Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/spiritual"><?php echo $this->translate('Spiritual Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/sciandtech"><?php echo $this->translate('Sci & Tech Page Categories'); ?></a>
    </li>
	<li class="active">
        <a class="home_page_category" href="/admin/activity/categories/adults"><?php echo $this->translate('18 & Over Page Categories'); ?></a>
    </li>
    <li class="">
        <a class="home_page_category" href="/admin/activity/categories/love"><?php echo $this->translate('Love Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/food"><?php echo $this->translate('Food Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/people"><?php echo $this->translate('People Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/health"><?php echo $this->translate('Health Page Categories'); ?></a>
    </li>
	<li class="">
        <a class="home_page_category" href="/admin/activity/categories/arts"><?php echo $this->translate('Arts Page Categories'); ?></a>
    </li>
  </ul>
  </div>
  
  <div class='clear'>
    <div class='settings'>
    <form class="global_form">
      <div>
        <h3> <?php echo $this->translate("18 & Over Activity Feed Categories") ?> </h3>
        <p class="description">
          <?php echo $this->translate("ACTIVITY_VIEWS_SCRIPTS_ADMINSETTINGS_CATEGORIES_DESCRIPTION") ?>
        </p>
        <?php if(!empty($this->category)): ?>
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'adults' 
            ), $this->translate("Back to")." ".$this->category->title, array(
              'class' => '',
            )) ?>
            <br/><br/>
        <?php endif; ?>
        
        <?php if(count($this->categories)>0):?>

         <table class='admin_table'>
          <thead>
            <tr>
              <th><?php echo $this->translate("Category Name") ?></th>
              <th><?php echo $this->translate("Options") ?></th>
            </tr>

          </thead>
          <tbody class="category_list" id="category_list">
            <?php foreach ($this->categories as $category): ?>
                <tr class="item_label" data-id="<?php echo $category->category_id; ?>">                  
                  <td>
                      <a class="sd_category_sort" style="cursor: move;margin-right: 10px;font-size:16px;" href="javascript:void(0);"><i class="fa fa-arrows"></i></a>
                      <?php echo $category->title?>
                  </td>
                  <td>
                    <?php if(empty($this->id)): ?>
                        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'adults', 'id' => $category->category_id), $this->translate("Sub Categories"), array(
                          'class' => '',
                        )) ?>
                        <span>(<?php echo $this->table->getSubCategoriesCount($category->category_id); ?>)</span>
                        |
                    <?php endif; ?>
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'edit', 'id' => $category->category_id), $this->translate("Edit"), array(
                      'class' => 'smoothbox',
                    )) ?>
                    |
                    <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'delete', 'id' => $category->category_id), $this->translate("Delete"), array(
                      'class' => 'smoothbox',
                    )) ?>

                  </td>
                </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php else:?>
      <br/>
      <div class="tip">
        <span>
            <?php if(empty($this->id)): ?>
                <?php echo $this->translate("There are currently no categories.") ?>
            <?php else: ?>
                <?php echo $this->translate("There are currently no sub categories.") ?>
            <?php endif; ?>
            
        </span>
      </div>
      <?php endif;?>
        <br/>

        <?php if(empty($this->id)): ?>
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'add'), $this->translate('Add New Category'), array(
            'class' => 'smoothbox buttonlink',
            'style' => 'background-image: url(' . $this->layout()->staticBaseUrl . 'application/modules/Core/externals/images/admin/new_category.png);')) ?>
        <?php else: ?>
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'add', 'id' => $this->id), $this->translate('Add Sub Category'), array(
            'class' => 'smoothbox buttonlink',
            'style' => 'background-image: url(' . $this->layout()->staticBaseUrl . 'application/modules/Core/externals/images/admin/new_category.png);')) ?>
        <?php endif; ?>
    </div>
    </form>
    </div>
  </div>
