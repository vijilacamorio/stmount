
<style>
        /* Add your custom styles here */
        .hidden {
            display: none;
        }
    </style>
</head>
<body>

<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
 $(document).ready(function () {
        // Check the initial value of the dropdown on page load
        if ($('#toggleInput').val() === 'yes') {
            $('#gst_no').removeClass('hidden').addClass('show');
        }

        $('#toggleInput').change(function () {
            if ($(this).val() === 'yes') {
                $('#gst_no').removeClass('hidden').addClass('show');
            } else {
                $('#gst_no').removeClass('show').addClass('hidden');
                 $('#gst_no').val('');
            }
        });
    });
</script>




<?php // print_r($intinfo); die();?>




 
<div class="card">
    <div class="card-header">
        <h4><?php echo ('Update Guest List')?> <small class="float-right"><a
                    href="<?php echo base_url("customer/customer_info") ?>" class="btn btn-primary btn-md"><i
                        class="ti-plus" aria-hidden="true"></i> <?php echo ('customer_list')?></a></small></h4>
    </div>
    <div class="row">
        <div class="col-sm-12">

            <div class="card-body">
                <?php echo form_open_multipart('customer/customer_info/create/'.$intinfo->customerid);?>
                <?php echo form_hidden('customerid', (!empty($intinfo->customerid)?$intinfo->customerid:null)) ?>
                <?php echo form_hidden('customernumber', (!empty($intinfo->customernumber)?$intinfo->customernumber:null)) ?>
            
            
            
            
            
                <div class="row">
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill w-100 border mb-4">
                            <div class="card-header py-3">
                                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("guest_details") ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">

 

                                    <div class="col-md-3 mb-1">
                                        <div class="form-group mb-0">
                                            <label><?php echo display("title") ?></label>
                                            <div class="icon-addon addon-md">
                                                <select class="form-control"  name="title"  id="title"      >

                                                <option value="<?php echo html_escape(!empty($intinfo->title) ? $intinfo->title : ''); ?>">
    <?php echo html_escape(!empty($intinfo->title) ? $intinfo->title : ''); ?>
</option>
 


                                                    <option   value="Mr">Mr</option>
                                                    <option value="Ms">Ms</option>
                                                    <option value="Mrs">Mrs.</option>
                                                    <option value="Dr">Dr</option>
                                                 <option value="Sister">Sister</option>
                                                        <option value="Father">Father</option>
                                                               <option value="Others">Others</option>
                                                </select>
                                                <label class="fas fa-meh"></label>
                                            </div>
                                        </div>
                                    </div>






                                    <div class="col-md-4 mb-4">
                                        <div class="form-group mb-0">
                                            <label for="firstname"><?php echo display("first_name") ?> 
                                                   <span class="text-danger"></span></label>
                                                    <div class="icon-addon addon-md">
                                                <input type="text" name="firstname"   class="form-control" id="firstname"
                                                value="<?php echo html_escape((!empty($intinfo->firstname)?$intinfo->firstname:null)) ?>">
                                                <label class="fas fa-user-circle"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="form-group mb-0">
                                            <label for="lastname"><?php echo display("last_name") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="text"  name="lastname"  class="form-control" id="lastname" style="width:286px;"
                                                value="<?php echo html_escape((!empty($intinfo->lastname)?$intinfo->lastname:null)) ?>">
                                                <label class="fas fa-user-circle"></label>
                                            </div>
                                        </div>
                                    </div>

 










                                    <div class="col-md-6 align-self-center mb-3">
                                    <div class="form-group mb-0">
                                            <label><?php echo  ("Gender") ?></label>
                                            <div class="icon-addon addon-md">
                                                <select class="form-control"  name="gender"      style="width:307px;" >
                                                     
                                                <option value="<?php echo html_escape(!empty($intinfo->gender) ? $intinfo->gender : ''); ?>">
    <?php echo html_escape(!empty($intinfo->gender) ? $intinfo->gender : ''); ?>
