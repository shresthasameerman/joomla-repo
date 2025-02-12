<?php // no direct access
/**
* @copyright	Copyright (C) 2008-2022 CMSJunkie. All rights reserved.
*
* This program is free software: you can redistribute it and/or modify
* it under the terms of the GNU AFFERO GENERAL PUBLIC LICENSE as published by
* the Free Software Foundation
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
* See the GNU AFFERO GENERAL PUBLIC LICENSE for more details.
* You should have received a copy of the GNU AFFERO GENERAL PUBLIC LICENSE
* along with this program.  If not, see <https://www.gnu.org/licenses/agpl-3.0.en.html>.
*/

defined('_JEXEC') or die('Restricted access');
$hotel = $this->hotel;
$jinput = JFactory::getApplication()->input;
$this->currency = JHotelUtil::getCurrencyDisplay($this->userData->currency,$hotel->hotel_currency,$hotel->currency_symbol);
$divSize = (count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS && $this->appSettings->enable_hotel_rating==1)?8:12;
$startDate = $this->userData->start_date;
$startDate = JHotelUtil::convertToFormat($startDate);
JText::script('LNG_PLEASE_SELECT_AT_LEAST_ONE_ROOM');
JText::script('LNG_AVAILABILITY');
JText::script('LNG_ROOM_ALREADY_BOOKED');
JText::script('LNG_WHOLEHOUSE_ALREADY_BOOKED');
$hasDescription = false;
reset($this->offers);
$first_key = key($this->offers);

HotelService::generateHotelMetaData($hotel);
?>

