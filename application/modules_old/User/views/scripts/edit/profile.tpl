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
<?php echo $this->form->render($this) ?>


<script type="text/javascript">
    function suggestLocation(){
        if($("1_1_15")){
            new Element("input",{
                type: 'hidden',
                name: 'locationParams',
                id: 'locationParams'
            }).inject($("1_1_15"),"after");
            $("1_1_15").set("id","location");
        }    
        if(document.getElementById('location')) {
              var options = {
                  types: ['geocode']
              };  
            var autocompleteSECreateLocation = new google.maps.places.Autocomplete(document.getElementById('location'),options);
            <?php include APPLICATION_PATH . '/application/modules/Seaocore/views/scripts/location.tpl'; ?>
        }
    }
    en4.core.runonce.add(function(){
        console.log(<?php echo $apiKey; ?>);
        suggestLocation();
    });
</script>
<?php $apiKey = Engine_Api::_()->seaocore()->getGoogleMapApiKey();
    $this->headScript()->appendFile("https://maps.googleapis.com/maps/api/js?libraries=places&key=$apiKey")
?>