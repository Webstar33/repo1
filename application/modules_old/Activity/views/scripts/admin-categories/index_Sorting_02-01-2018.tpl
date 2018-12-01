<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @author     Stars Developer
 */
?>

<h2><?php echo $this->translate("Activity Plugin") ?></h2>

  <div class='clear'>
    <div class='settings'>
    <form class="global_form">
      <div>
        <h3> <?php echo $this->translate("Activity Feed Categories") ?> </h3>
        <p class="description">
          <?php echo $this->translate("ACTIVITY_VIEWS_SCRIPTS_ADMINSETTINGS_CATEGORIES_DESCRIPTION") ?>
        </p>
        <?php if(!empty($this->category)): ?>
            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'index' 
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
          <tbody>
            <?php foreach ($this->categories as $category): ?>
                    <tr>
                      <td><?php echo $category->title?></td>
                      <td>
                        <?php if(empty($this->id)): ?>
                            <?php echo $this->htmlLink(array('route' => 'admin_default', 'module' => 'activity', 'controller' => 'categories', 'action' => 'index', 'id' => $category->category_id), $this->translate("Sub Categories"), array(
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
