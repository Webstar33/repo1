<?php

/**
 * SocialEngine
 *
 * @category   Application_Extensions
 * @package    Widget
 * @author     Stars Developer
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
        var autocompleteSECreateLocation = new google.maps.places.Autocomplete(document.getElementById('location'));
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