</option>
                                                
                                                <option   value="Male">Male</option>
                                                    <option value="Female">Female</option>
                
                                                </select>
                                                <label class="fas fa-meh"></label>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="occupation"><?php echo display("occupation") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="text" class="form-control" name="profession"  id="occupation"
                                                value="<?php echo html_escape((!empty($intinfo->profession)?$intinfo->profession:null)) ?>">
                                                <label class="fas fa-anchor"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="dob"><?php echo display("dob") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="date" autocomplete="off" name="dob"
                                                    class="form-control datefilter2" id="dob" 
                                                    value="<?php echo $intinfo->dob; ?>">
                                                <label class="fas fa-calendar-alt"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="anniversary"><?php echo display("anniversary") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="date" name="adate" autocomplete="off"
                                                    class="form-control datefilter2" id="anniversary"
                                                    value="<?php echo html_escape((!empty($intinfo->adate)?$intinfo->adate:null)) ?>">
                                                <label class="fas fa-calendar-alt"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="nationality"><?php echo display("nationality") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="text" name="nationaliti" class="form-control"
                                                    id="nationality" placeholder="<?php echo display("nationality") ?>"
                                                    value="<?php echo html_escape((!empty($intinfo->isnationality)?$intinfo->isnationality:null)) ?>">
                                                <label class="fas fa-flag-usa"></label>
                                            </div>
                                        </div>
                                    </div>
                                
                                    <div class="col-md-6 mb-3">
    <div class="form-group mb-0">
        <label for="nationality"><?php echo ("GSTIN") ?></label>
        <div class="icon-addon addon-md">
            <select id="toggleInput" class="form-control">
          <?php if(!empty($intinfo->gst_no)) { ?>      <option value="yes">Yes</option>    <?php  }  ?>
                <option value="no">No</option>
                <option value="yes">Yes</option>
            </select>
            <br>
            <input type="text" name="gst_no" class="form-control   <?php if(empty($intinfo->gst_no)) {  echo 'hidden'; }?> "
                   id="gst_no" placeholder="<?php echo ("GSTIN") ?>"
                   value="<?php echo html_escape((!empty($intinfo->gst_no) ? $intinfo->gst_no : null)) ?>">
            <label></label>
        </div>
    </div>
</div>

                                    
                                    
                                    
                                    
                                    
                                    
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill w-100 border mb-4">
                            <div class="card-header py-3">
                                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("contact_details") ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="contacttype"><?php echo  ("Booking Type") ?></label>
                                            <div class="icon-addon addon-md">
                                            <select class="form-control" name="contacttype" id="contacttype" style="width: 307px;">

<option value="<?php echo html_escape(!empty($intinfo->contacttype) ? $intinfo->contacttype : ''); ?>">
    <?php echo html_escape(!empty($intinfo->contacttype) ? $intinfo->contacttype : ''); ?>
</option>
<option value="walk-in">walk-in</option>
                                                    <option value="Online">Online</option>
 
