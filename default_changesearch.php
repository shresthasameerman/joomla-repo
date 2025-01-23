<?php

//create dates & default values
$startDate = $this->userData->start_date;
$endDate = $this->userData->end_date;
$startDate = JHotelUtil::convertToFormat($startDate);
$endDate = JHotelUtil::convertToFormat($endDate);
$nrNights = JHotelUtil::getNumberOfDays($startDate,$endDate);
$jinput = JFactory::getApplication()->input;
$voucher = $jinput->get('voucher','');
if(strpos($_SERVER['HTTP_REFERER'],"acties")>0) {
    $voucher = $this->userData->voucher;
}
?>
<div class="row clear changeSearch mt-4 mb-4 ">
    <div class="col-md-5 mt-1">
	<?php
		echo "<b id='dateSelection'>".JHotelUtil::getDateGeneralFormat($this->userData->start_date)."</b> : ".JText::_('LNG_NUMBER_OF_NIGHTS').":".$nrNights.", ".JText::_('LNG_ADULTS').":".$this->userData->total_adults;
	?>
    </div>
    <div class="col-md-3 d-none" >
        <button onclick="jQuery('.reservation-details-holder ').toggleClass('d-none')" class="btn btn-secondary" name="check-button" value="Check dates" type="button">
            <span><i class="la la-search" tooltip="<?php echo JText::_('LNG_CHANGE_SEARCH')?>"> <?php echo JText::_('LNG_CHANGE_SEARCH')?></i></span>
        </button>
    </div>
</div>

