<?php
/**
 * SocialEngine
 *
 * @category   Application_Core
 * @package    Activity
 * @copyright  Copyright 2006-2010 Webligo Developments
 * @license    http://www.socialengine.com/license/
 * @version    $Id: pulldown.tpl 9747 2012-07-26 02:08:08Z john $
 * @author     John
 */
?>

<?php foreach( $this->notifications as $notification ): ?>
	<?php  $is_custom = ($notification->type == 'acm_feedupdated' )? true : false ; 
		$Channel_id = 0;
		if($is_custom){
			$Channel_id = @explode('Channel', $notification->__toString())[1];
			if(empty($Channel_id) || !$Channel_id = intval($Channel_id)){
				$Channel_id = 0;
			}
		}
	?>
  <li<?php if( !$notification->read ): ?> class="notifications_unread"<?php endif; ?> value="<?php echo $notification->getIdentity();?>" <?php echo ($is_custom )? 'data-channel_id='.$Channel_id:''; ?>>
    <span class="notification_item_general notification_type_<?php echo $notification->type ?>">
      <?php echo $notification->__toString() ?>
    </span>
  </li>
<?php endforeach; ?>