</select>

                                                <label class="fas fa-address-book"></label>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="mobileNo"><?php echo display("mobile_no") ?> <span class="text-danger">*</span></label>
                                            <div class="icon-addon addon-md">
                                                <input type="number"    name="phone"    required   class="form-control" id="mobileNo"
                                                value="<?php echo html_escape((!empty($intinfo->cust_phone)?$intinfo->cust_phone:null)) ?>">
                                                <label class="fas fa-mobile">                                                   </label>
 
                                            </div>
                                        </div>
                                    </div>
                                  
                                  








                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="email"><?php echo display("email") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="email" class="form-control" name="email" id="email"
                                                value="<?php echo html_escape((!empty($intinfo->email)?$intinfo->email:null)) ?>">
                                                <label class="fas fa-envelope"></label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="floatingSelect"><?php echo display("city") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="city"  name="city"  class="form-control" id="city"
                                                value="<?php echo html_escape((!empty($intinfo->city)?$intinfo->city:null)) ?>">
                                                <label class="fas fa-globe-americas"></label>
                                            </div>
                                        </div>
                                    </div>


                               


                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="floatingSelect"><?php echo display("state") ?></label>
                                            <div class="icon-addon addon-md">
                                                <input type="state"  name="state"  class="form-control" id="state"
                                                value="<?php echo html_escape((!empty($intinfo->state)?$intinfo->state:null)) ?>">
                                                <label class="fas fa-globe-americas"></label>
                                            </div>
                                        </div>
                                    </div>





                              
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="zipcode"><?php echo display("zipcode") ?></label>
                                            <div class="icon-addon addon-md">
                                               
                                                
                                                
                                                 <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="<?php echo display("zipcode") ?>" 
                                    oninput="javascript: if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" value="<?php echo html_escape((!empty($intinfo->zipcode)?$intinfo->zipcode:null)) ?>"maxlength="10">



                                                <label class="fas fa-code-branch"></label>
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="floatingSelect"><?php echo display("country") ?></label>
                                           
                                            
                                            
                                            
                                            <div class="icon-addon addon-md">
                                                <!--<input type="country" name="country"    class="form-control" id="country"-->
                                                <!--value="<?php echo html_escape((!empty($intinfo->country)?$intinfo->country:null)) ?>">-->
                                                <!--<label class="fas fa-globe-americas"></label>-->

 
   <select name="country" class="form-control countries"   value="<?php echo html_escape((!empty($intinfo->country)?$intinfo->country:null)) ?>" >
     
      
          <option value="<?php echo html_escape((!empty($intinfo->country)?$intinfo->country:null)) ?>"><?php echo html_escape((!empty($intinfo->country)?$intinfo->country:null)) ?></option>
 <option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option>
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option>
<option value="Anguilla">Anguilla</option>
<option value="Antartica">Antarctica</option>
<option value="Antigua and Barbuda">Antigua and Barbuda</option>
<option value="Argentina">Argentina</option>
<option value="Armenia">Armenia</option>
<option value="Aruba">Aruba</option>
<option value="Australia">Australia</option>
<option value="Austria">Austria</option>
<option value="Azerbaijan">Azerbaijan</option>
<option value="Bahamas">Bahamas</option>
<option value="Bahrain">Bahrain</option>
<option value="Bangladesh">Bangladesh</option>
<option value="Barbados">Barbados</option>
<option value="Belarus">Belarus</option>
<option value="Belgium">Belgium</option>
<option value="Belize">Belize</option>
<option value="Benin">Benin</option>
<option value="Bermuda">Bermuda</option>
<option value="Bhutan">Bhutan</option>
<option value="Bolivia">Bolivia</option>
<option value="Bosnia and Herzegowina">Bosnia and Herzegowina</option>
<option value="Botswana">Botswana</option>
<option value="Bouvet Island">Bouvet Island</option>
<option value="Brazil">Brazil</option>
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
<option value="Brunei Darussalam">Brunei Darussalam</option>
<option value="Bulgaria">Bulgaria</option>
<option value="Burkina Faso">Burkina Faso</option>
<option value="Burundi">Burundi</option>
<option value="Cambodia">Cambodia</option>
<option value="Cameroon">Cameroon</option>
<option value="Canada">Canada</option>
<option value="Cape Verde">Cape Verde</option>
<option value="Cayman Islands">Cayman Islands</option>
<option value="Central African Republic">Central African Republic</option>
<option value="Chad">Chad</option>
<option value="Chile">Chile</option>
<option value="China">China</option>
<option value="Christmas Island">Christmas Island</option>
<option value="Cocos Islands">Cocos (Keeling) Islands</option>
<option value="Colombia">Colombia</option>
<option value="Comoros">Comoros</option>
<option value="Congo">Congo</option>
<option value="Congo">Congo, the Democratic Republic of the</option>
<option value="Cook Islands">Cook Islands</option>
<option value="Costa Rica">Costa Rica</option>
<option value="Cota D'Ivoire">Cote d'Ivoire</option>
<option value="Croatia">Croatia (Hrvatska)</option>
<option value="Cuba">Cuba</option>
<option value="Cyprus">Cyprus</option>
<option value="Czech Republic">Czech Republic</option>
<option value="Denmark">Denmark</option>
<option value="Djibouti">Djibouti</option>
<option value="Dominica">Dominica</option>
<option value="Dominican Republic">Dominican Republic</option>
<option value="East Timor">East Timor</option>
<option value="Ecuador">Ecuador</option>
<option value="Egypt">Egypt</option>
<option value="El Salvador">El Salvador</option>
<option value="Equatorial Guinea">Equatorial Guinea</option>
<option value="Eritrea">Eritrea</option>
<option value="Estonia">Estonia</option>
<option value="Ethiopia">Ethiopia</option>
<option value="Falkland Islands">Falkland Islands (Malvinas)</option>
<option value="Faroe Islands">Faroe Islands</option>
<option value="Fiji">Fiji</option>
<option value="Finland">Finland</option>
<option value="France">France</option>
<option value="France Metropolitan">France, Metropolitan</option>
<option value="French Guiana">French Guiana</option>
<option value="French Polynesia">French Polynesia</option>
<option value="French Southern Territories">French Southern Territories</option>
<option value="Gabon">Gabon</option>
<option value="Gambia">Gambia</option>
<option value="Georgia">Georgia</option>
<option value="Germany">Germany</option>
<option value="Ghana">Ghana</option>
<option value="Gibraltar">Gibraltar</option>
<option value="Greece">Greece</option>
<option value="Greenland">Greenland</option>
<option value="Grenada">Grenada</option>
<option value="Guadeloupe">Guadeloupe</option>
<option value="Guam">Guam</option>
<option value="Guatemala">Guatemala</option>
<option value="Guinea">Guinea</option>
<option value="Guinea-Bissau">Guinea-Bissau</option>
<option value="Guyana">Guyana</option>
<option value="Haiti">Haiti</option>
<option value="Heard and McDonald Islands">Heard and Mc Donald Islands</option>
<option value="Holy See">Holy See (Vatican City State)</option>
<option value="Honduras">Honduras</option>
<option value="Hong Kong">Hong Kong</option>
<option value="Hungary">Hungary</option>
<option value="Iceland">Iceland</option>
<option value="India">India</option>
<option value="Indonesia">Indonesia</option>
<option value="Iran">Iran (Islamic Republic of)</option>
<option value="Iraq">Iraq</option>
<option value="Ireland">Ireland</option>
<option value="Israel">Israel</option>
<option value="Italy">Italy</option>
<option value="Jamaica">Jamaica</option>
<option value="Japan">Japan</option>
<option value="Jordan">Jordan</option>
<option value="Kazakhstan">Kazakhstan</option>
<option value="Kenya">Kenya</option>
<option value="Kiribati">Kiribati</option>
<option value="Democratic People's Republic of Korea">Korea, Democratic People's Republic of</option>
<option value="Korea">Korea, Republic of</option>
<option value="Kuwait">Kuwait</option>
<option value="Kyrgyzstan">Kyrgyzstan</option>
<option value="Lao">Lao People's Democratic Republic</option>
<option value="Latvia">Latvia</option>
<option value="Lebanon"  >Lebanon</option>
<option value="Lesotho">Lesotho</option>
<option value="Liberia">Liberia</option>
<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
<option value="Liechtenstein">Liechtenstein</option>
<option value="Lithuania">Lithuania</option>
<option value="Luxembourg">Luxembourg</option>
<option value="Macau">Macau</option>
<option value="Macedonia">Macedonia, The Former Yugoslav Republic of</option>
<option value="Madagascar">Madagascar</option>
<option value="Malawi">Malawi</option>
<option value="Malaysia">Malaysia</option>
<option value="Maldives">Maldives</option>
<option value="Mali">Mali</option>
<option value="Malta">Malta</option>
<option value="Marshall Islands">Marshall Islands</option>
<option value="Martinique">Martinique</option>
<option value="Mauritania">Mauritania</option>
<option value="Mauritius">Mauritius</option>
<option value="Mayotte">Mayotte</option>
<option value="Mexico">Mexico</option>
<option value="Micronesia">Micronesia, Federated States of</option>
<option value="Moldova">Moldova, Republic of</option>
<option value="Monaco">Monaco</option>
<option value="Mongolia">Mongolia</option>
<option value="Montserrat">Montserrat</option>
<option value="Morocco">Morocco</option>
<option value="Mozambique">Mozambique</option>
<option value="Myanmar">Myanmar</option>
<option value="Namibia">Namibia</option>
<option value="Nauru">Nauru</option>
<option value="Nepal">Nepal</option>
<option value="Netherlands">Netherlands</option>
<option value="Netherlands Antilles">Netherlands Antilles</option>
<option value="New Caledonia">New Caledonia</option>
<option value="New Zealand">New Zealand</option>
<option value="Nicaragua">Nicaragua</option>
<option value="Niger">Niger</option>
<option value="Nigeria">Nigeria</option>
<option value="Niue">Niue</option>
<option value="Norfolk Island">Norfolk Island</option>
<option value="Northern Mariana Islands">Northern Mariana Islands</option>
<option value="Norway">Norway</option>
<option value="Oman">Oman</option>
<option value="Pakistan">Pakistan</option>
<option value="Palau">Palau</option>
<option value="Panama">Panama</option>
<option value="Papua New Guinea">Papua New Guinea</option>
<option value="Paraguay">Paraguay</option>
<option value="Peru">Peru</option>
<option value="Philippines">Philippines</option>
<option value="Pitcairn">Pitcairn</option>
<option value="Poland">Poland</option>
<option value="Portugal">Portugal</option>
<option value="Puerto Rico">Puerto Rico</option>
<option value="Qatar">Qatar</option>
       
	  <option value="Reunion">Reunion</option>
