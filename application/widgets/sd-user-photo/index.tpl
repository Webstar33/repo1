
<?php ?>

<style type="text/css">
.layout_sd_user_photo>div {
    text-align: center;
}
.sd-user-name a {
    text-decoration: underline;
    font-size: 14px;
    color: #1BC1D6;
}
.sd-user-rating {
    margin-bottom: 5px;
}

span.sd_list_rating_star>span {
    width: 14px;
    display: inline-block;
}

.sd-user-rating .sd_rating_star:before{
    font-family: fontawesome;
    font-size: 18px;
    color: #000;
}

.sd-user-rating .sd_star_y:before{
    content: "\f005";
}

.sd-user-rating .sd_star_disabled:before{
    content: "\f006";
}

.sd-user-icons-lists>li {
    display: inline-block;
    margin: 5px;
}

.sd-user-icons-lists li a:before{
    font-family: fontawesome;
    font-size: 20px;
    color: #000;
}

.sd-world-icon a:before{
    content: "\f0ac";
}

.sd-male-female-icon a:before{
    content: "\f183";
}
.sd-male-female-icon a:after{
    content: "\f182";
    font-family: fontawesome;
    font-size: 20px;
    color: #000;
}

.sd-down-icon a:before{
    content: "\f019";
}
.sd-star-icon a:before{
    content: "\f123";
}
.sd-message-icon a:before{
    content: "\f0e0";
}
.sd-menu-icon a:before{
    content: "\f142";
    vertical-align: middle;
}
.sd-user-icons-lists{
    margin-top: 8px;
}
</style>


<div class="sd-user-rating">
    <span class="sd_list_rating_star">
        <span class="sd_rating_star sd_star_y"></span>
        <span class="sd_rating_star sd_star_disabled"></span>
        <span class="sd_rating_star sd_star_disabled"></span>
        <span class="sd_rating_star sd_star_disabled"></span>
        <span class="sd_rating_star sd_star_disabled"></span>
    </span>
</div>
<div class="sd-user-photo">
  <?php echo $this->htmlLink($this->viewer()->getHref(), $this->itemPhoto($this->viewer(), 'thumb.profile')) ?>
</div>
<div class="sd-user-name">
    <?php echo $this->htmlLink($this->viewer()->getHref(), $this->viewer()->getTitle());   ?> 
</div>
<div class="sd-user-icons">
    
 <!--- For Mini Menu added by Anil -->
	
	<?php echo $this->content()->renderWidget('core.menu-mini'); ?>
	
 <!--- For Mini Menu added by Anil -->
 
</div>
