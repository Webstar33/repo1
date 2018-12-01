<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    User
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: profile.tpl 9984 2013-03-20 00:00:04Z john $
 * @author     John
 */
?>

<?php
    if (APPLICATION_ENV == 'production')
        $this->headScript()
            ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.min.js');
    else
        $this->headScript()
            ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Observer.js')
            ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.js')
            ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Local.js')
            ->appendFile($this->layout()->staticBaseUrl . 'externals/autocompleter/Autocompleter.Request.js');
?>


<div class="headline">
    <h2>
        <?php if ($this->viewer->isSelf($this->user)):?>
            <?php echo $this->translate('Edit My Profile');?>
        <?php else:?>
            <?php echo $this->translate('%1$s\'s Profile', $this->htmlLink($this->user->getHref(), $this->user->getTitle()));?>
        <?php endif;?>
    </h2>
    <div class="tabs">
        <?php
            // Render the menu
            echo $this->navigation()
                ->menu()
                ->setContainer($this->navigation)
                ->render();
        ?>
    </div>
</div>

<?php
    /* Include the common user-end field switching javascript */
    echo $this->partial('_jsSwitch.tpl', 'fields', array(
        'topLevelId' => (int) @$this->topLevelId,
        'topLevelValue' => (int) @$this->topLevelValue
    ))
?>

<?php
    $this->headTranslate(array(
        'Everyone', 'All Members', 'Friends', 'Only Me',
    ));
?>
<script type="text/javascript">
    window.addEvent('domready', function() {
        en4.user.buildFieldPrivacySelector($$('.global_form *[data-field-id]'));
    });
</script>

<style>
.category_list td{
    border: 1px solid black;
}
.movable_class{
    background-color:#83c0ff !important;
}
</style>

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
        // console.log(e);
      //alert(e);
    }
  }


  window.addEvent('load', function() {
    SortablesInstance = new Sortables('category_list', {
        clone: true,
        constrain: false,
        handle: '.item_label',
        onStart:function(element, clone) {
            $(element).addClass('movable_class');
        },
        onComplete: function(e) {
            $(e).removeClass('movable_class');
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
<style>
#categories_table {
    font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
    border-collapse: collapse;
    width: 100%;
}

#categories_table td, #categories_table th {
    border: 1px solid #ddd;
    padding: 8px;
}

#categories_table tr:nth-child(even){background-color: #f2f2f2;}

#categories_table tr:hover {background-color: #ddd;}

#categories_table th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: left;
    background-color: #4CAF50;
    color: white;
}
.global_category_table_div{
    width: 50%;
    margin-left: 17% !important;;
}
</style>
<div class="form-elements">
    <div class='clear'>
        <div class='settings'>
            <form class="global_form">
                <div class="global_category_table_div">
                    <h3> <?php echo $this->translate("Activity Feed Categories") ?> </h3>
                    <p class="description">
                      <?php echo $this->translate("Re-order your feed categories") ?>
                    </p>
                    <?php if(!empty($this->category)): ?>
                        <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'index' 
                        ), $this->translate("Back to")." ".$this->category->title, array(
                          'class' => '',
                        )) ?>
                        <br/><br/>
                    <?php endif; ?>
                    <div id="1_1_3-wrapper" class="form-wrapper">
                        <?php if(count($this->categories)>0):?>

                            <table class='admin_table' id="categories_table">
                                <thead>
                                    <tr>
                                      <th><?php echo $this->translate("Category Name") ?></th>
                                    </tr>

                                </thead>
                                <tbody class="category_list" id="category_list">
                                    <?php foreach ($this->categories as $category): ?>
                                        <tr class="item_label" data-id="<?php echo (is_array($category))?$category['category_id']:$category->category_id; ?>">                  
                                          <td>
                                              <a class="sd_category_sort" style="cursor: move;margin-right: 10px;font-size:16px;" href="javascript:void(0);"><i class="fa fa-arrows"></i></a>
                                              <?php echo (is_array($category))?$category['title']:$category->title?>
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
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>