<option value="Romania">Romania</option>
<option value="Russia">Russian Federation</option>
<option value="Rwanda">Rwanda</option>
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
<option value="Saint LUCIA">Saint LUCIA</option>
<option value="Saint Vincent">Saint Vincent and the Grenadines</option>
<option value="Samoa">Samoa</option>
<option value="San Marino">San Marino</option>
<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
<option value="Saudi Arabia">Saudi Arabia</option>
<option value="Senegal">Senegal</option>
<option value="Seychelles">Seychelles</option>
<option value="Sierra">Sierra Leone</option>
<option value="Singapore">Singapore</option>
<option value="Slovakia">Slovakia (Slovak Republic)</option>
<option value="Slovenia">Slovenia</option>
<option value="Solomon Islands">Solomon Islands</option>
<option value="Somalia">Somalia</option>
<option value="South Africa">South Africa</option>
<option value="South Georgia">South Georgia and the South Sandwich Islands</option>
<option value="Span">Spain</option>
<option value="SriLanka">Sri Lanka</option>
<option value="St. Helena">St. Helena</option>
<option value="St. Pierre and Miguelon">St. Pierre and Miquelon</option>
<option value="Sudan">Sudan</option>
<option value="Suriname">Suriname</option>
<option value="Svalbard">Svalbard and Jan Mayen Islands</option>
<option value="Swaziland">Swaziland</option>
<option value="Sweden">Sweden</option>
<option value="Switzerland">Switzerland</option>
<option value="Syria">Syrian Arab Republic</option>
<option value="Taiwan">Taiwan, Province of China</option>
<option value="Tajikistan">Tajikistan</option>
<option value="Tanzania">Tanzania, United Republic of</option>
<option value="Thailand">Thailand</option>
<option value="Togo">Togo</option>
<option value="Tokelau">Tokelau</option>
<option value="Tonga">Tonga</option>
<option value="Trinidad and Tobago">Trinidad and Tobago</option>
<option value="Tunisia">Tunisia</option>
<option value="Turkey">Turkey</option>
<option value="Turkmenistan">Turkmenistan</option>
<option value="Turks and Caicos">Turks and Caicos Islands</option>
<option value="Tuvalu">Tuvalu</option>
<option value="Uganda">Uganda</option>
<option value="Ukraine">Ukraine</option>
<option value="United Arab Emirates">United Arab Emirates</option>
<option value="United Kingdom">United Kingdom</option>
<option value="United States">United States</option>
<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
<option value="Uruguay">Uruguay</option>
<option value="Uzbekistan">Uzbekistan</option>
<option value="Vanuatu">Vanuatu</option>
<option value="Venezuela">Venezuela</option>
<option value="Vietnam">Viet Nam</option>
<option value="Virgin Islands (British)">Virgin Islands (British)</option>
<option value="Virgin Islands (U.S)">Virgin Islands (U.S.)</option>
<option value="Wallis and Futana Islands">Wallis and Futuna Islands</option>
<option value="Western Sahara">Western Sahara</option>
<option value="Yemen">Yemen</option>
<option value="Serbia">Serbia</option>
<option value="Zambia">Zambia</option>
<option value="Zimbabwe">Zimbabwe</option>
</select>		 
                                                        <label class="fas fa-globe-americas"></label>





                                            </div>








                                        </div>
                                    </div>





                                    <div class="col-md-12 mb-3">
                                        <div class="form-floating with-icon">


                                            <textarea class="form-control"
                                               name="address"    placeholder="<?php echo display('address') ?>"><?php echo html_escape((!empty($intinfo->address)?$intinfo->address:null)) ?> </textarea>



                                            <label for="address"><?php echo display("address") ?></label>
                                            <i class="fas fa-map-pin"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill w-100 border mb-4 mb-md-0">
                            <div class="card-header py-3">
                                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("photo_id_details") ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="pitype"><?php echo display("photo_id_type") ?>   <span class="text-danger">*</span>  </label>
                                            <div class="icon-addon addon-md">
                                             
                                     
                                            
            <select name="id_type" id="pitype "   required  class="form-control" >

            <option value="<?php echo html_escape(!empty($intinfo->pitype) ? $intinfo->pitype : ''); ?>">
    <?php echo html_escape(!empty($intinfo->pitype) ? $intinfo->pitype : ''); ?>
