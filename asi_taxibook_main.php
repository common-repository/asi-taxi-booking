<?php 
add_action('init', 'asi_booktaxi_register_shortcodes');
function asi_booktaxi_register_shortcodes() {
    add_shortcode('asi-booktaxi', 'asi_taxi_shortcode');
}
function asi_taxi_shortcode($atts) {
      $carinfo=new asi_taxibook_plugin_admin();             
         $allfare=$carinfo->taxi_allselected_fare();
         $cartypes=$carinfo->taxi_allselected_car();         
         $select='<select name="cartypes" class="form-control" id="cartypes" style="width: 75%;padding-left: 15px; float: right;">';
         $select.='<option value="select" >'.__("Select Taxi").'</option>';
         foreach($cartypes as $car)
         {
            $select.='<option value="'.$car['fare'].'">'.$car['name'].'</option>';
         }
         $select.='</select>';
         $color=$allfare[0]['color'];
         if($color!="")
         {
            $color='background-color:'.$allfare[0]['color'];
         }
         if (isset($_POST['addbooking'])) {
            $user_date=sanitize_text_field($_POST['user_date']);
            $time=sanitize_text_field($_POST['usr_time']);
            $date=$user_date.' '.$time;
            $car=sanitize_text_field($_POST['cartypes']);
            $pick=sanitize_text_field($_POST['source']);
            $stopc=sanitize_text_field($_POST['stops_count']);
            $drop=sanitize_text_field($_POST['destination']);
            $adulc=sanitize_text_field($_POST['adult_seat']);
            $infc=sanitize_text_field($_POST['enf_seat']);
            $babyc=sanitize_text_field($_POST['baby_seat']);
            $lugc=sanitize_text_field($_POST['lugg']);
            $bname=sanitize_text_field($_POST['bname']);
            $bemail=sanitize_text_field($_POST['bemail']);
            $bcell=sanitize_text_field($_POST['bcell']);
            $output_form = true;
            global $wpdb;
            if (isset( $_POST['pf_added'] ) && wp_verify_nonce($_POST['pf_added'], 'add-item') )
            {
                $booking = $wpdb->prefix."Booking";
                $wpdb->query($wpdb->prepare("INSERT INTO $booking(name,email,cell,cartype,pickup,dropoff,stop,adults,baby,infan,lugg,date)
                VALUES(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", array($bname,$bemail,$bcell,$car,$pick,$drop,$stopc,$adulc,$babyc,$infc,$lugc,$date)));
            }
             $admin_email = get_option('admin_email');
             $headers = __("From: ").$bemail."\r\n";
             $headers2 = __("From: ").$admin_email."\r\n";
             $message = __("Name: ").$bname. "\n";
             $message.="\n".__("Cell Number: ").$bcell."\n";
             $message.=__("PickUp Address: ").$pick."\n";
             $message.=__("DropOff Address: ").$drop."\n";
             $message.=__("Date and Time: ").$date."\n";
             $subject=__("Taxi Booking: ");
             mail($admin_email,$subject,$message,$headers);
             mail($bemail,$subject,$message,$headers2);
        } 
        else {
                   $output_form = true;
                   $user_date = "";
                   $time = "";
                   $car = "";
                   $pick = "";
                   $stopc = "";
                   $drop = "";
                   $adulc = "";
                   $infc = "";
                   $babyc = "";
                   $lugc = "";
                   $bname = "";
                   $bemail = "";
                   $bcell = "";
            }
    if($output_form){
            $displayform='<div class="container">
			<div class="row">
				<div class="col-lg-5 col-md-6 col-sm-7 col-xs-12" id="main1" style="'.$color.'; padding-bottom: 15px">
				<form id="order" name="addbooking" method="post" onsubmit="return doCalculation();">'
                   .wp_nonce_field("add-item","pf_added"). 
						'<div class="row" style="padding-top: 15px;">
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<input style="padding-left: 15px; width: 100%;" class="form-control" name="user_date" type="date" id="bdate" min="2015-06-01">
							</div>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
								<input style="width: 75%; float: right;padding-left: 15px;" class="form-control" type="time" id="btime" value="16:00" name="usr_time">
							</div>
						</div>
						<div class="row">
							<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px">
								'.__("Taxi Type: ").'
							</label>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px;">
								'.$select.'
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
								<input type="text" class="form-control" id="source" name="source" placeholder="'.__("PickUp Address").'">                                
							<input style="display: none;" type="text" hidden class="form-control" id="stops_count_s" name="stops_count">
							</div>
						</div>
						<div class="row">
							<label class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px">
								'.__("Additional Stops").'
							</label>
							<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-top: 15px;">     
							<input style="padding-left: 15px; width: 75%; float: right;" class="form-control" type="number" value="0" min="0" name="stops_count" id="stops_count">
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                            <input type="textbox" id="destination" name="destination" placeholder="'.__("DropOff Address").'" class="form-control" value="" />
							</div>
						</div>
						<div class="row" >
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
								<label class="" style="padding-top: 14px">
									'.__("Adults").'
								</label>
                             <input type="number" value="0" min="0" max="10" class="form-control" name="adult_seat" id="adult_seat" value="" />
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
								<label class="" style="padding-top: 14px">
								'.__("Infants").'
								</label>
                                <input type="number" value="0" min="0" max="10" class="form-control" value="" name="enf_seat" id="enf_seat" />
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
								<label class="" style="padding-top: 14px">
									'.__("BabySeats").'
								</label>
                                <input type="number" value="0" min="0" max="10" class="form-control" value="" name="baby_seat" id="baby_seat" />
							</div>
							<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
								<label class="" style="padding-top: 14px">
									'.__("Bags").'
								</label>
                               <input type="number" value="0" min="0" max="10" class="form-control" value="" name="lugg" id="lugg" />
							</div>
						</div>
						<div class="row">
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                            <input type="textbox" id="bname" name="bname" placeholder="'.__("Your Name").'" class="form-control" value="" />
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                            <input type="textbox" id="bemail" name="bemail" placeholder="'.__("Your Email").'" class="form-control" value="" />
							</div>
							<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12" style="padding-top: 15px;">
                            <input type="textbox" id="bcell" name="bcell" placeholder="'.__("Your Phone No").'" class="form-control" value="" />
                             <input type="hidden" name="distance"  id="distance" readonly value=""/>
                            <input type="hidden" name="fare" id="fare" readonly value=""/>
                            <input type="hidden" name="duration" id="duration" readonly value=""/>
							</div>
						</div>
						<div class="calBlue_line">
						</div>
						<div class="form-group">
							<div class="col-xs-12" style="text-align: center;padding-top: 15px; margin-bottom: 15px">
								<input type="submit" id="cal1" value="'.__("Book").'" class="btn btn-primary " name="addbooking" style="font-size: 14px; font-weight: bold" />
								<input type="button" id="res1" class="btn" name="reset" value="'.__("Reset").'" onclick="clear_form_elements(this.form)" style="font-size: 14px; font-weight: bold;" />
							</div>
                <input type="hidden"  name="stopfare" id="stopfare" value="'.$allfare[0]['stop'].'"/>
                <input type="hidden"  name="milefare" id="milefare" value="'.$allfare[0]['mile'].'"/>
                <input type="hidden"  name="seatfare" id="seatfare" value="'.$allfare[0]['seat'].'"/>
                <input type="hidden"  name="minutefare" id="minutefare" value="'.$allfare[0]['minute'].'"/>
                <input type="hidden"  name="currfare" id="currfare" value="'.$allfare[0]['curr'].'"/>
                <input type="hidden"  name="adulfare" id="adulfare" value="'.$allfare[0]['adul'].'"/>
                <input type="hidden"  name="inffare" id="inffare" value="'.$allfare[0]['inf'].'"/>
                <input type="hidden"  name="luggfare" id="luggfare" value="'.$allfare[0]['lugg'].'"/>
                 <input type="hidden" name="diskmmiles" id="diskmmiles" value="'.$allfare[0]['diskmmile'].'"/>            
						</div>
						<div class="table-float" style="text-align: center; margin-top: 10px; float: none">
							<div id="po" style="display:none; text-align: left">
                            <span class="nearest">'.__("Estimated Fare: ").'</span><span id="estfare"></span><br>
                            <span class="nearest">'.__("Distance: ").'</span><span id="estdist"></span><br>
                            <span class="nearest">'.__("Duration: ").'</span><span id="estdur"></span><br>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
        <div class="table-float" style="text-align: center">
		<div id="po" style="display: none; text-align: left"></div> 
	</div>';
return $displayform;
    }    
} 
?>