<div class="reservation-details-holder mx-0 px-0 container-fluid clear ">
	<form action="<?php echo JRoute::_('index.php?option=com_jhotelreservation&view=hotel'.JHotelUtil::getItemIdS()) ?>" method="post" name="searchForm" id="searchForm">
		<input type='hidden' name='resetSearch' id='resetSearch' value='true'>
		<input type='hidden' name='option' value='com_jhotelreservation'>
		<input type='hidden' name='task' id="task" value='hotel.changeSearch'>
		<input type="hidden" name="hotel_id" id="hotel_id" value="<?php echo $this->hotel->hotel_id ?>" />
		<input type="hidden" name="user_property" id="user_property" value="<?php echo $this->hotel->hotel_id ?>" />
		<input type='hidden' name='year_start' value=''>
		<input type='hidden' name='month_start' value=''>
		<input type='hidden' name='day_start' value=''>
		<input type='hidden' name='year_end' value=''>
		<input type='hidden' name='month_end' value=''>
		<input type='hidden' name='day_end' value=''>
		<input type='hidden' name='rooms' value=''>
		<input type='hidden' name='guest_adult' value=''>
		<input type='hidden' name='guest_child' value=''>
		<input type='hidden' name='user_currency' value=''>
        <input type='hidden' name='divIdentifier' id='divIdentifier' value=''>

		<?php
			if(isset($this->userData->roomGuests )){
				foreach($this->userData->roomGuests as $guestPerRoom){?>
					<!-- <input class="room-search" type="hidden" name='room-guests[]' value='<?php echo $guestPerRoom?>'/> -->
				<?php }
			}
			if(isset($this->userData->roomGuestsChildren )){
				foreach($this->userData->roomGuestsChildren as $guestPerRoomC){?>
						<input class="room-search" type="hidden" name='room-guests-children[]' value='<?php echo $guestPerRoomC?>'/>
					<?php }
				}
			if(isset($this->userData->excursions ) && is_array($this->userData->excursions) && count($this->userData->excursions)>0){
				foreach($this->userData->excursions as $excursion){?>
					<input class="excursions" type="hidden" name='excursions[]' value='<?php echo $excursion;?>' />
				<?php }
				}
			if(isset($this->userData->roomChildrenAges ) && is_array($this->userData->roomChildrenAges) && count($this->userData->roomChildrenAges)>0){
				foreach($this->userData->roomChildrenAges as $childAges){
					foreach($childAges as $childAge){
					?>
					<input class="jhotelreservation_child_age" type="hidden" name='roomChildrenAges[]' value='<?php echo $childAge;?>' />
				<?php
					 }
				}
			}

		?>
		<div class="reservation-details mx-0 px-0 col-12" >
			<div class="reservation-detail">
                <div class="calendarHolder">
                    <label for="jhotelreservation_datas2"><?php echo JText::_('LNG_ARIVAL')?></label>
                    <input class="form-control cursor-pointer" data-provide="datepicker"
                           id="jhotelreservation_datas2"
                           name="jhotelreservation_datas"
                           type="text"
                           value="<?php echo $startDate; ?>"
                           onchange="if(!jhpSearch.checkStartDate(this.value, defaultStartDate,defaultEndDate))return false;jhpSearch.setDepartureDate('jhotelreservation_datae2',this.value,dateFormat);">
                    <i id="jhotelreservation_datas2_img" class="hotelCalendarImage la la-calendar"></i>
                </div>
            </div>
			<div class="reservation-detail ">
				<div class="calendarHolder">
                    <label for="jhotelreservation_datae2"><?php echo JText::_('LNG_DEPARTURE')?></label>
                    <input class="form-control cursor-pointer"
                           data-provide="datepicker"
                           type="text"
                           name="jhotelreservation_datae"
                           value="<?php echo $endDate; ?>"
                           onchange="jhpSearch.checkEndDate(this.value,defaultStartDate,defaultEndDate)"
                           id="jhotelreservation_datae2">
                    <i id="jhotelreservation_datae2_img" class="hotelCalendarImage la la-calendar"></i>
                </div>
			</div>
			<div class="reservation-detail">
				<label for=""><?php echo JText::_('LNG_ADULTS_19')?></label>
				<div class="styled-select form-control">
					<select name='jhotelreservation_guest_adult' id='jhotelreservation_guest_adult'	class = 'select_hotelreservation'>
						<?php
						$i_min = 1;
						$i_max = 12;

						$jhotelreservation_adults = $this->userData->total_adults;

						for($i=$i_min; $i<=$i_max; $i++)
						{
						?>
						<option value='<?php echo $i?>'  <?php echo $jhotelreservation_adults==$i ? " selected " : ""?>><?php echo $i?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>

			<div class="reservation-detail " style="<?php echo $this->appSettings->show_children!=0 ? "":"display:none" ?>">
				<label for=""><?php echo JText::_('LNG_CHILDREN_0_18')?></label>
				<div class="styled-select form-control">
					<select name='jhotelreservation_guest_child' id='jhotelreservation_guest_child'
						class		= 'select_hotelreservation' onchange='jhpHotel.showChildrenAgesHotel(this.value)'
					>
						<?php
						$i_min = 0;
						$i_max = 10;
						$jhotelreservation_children = $this->userData->total_children;

						for($i=$i_min; $i<=$i_max; $i++)
						{
						?>
						<option <?php echo $jhotelreservation_children==$i ? " selected " : ""?> value='<?php echo $i?>'  ><?php echo $i?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>

			<?php if($this->appSettings->enable_children_categories!=0){?>
			<div class="reservation-detail">
				<label id="labelText" style="display:<?php echo !empty($this->userData->jhotelreservation_child_ages)?'block':'none';?>;"><?php echo JText::_('LNG_CHILDREN_AGE_SHORT',true)?></label>
				<div id="childrenAgesHotel">
					<?php
					if(!empty($this->userData->jhotelreservation_child_ages)){
						$childrenAgesArray = $this->userData->jhotelreservation_child_ages;

						foreach($childrenAgesArray as $childAge){
					?>
						 <div class="styled-select form-control mr-2 fixedWidth">
							 <select id="jhotelreservation_child_ages[]"  name="jhotelreservation_child_ages[]">
							 <?php for ($j = 0; $j <= HOTEL_MAX_CHILDREN_AGE; $j++){?>
						        <option value="<?php echo $j?>"  <?php echo intVal($childAge)==$j ? "selected" : ""?>><?php echo $j?></option>
						     <?php }?>
							</select>
						 </div>
					<?php }

					}
					?>
				</div>
			</div>
			<?php } ?>

            <?php if ($this->appSettings->is_enable_offers){?>
                <div class="reservation-detail voucher">
                    <label for=""><?php echo JText::_('LNG_VOUCHER')?></label>
                    <input type="text" class="form-control" value="<?php echo $voucher ?>" name="voucher" id="voucher"/>
                </div>
            <?php }?>
            <div class="reservation-detail">
                <label for="">&nbsp;</label>
                <button class="btn btn-dark" onclick="jhpSearch.checkRoomRates('searchForm');"
                        type="button" name="checkRates" value="checkRates">
                    <span>
                        <i class="la la-search" alt=""><?php echo JText::_('LNG_SEARCH')?></i>
                    </span>
                </button>
			</div>
			<div class="clear"></div>
		</div>
	</form>
</div>
<script>
    function populateBookingForm() {
    $jinput = JFactory::getApplication()->input;
    $startDate = $jinput->getString('jhotelreservation_datas', '');
    $endDate = $jinput->getString('jhotelreservation_datae', '');
    $rooms = $jinput->getInt('jhotelreservation_rooms', 1);
    $adults = $jinput->getInt('jhotelreservation_guest_adult', 2);
    $children = $jinput->getInt('jhotelreservation_guest_child', 0);

    // Populate the form fields with the submitted values
    $document = JFactory::getDocument();
    $document->addScriptDeclaration("
        document.getElementById('jhotelreservation_datas2').value = '$startDate';
        document.getElementById('jhotelreservation_datae2').value = '$endDate';
        document.getElementById('jhotelreservation_guest_adult').value = '$adults';
        document.getElementById('jhotelreservation_guest_child').value = '$children';
    ");
}
</script>