</option>

                                                   <option value="Driving Licence">Driving Licence </option>
    <option value="Passport">Passport</option>
    <option value="Aadhar Card">Aadhar Card</option>
    <option value="PAN Card">PAN Card</option>
    <!-- Add more options as needed -->
</select>
                                            
                                            
                                            </div>




                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="form-group mb-0">
                                            <label for="pid"><?php echo display("photo_id") ?> # <span
                                                    class="text-danger">*</span></label>
                                            <div class="icon-addon addon-md">
                                                <input type="text"  name="national_id" class="form-control" id="pid"
                                                value="<?php echo html_escape((!empty($intinfo->pid)?$intinfo->pid:null)) ?>"    required  >
                                                <label class="fas fa-images"></label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12" style="text-align:center;" >
                                        <label><?php echo display("photo_id_upload") ?></label>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="image-upload position-relative overflow-hidden m-auto">
                                            <!-- <input type="file" id="imgfront" onchange="fileValueOne(this)">
                                            <input type="hidden" id="imgffront"> -->
                                            <label for="imgfront" class="upload-field mb-0" id="file-label">
                                                <span class="file-thumbnail">
                                                    <span
                                                        class="d-block text-center mb-2" style=" margin-right: 225px;"  ><?php echo display("front_side") ?></span>
                                                      
                                                      
                                                      
                                                        <img src="<?php echo html_escape(base_url(!empty($intinfo->imgfront)?$intinfo->imgfront:'assets/img/room-setting/room_images.png')); ?>"
                        id="" class="img-thumbnail height_150_width_200px jsclrimg" />
                        <input type="hidden" name="imgfront"
                        value="<?php echo html_escape((!empty($intinfo->imgfront)?$intinfo->imgfront:null)) ?>">
                        <input type="file" accept="image/*" name="imgfront">
                        <input type="hidden" name="old_imgfront" value="<?php echo html_escape((!empty($intinfo->imgfront)?$intinfo->imgfront:null)) ?>">
                                                   
                                                   
                                                        <!-- <span id="filename"
                                                        class="d-block mt-2"><?php echo display("drag_and_drop") ?></span>
                                                    <span class="format"><?php echo display("supports_image") ?></span> -->
                                                </span>
                                            </label>


                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="image-upload position-relative overflow-hidden m-auto">
                                         
                                            <label for="imgback" class="upload-field mb-0" id="file-label">
                                                <span class="file-thumbnail">
                                                    <span
                                                        class="d-block text-center mb-2" style=" margin-right: 225px;"  ><?php echo display("back_side") ?></span>
                                                  
                                                  
                                                  
                                                  
                                                        <img src="<?php echo html_escape(base_url(!empty($intinfo->imgback)?$intinfo->imgback:'assets/img/room-setting/room_images.png')); ?>"
                        id="" class="img-thumbnail height_150_width_200px jsclrimg" />
                        <input type="hidden" name="imgback"
                        value="<?php echo html_escape((!empty($intinfo->imgback)?$intinfo->imgback:null)) ?>">
                        <input type="file" accept="image/*" name="imgback">
                        <input type="hidden" name="old_imgback" value="<?php echo html_escape((!empty($intinfo->imgback)?$intinfo->imgback:null)) ?>">
                          
                                                   
                                                   
                                                        <!-- <span id="filename2"
                                                        class="d-block mt-2"><?php echo display("drag_and_drop") ?></span>
                                                    <span class="format"><?php echo display("supports_image") ?></span> -->
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <div class="form-floating with-icon">
                                            <textarea class="form-control" name="remarks"  placeholder="Remarks"
                                                id="comments"><?php echo html_escape((!empty($intinfo->remarks)?$intinfo->remarks:null)) ?></textarea>
                                            <i class="far fa-comment-dots"></i>
                                            <label for="comments"><?php echo display("comments") ?></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex">
                        <div class="card flex-fill w-100 border">
                            <div class="card-header py-3">
                                <h6 class="fs-17 font-weight-600 mb-0"><?php echo display("guest_image") ?></h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-8 col-lg-6 mb-3">
                                        <div class="image-upload position-relative overflow-hidden m-auto">
                                            <!-- <input type="file" id="imgguest" onchange="fileValuesThree(this)">
                                            <input type="hidden" id="imggguest"> -->
                                            <label for="imgguest" class="upload-field mb-0" id="file-label">
                                                <span class="file-thumbnail">
                                                    <!--<span-->
                                                    <!--    class="d-block mb-2"><?php //echo display("occupant_image") ?></span>-->
                                                        <img src="<?php echo html_escape(base_url(!empty($intinfo->imgguest)?$intinfo->imgguest:'assets/img/room-setting/room_images.png')); ?>"
                        id="" class="img-thumbnail height_150_width_200px jsclrimg" />
                        <input type="hidden" name="imgguest"
                        value="<?php echo html_escape((!empty($intinfo->imgguest)?$intinfo->imgguest:null)) ?>">
                        <input type="file" accept="image/*" name="imgguest">
                        <input type="hidden" name="old_imgguest" value="<?php echo html_escape((!empty($intinfo->imgguest)?$intinfo->imgguest:null)) ?>">



                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>










                <div class="form-group">
                    <button type="submit" class="btn btn-success w-md m-b-5"><?php echo display('update') ?></button>
                </div>



                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>