<div class="jhp-container container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="jhotel-item">
                <div class="row">
                    <div class="col-12">
                        <div class="image-gallery img-wrap">
                            <div id="gallery" style="display:none;">
                                <?php if (!empty($this->hotel->pictures)) {?>
                                    <?php foreach($this->hotel->pictures as $picture) {
                                        if(!empty($picture->hotel_picture_info))
                                            $hasDescription = true; ?>

                                        <img alt="<?php echo $picture->hotel_picture_info ?>" src="<?php echo JHP_PICTURES_PATH.$picture->hotel_picture_path ?>"
                                             data-image="<?php echo JHP_PICTURES_PATH.$picture->hotel_picture_path ?>"
                                             data-description="<?php echo $picture->hotel_picture_info ?>">

                                    <?php } ?>
                                <?php } else { ?>
                                    <?php echo JText::_("LNG_NO_IMAGES"); ?>
                                <?php } ?>
                            </div>

                            <?php
                            if($hotel->display_unavailability_message==1){
                                $nextAvailableDate = BookingService::getNextAvailableDate($this->hotel->hotel_id,$startDate);
                                if(date('m',strtotime($nextAvailableDate))>date('m')){
                                    ?>
                                    <div class="alert_message div_alert_roomrates">
                                        <?php echo JText::_('LNG_UNAVAILABILITY_MESSAGE');
                                        echo sprintf(JText::_('LNG_NEXT_AVAILABILITY_MESSAGE'),JHotelUtil::getDateGeneralFormat($nextAvailableDate));
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                                </div>
                            </div>
                            <div class="sellingPoints row m-0 p-0 mt-2">
                                <?php echo $this->hotel->hotel_selling_points;?>
                            </div>
                            <div class="row">
                                <div class="col-md-<?php echo $divSize;?> order-2 order-md-1">
                                    <div class="jhotel-result-desc">
                                        <?php if($this->appSettings->enable_hotel_description==1){?>
                                            <p>
                                                <?php
                                                    $hotelDescription = $this->hotel->hotel_description;
                                                    $preDescription = JHotelUtil::truncate(($hotelDescription), 490, '', false);
                                                    $replaceCount = 0;
                                                    $preDescription = preg_replace('%<p(.*?)>|</p>%s','',$preDescription,-1,$replaceCount);
                                                    echo "<div stye='display:inline' id='hotelDescriptionShort'>".$preDescription."<span id='dots'>...</span>"." <a href='javascript:void(0)' onclick='jhpHotel.toggleHotelDescription()'>".JText::_('LNG_READ_MORE')."</a></div>";
                                                    echo "<div class='d-none' id='hotelDescriptionExtended'>".$hotelDescription."<a href='javascript:void(0)' onclick='jhpHotel.toggleHotelDescription()'>".JText::_('LNG_LESS')."</a></div>";
                                                ?>
                                            </p>
                                        <?php }?>
                                    </div>
                                </div>
                                <?php if(count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS && $this->appSettings->enable_hotel_rating==1){?>
                                    <div class="col-md-4 order-1 order-md-2 ">
                                            <?php if(count($hotel->reviews) >= 0)
                                                echo ReviewsService::getHotelRatingSummaryHtml($hotel,$hotel->reviews[0]->totalReviewsCount);
                                            ?>
                                    </div>
                                 <?php }?>
                            </div>
                            <div class="hotel-reviews-hidden d-none">
                                <?php
                                if(count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS && $this->appSettings->enable_hotel_rating==1){
                                    echo $this->loadTemplate('hotelreviews');
                                }
                                ?>
                            </div>
                            <?php echo $this->loadTemplate('changesearch');?>
                            <!-- main form to reserve a room -->
                            <form autocomplete='off' action="<?php echo JRoute::_('index.php?option=com_jhotelreservation') ?>" method="post" name="userForm" id="userForm" >
                                <div id="boxes" class="hotel_reservation">
                                    <div id='div_room'>
                                        <?php
                                        if(!$this->hotel->is_available)
                                            echo $this->loadTemplate("unavailable");
                                        else{
                                            $viewType = $this->appSettings->platform_type == 1? "roomoffers_": "offerrooms_";
                                            //display rates
                                            echo $this->loadTemplate($viewType.$this->appSettings->room_view);
                                        }
                                        ?>
                                    </div>
                                </div>
                                <input type="hidden" name="task" 	 						id="task"	 				value="hotel.reserveRoom"  />
                                <input type="hidden" name="hotel_id"						id="hotel_id"	 			value="<?php echo $this->state->get("hotel.id") ?>"	/>
                                <input type="hidden" name="tmp"								id="tmp" 					value="<?php echo $jinput->get('tmp') ?>" />
                                <input type="hidden" name="tip_oper" 						id="tip_oper" 				value="<?php echo $jinput->get( 'tip_oper') ?>" />
                                <input type="hidden" name="reservedItems" 					id="reservedItems"          value="<?php echo is_array($this->userData->reservedItems)?implode("||",$this->userData->reservedItems):""?>" />
                                <input type="hidden" name="reservedItemsNames" 				id="reservedItemsNames"     value="<?php echo !empty($this->userData->reservedItemsNames) && is_array($this->userData->reservedItemsNames)?implode("||",$this->userData->reservedItemsNames):""?>" />
                                <input type="hidden" name="price"  							id="price"          		value="" />
                                <input type="hidden" name="priceType"  						id="priceType"          	value="" />
                            </form>
                            <?php
                            //load hotel amenities & info
                            if($this->appSettings->enable_hotel_information==1) {
                                echo $this->loadTemplate('information');
                            }

                            //load facilities
                            if($this->appSettings->enable_hotel_facilities==1){
                                echo $this->loadTemplate('hotelfacilities');
                            }

                            if (count($hotel->reviews) >= MINIMUM_HOTEL_REVIEWS && $this->appSettings->enable_hotel_rating == 1) {
                                echo $this->loadTemplate('hotelreviews');
                            }

                            //load points of interest
                            echo $this->loadTemplate('poi_grid');

                            //load hotel map
                            if($this->appSettings->enable_hotel_map==1) {
                                echo $this->loadTemplate('hotelmap');
                            }

                            if(!$this->hotel->availability_contact) {
                                echo $this->loadTemplate('roomselection');
                            }

                            if($this->hotel->availability_contact) {
                                echo $this->loadTemplate('availabilityrequest');
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
	<?php if(strlen($jinput->get('divIdentifier',''))>0){?>
        const divIdentifier = "<?php echo $jinput->get('divIdentifier', '')?>";
        jQuery(document).ready(function(){
            jhpHotel.toggleDiv(divIdentifier);
            setTimeout(function () {
                if(jQuery("#"+divIdentifier).length){
                    jQuery("#"+divIdentifier).get(0).scrollIntoView();
                }
            },1000)
        });
	<?php }?>

    jQuery(document).ready(function() {
        hotelId = '<?php echo $this->hotel->hotel_id; ?>';
        currentRoom = '<?php echo count($this->userData->reservedItems) +1 ?>';
        jhpHotel.scrollSelectedItems();
        <?php if($this->appSettings->room_view == 1){ ?>
            jhpHotel.showRoomCalendars(hotelId,currentRoom);
        <?php } else { ?>
            jhpHotel.showCombinedRoomCalendars(hotelId,currentRoom);
        <?php }?>
        <?php if(!empty($this->userData->reservedItems)){ ?>
            jQuery('.reservation-selection').removeClass("d-none");
        <?php }?>

        jhpHotel.bindRoomControls();
        jhpHotel.checkReservationPendingPayments();
        jhpHotel.displaySellingPointsMarkers();
    });

    var  defaultStartDate = "<?php echo isset($module)?$module->params["start-date"]: ''?>";
    var defaultEndDate = "<?php echo isset($module)?$module->params["end-date"]: ''?>";

    var dateFormat = '<?php echo  $this->appSettings->dateFormat; ?>';
    var language = '<?php echo JHotelUtil::getLanguageTag();?>';
    var formatToDisplay =  jhpUtils.calendarFormat(dateFormat);

    jQuery(document).ready(function(){
        jQuery('body').removeClass("homepage");
        jQuery('body').addClass("subpage");
        <?php if(!empty($this->offers[$first_key]->hasPrepaidOffer)){?>
            jQuery('.prepaid-info').css("position","absolute");
            jQuery('.prepaid-info').css("top",jQuery(".offers").offset().top+100);
        <?php }?>

        jQuery("img.image-prv").hover(function(e){
            jQuery("#image-preview").attr('src', this.src);
        });
        jQuery.fn.datepicker.defaults.language = language;
        jQuery.fn.datepicker.defaults.format = formatToDisplay;

        jQuery("#jhotelreservation_datas2").datepicker({
            autoclose:true,
            language: language,
            format: formatToDisplay
        });

        jQuery("#jhotelreservation_datae2").datepicker({
            autoclose:true,
            language: language,
            format: formatToDisplay
        });

        jQuery("#jhotelreservation_datas2_img").click(function(){
            jQuery("#jhotelreservation_datas2").focus();
        });

        jQuery("#jhotelreservation_datae2_img").click(function(){
            jQuery("#jhotelreservation_datae2").focus();
        });

        <?php if($this->appSettings->enable_hotel_gallery == 1){ ?>
        var sWidth = jQuery(window).width();
        var galleryHeight = 450;
        if(sWidth<780)
            galleryHeight = 250;

        jQuery("#gallery").unitegallery({
            gallery_theme: "compact",
            gallery_height: galleryHeight,
            gallery_width:"100%",
            slider_enable_arrows: true,
            <?php if (count($this->hotel->pictures) <= 1){ ?>
            theme_hide_panel_under_width: 4000,
            <?php } ?>
            theme_enable_text_panel: <?php if ($hasDescription) echo 'true'; else echo 'false'; ?>,
            theme_panel_position: "right",
            slider_control_zoom: false,
            slider_scale_mode: "fill",
            theme_hide_panel_under_width: 480,
            thumb_width:160,								//thumb width
            thumb_height:150,							//thumb height
            thumb_fixed_size:true,
            gallery_autoplay: true,
            strippanel_padding_top:0,					//space from top of the panel
            strippanel_padding_bottom:0,				//space from bottom of the panel
            strippanel_padding_left:  10,				//space from left of the panel
            strippanel_padding_right: 0,				//space from right of the panel
            strippanel_enable_buttons: false,			//enable buttons from the sides of the panel
            strip_scroll_to_thumb_duration: 740,
            slider_textpanel_enable_title: false,			//enable the title text
            slider_textpanel_enable_description: false,		//enable the description text
            slider_enable_play_button: false,			 //true,false - enable play / pause button onslider element
            slider_enable_fullscreen_button: false,		 //true,false - enable fullscreen button onslider element
            slider_enable_zoom_panel: false,				 //true,false - enable the zoom buttons, works together with zoom control.
            slider_enable_text_panel: false			 //true,false - enable the text panel
        });
        <?php }?>

        //offers and rates gallery
        jQuery('.thumbs a').touchTouch();
    });
</script>


<?php if($this->appSettings->enable_google_tag_manager){?>
	<script>
        dataLayer = [];
        dataLayer.push({
          'event': 'detail',
          'ecommerce': {
            'detail': {
              'products': [{
                'name': '<?php echo $this->hotel->hotel_name; ?>',
                'id': '<?php echo $this->hotel->hotel_id; ?>',
                'price': '<?php echo $this->hotel->min_room_price; ?>',
                'brand': '<?php echo $this->hotel->hotel_county; ?>',
                'category': '<?php echo !empty($this->offers[0]->offer_name)?$this->offers[0]->offer_name:$this->rooms[0]->room_name?>',
                'variant': '<?php echo $this->userData->voucher; ?>',
                'quantity':'<?php echo $this->userData->total_adults+$this->userData->total_children; ?>'
               }]
             }
           }
        });
	</script>

<?php
	echo JHotelUtil::getTagManagerScript();
}
?>