<script>
    
$(document).ready(function(){
    var $cat = $('select[name=country]'),
        $items = $('select[name=items]');

    $cat.change(function(){
        var $this = $(this).find(':selected'),
            rel = $this.attr('rel'),
            $set = $items.find('option.' + rel);

        if ($set.size() < 0) {
            $items.hide();
            return;
        }

        $items.show().find('option').hide();

        $set.show().first().prop('selected', true);
    });

	//copy phone number to coupon

	$('#phone').change(function() {
    $('#coupon').val($(this).val());
});

//reset the forms

 resetForms();
function resetForms() {
    for (i = 0; i < document.forms.length; i++) {
        document.forms[i].reset();
    }
}

});


	//Phone format

	$.fn.ForceNumericOnly =
function()
{
    return this.each(function()
    {
        $(this).keydown(function(e)
        {
            var key = e.charCode || e.keyCode || 0;
            // allow backspace, tab, delete, enter, arrows, numbers and keypad numbers ONLY
            // home, end, period, and numpad decimal
            return (
                key == 8 || //backspace
                key == 9 || //tab
                key == 13 ||  //enter
                key == 46 ||  //delete
                key == 110 ||  //decimal point
                key == 190 ||  //period
                (key >= 35 && key <= 40) ||  //end,home,arrows,insert,delete
                (key >= 48 && key <= 57) ||  //numbers
                (key >= 96 && key <= 105));  //numpad 0-9
        });
    });
};
	$("#phone").ForceNumericOnly();




	$('#btnContact').click(function(e) {
var sCountry = $('#countries').val();
var sPhone = $('#phone').val();

if ( sCountry == "Country" || $("#pincode").val()== "Code" || sPhone.length == 0 ) {
$(".error-message").show();
$(".error-message").fadeOut(2000);
$(".success-message").hide();
e.preventDefault();
}

else if( sPhone.length <= 9 )
{
$(".error-phone").show();
$(".error-phone").fadeOut(2000);
$(".success-message").hide();
e.preventDefault();
}

else {
$(".success-message").show();
$(".error-message").hide();
$(".success-message").fadeOut(5000);
}
});

/*
Object of countries based on
http://en.wikipedia.org/wiki/List_of_IOC_country_codes
*/
function countriesDropdown(container){
	var countries = {
		AFG: "Afghanistan",
		ALB: "Albania",
		ALG: "Algeria",
		AND: "Andorra",
		ANG: "Angola",
		ANT: "Antigua and Barbuda",
		ARG: "Argentina",
		ARM: "Armenia",
		ARU: "Aruba",
		ASA: "American Samoa",
		AUS: "Australia",
		AUT: "Austria",
		AZE: "Azerbaijan",
		BAH: "Bahamas",
		BAN: "Bangladesh",
		BAR: "Barbados",
		BDI: "Burundi",
		BEL: "Belgium",
		BEN: "Benin",
		BER: "Bermuda",
		BHU: "Bhutan",
		BIH: "Bosnia and Herzegovina",
		BIZ: "Belize",
		BLR: "Belarus",
		BOL: "Bolivia",
		BOT: "Botswana",
		BRA: "Brazil",
		BRN: "Bahrain",
		BRU: "Brunei",
		BUL: "Bulgaria",
		BUR: "Burkina Faso",
		CAF: "Central African Republic",
		CAM: "Cambodia",
		CAN: "Canada",
		CAY: "Cayman Islands",
		CGO: "Congo",
		CHA: "Chad",
		CHI: "Chile",
		CHN: "China",
		CIV: "Cote d'Ivoire",
		CMR: "Cameroon",
		COD: "DR Congo",
		COK: "Cook Islands",
		COL: "Colombia",
		COM: "Comoros",
		CPV: "Cape Verde",
		CRC: "Costa Rica",
		CRO: "Croatia",
		CUB: "Cuba",
		CYP: "Cyprus",
		CZE: "Czech Republic",
		DEN: "Denmark",
		DJI: "Djibouti",
		DMA: "Dominica",
		DOM: "Dominican Republic",
		ECU: "Ecuador",
		EGY: "Egypt",
		ERI: "Eritrea",
		ESA: "El Salvador",
		ESP: "Spain",
		EST: "Estonia",
		ETH: "Ethiopia",
		FIJ: "Fiji",
		FIN: "Finland",
		FRA: "France",
		FSM: "Micronesia",
		GAB: "Gabon",
		GAM: "Gambia",
		GBR: "Great Britain",
		GBS: "Guinea-Bissau",
		GEO: "Georgia",
		GEQ: "Equatorial Guinea",
		GER: "Germany",
		GHA: "Ghana",
		GRE: "Greece",
		GRN: "Grenada",
		GUA: "Guatemala",
		GUI: "Guinea",
		GUM: "Guam",
		GUY: "Guyana",
		HAI: "Haiti",
		HKG: "Hong Kong",
		HON: "Honduras",
		HUN: "Hungary",
		INA: "Indonesia",
		IND: "India",
		IRI: "Iran",
		IRL: "Ireland",
		IRQ: "Iraq",
		ISL: "Iceland",
		ISR: "Israel",
		ISV: "Virgin Islands",
		ITA: "Italy",
		IVB: "British Virgin Islands",
		JAM: "Jamaica",
		JOR: "Jordan",
		JPN: "Japan",
		KAZ: "Kazakhstan",
		KEN: "Kenya",
		KGZ: "Kyrgyzstan",
		KIR: "Kiribati",
		KOR: "South Korea",
		KOS: "Kosovo",
		KSA: "Saudi Arabia",
		KUW: "Kuwait",
		LAO: "Laos",
		LAT: "Latvia",
		LBA: "Libya",
		LBR: "Liberia",
		LCA: "Saint Lucia",
		LES: "Lesotho",
		LIB: "Lebanon",
		LIE: "Liechtenstein",
		LTU: "Lithuania",
		LUX: "Luxembourg",
		MAD: "Madagascar",
		MAR: "Morocco",
		MAS: "Malaysia",
		MAW: "Malawi",
		MDA: "Moldova",
		MDV: "Maldives",
		MEX: "Mexico",
		MGL: "Mongolia",
		MHL: "Marshall Islands",
		MKD: "Macedonia",
		MLI: "Mali",
		MLT: "Malta",
		MNE: "Montenegro",
		MON: "Monaco",
		MOZ: "Mozambique",
		MRI: "Mauritius",
		MTN: "Mauritania",
		MYA: "Myanmar",
		NAM: "Namibia",
		NCA: "Nicaragua",
		NED: "Netherlands",
		NEP: "Nepal",
		NGR: "Nigeria",
		NIG: "Niger",
		NOR: "Norway",
		NRU: "Nauru",
		NZL: "New Zealand",
		OMA: "Oman",
		PAK: "Pakistan",
		PAN: "Panama",
		PAR: "Paraguay",
		PER: "Peru",
		PHI: "Philippines",
		PLE: "Palestine",
		PLW: "Palau",
		PNG: "Papua New Guinea",
		POL: "Poland",
		POR: "Portugal",
		PRK: "North Korea",
		PUR: "Puerto Rico",
		QAT: "Qatar",
		ROU: "Romania",
		RSA: "South Africa",
		RUS: "Russia",
		RWA: "Rwanda",
		SAM: "Samoa",
		SEN: "Senegal",
		SEY: "Seychelles",
		SIN: "Singapore",
		SKN: "Saint Kitts and Nevis",
		SLE: "Sierra Leone",
		SLO: "Slovenia",
		SMR: "San Marino",
		SOL: "Solomon Islands",
		SOM: "Somalia",
		SRB: "Serbia",
		SRI: "Sri Lanka",
		SSD: "South Sudan",
		STP: "Sao Tome and Principe",
		SUD: "Sudan",
		SUI: "Switzerland",
		SUR: "Suriname",
		SVK: "Slovakia",
		SWE: "Sweden",
		SWZ: "Swaziland",
		SYR: "Syria",
		TAN: "Tanzania",
		TGA: "Tonga",
		THA: "Thailand",
		TJK: "Tajikistan",
		TKM: "Turkmenistan",
		TLS: "Timor-Leste",
		TOG: "Togo",
		TPE: "Chinese Taipei",
		TTO: "Trinidad and Tobago",
		TUN: "Tunisia",
		TUR: "Turkey",
		TUV: "Tuvalu",
		UAE: "United Arab Emirates",
		UGA: "Uganda",
		UKR: "Ukraine",
		URU: "Uruguay",
		USA: "United States",
		UZB: "Uzbekistan",
		VAN: "Vanuatu",
		VEN: "Venezuela",
		VIE: "Vietnam",
		VIN: "Saint Vincent and the Grenadines",
		YEM: "Yemen",
		ZAM: "Zambia",
		ZAN: "Zanzibar",
		ZIM: "Zimbabwe"
		}
	var out = "<select><option rel=''>Country</option>";
	for (var key in countries) {
		out += "<option rel='" + key + "'>" + countries[key] + "</option>";
	}
	out += "</select>";

	document.getElementById(container).innerHTML = out;
}
countriesDropdown("countries");



 

$("#myForm").validate({
  errorLabelContainer: "#messageBox",
  wrapper: "li",
  submitHandler: function() { alert("Submitted!") }
});
</script>