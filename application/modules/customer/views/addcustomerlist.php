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
          $('#toggleInput').change(function () {
              if ($(this).val() === 'yes') {
                  $('#gst_no').removeClass('hidden').addClass('show');
              } else {
                  $('#gst_no').removeClass('show').addClass('hidden');
                   $('#gst_no').val('');
              }
          });
      });


 
   document.addEventListener('DOMContentLoaded', function() {
    var nationality = document.getElementById('nationality');
    var contentToToggle = document.getElementById('contentToToggle');
    var cFullNameInput = document.getElementById('c_full_name'); // Moved this declaration outside the event listener
    
    // Check if all necessary elements are found
    if (!nationality || !contentToToggle || !cFullNameInput) return;
    
    // Initial state
    contentToToggle.style.display = 'none'; // Hide the element by default
    
    // Event listener for nationality change
    nationality.addEventListener('change', function() {
        if (this.value.toLowerCase() === 'ind' || this.value.toLowerCase() === 'india' || this.value.toLowerCase() === 'indian') {
            contentToToggle.style.display = 'none';
            cFullNameInput.removeAttribute('required');
        } else {
            contentToToggle.style.display = 'block';
            cFullNameInput.setAttribute('required', 'required');
        }
    });
});

 
   </script>



   <div class="card">
   <div class="card-header">
      <h4><?php echo display("add_guest");?> <small class="float-right"><a href="<?php echo base_url("customer/customer_info") ?>" class="btn btn-primary btn-md"><i class="ti-plus" aria-hidden="true"></i> <?php echo display('guest_list')?></a></small></h4>
   </div>
   <div class="row">
      <!--  table area -->
      <div class="col-sm-12">
         <div class="card-body">
            <!-- <?php //echo form_open('customer/customer_info/create');?> -->
            <?php echo form_open_multipart('customer/customer_info/create') ?>
            <?php echo form_hidden('customerid', (!empty($intinfo->customerid)?$intinfo->customerid:null)) ?>
            <div class="modal-dialog " style="max-width: 1800px;">
               <div class="modal-content border-0">
                  <!-- <div class="modal-header">
                     <h5 class="modal-title" id="exampleModalLabel"><?php echo display("occupant_details") ?></h5>
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                         <span aria-hidden="true">&times;</span>
                     </button>
                     </div> -->
                  <!-- <div class="modal-body"> -->
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
                                          <select class="form-control"  name="title"  id="title">
                                              <option selected disabled value="">Title</option>
                                             <option  value="Mr">Mr</option>
                                             <option value="Ms">Ms</option>
                                             <option value="Mrs">Mrs</option>
                                             <option value="M/S">M/S</option>
                                             <option value="Dr">Dr</option>
                                             <option value="Sister">Sister</option>
                                             <option value="Father">Father</option>
                                          </select>
                                          <label class="fas fa-meh"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                       <label for="firstname"><?php echo display("first_name") ?> <span
                                          class="text-danger">*</span></label>
                                       <div class="icon-addon addon-md">
                                          <input type="text" name="firstname"   class="form-control" id="firstname"
                                             placeholder="<?php echo display("first_name") ?>"  required  >
                                          <label class="fas fa-user-circle"></label>
                                       </div>
                                    </div>
                                 </div>
 
                                 <script>
                                    document.getElementById('firstname').addEventListener('input', function() {
                                        var inputValue = this.value;
                                        var regex = /^[a-zA-Z]*$/;
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>
                                 <div class="col-md-4 mb-4">
                                    <div class="form-group mb-0">
                                       <label for="lastname"><?php echo display("last_name") ?><span  class="text-danger">*</span></label>
                                       <div class="icon-addon addon-md">
                                          <input type="text"  name="lastname" required  class="form-control" id="lastname" style="width:250px;"                                                    placeholder="<?php echo display("last_name") ?>">
                                          <label class="fas fa-user-circle"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <script>
                                    document.getElementById('lastname').addEventListener('input', function() {
                                        var inputValue = this.value;
                                        var regex = /^[a-zA-Z]*$/;
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>

<script>
    document.getElementById('firstname').addEventListener('keyup', updateFullName);
    document.getElementById('lastname').addEventListener('keyup', updateFullName);
    document.getElementById('title').addEventListener('keyup', updateFullName);
 
    function updateFullName() {
        var titleValue = document.getElementById('title').value;
        var firstNameValue = document.getElementById('firstname').value;
        var lastNameValue = document.getElementById('lastname').value;
        
        var fullName = titleValue + ' ' + firstNameValue + ' ' + lastNameValue;
        document.getElementById('c_full_name').value = fullName;
    }
</script>





                                 <div class="col-md-6 align-self-center mb-3">
                                    <div class="form-group mb-0">
                                       <label><?php echo  ("Gender") ?></label>
                                       <div class="icon-addon addon-md">
                                          <select class="form-control"  name="gender"    >
                                             <option selected value="Male">Male</option>
                                             <option value="Female">Female</option>
                                             <option value="Others">Others</option>
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
                                             placeholder="<?php echo display("occupation") ?>">
                                          <label class="fas fa-anchor"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <script>
                                    document.getElementById('occupation').addEventListener('input', function() {
                                        var inputValue = this.value;
                                        var regex = /^[a-zA-Z]*$/;
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>
                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="dob"><?php echo display("dob") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="date" autocomplete="off" name="dob"  
                                             class="form-control datefilter2" id="dob" placeholder="mm/dd/yyyy"/>
                                          <label class="fas fa-calendar-alt"></label>
                                       </div>
                                    </div>
                                 </div>


                                 <script>
    document.getElementById('dob').addEventListener('change', function() {
        var dobValue = this.value;
        document.getElementById('c_dob').value = dobValue;
    });
</script>
 
                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="anniversary"><?php echo display("anniversary") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="date" name="adate" autocomplete="off"  
                                             class="form-control datefilter2" id="anniversary"
                                             placeholder="mm/dd/yyyy" />
                                          <label class="fas fa-calendar-alt"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="nationality"><?php echo display("nationality") ?><span  class="text-danger">*</span></label>
                                       <div class="icon-addon addon-md">
                                          <input type="text" name="nationaliti" class="form-control"
                                             id="nationality" required  placeholder="<?php echo display("nationality") ?>"
                                             value="" />
                                          <label class="fas fa-flag-usa"></label>
                                       </div>
                                    </div>
                                 </div>


                                 <script>
    document.getElementById('nationality').addEventListener('keyup', function() {
        var firstNatValue = this.value;
        document.getElementById('c_nationality').value = firstNatValue;
    });
</script>
 
                                 <script>
                                    document.getElementById('nationality').addEventListener('input', function() {
                                        var inputValue = this.value;
                                        var regex = /^[a-zA-Z]*$/;
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>
                                
                                
                              

                                 <div class="col-md-6 mb-3">
    <div class="form-group mb-0">
        <label for="nationality"><?php echo ("GSTIN") ?></label>
        <div class="icon-addon addon-md" style="display: flex; align-items: center;">
            <select id="toggleInput" class="form-control">
                <option value="no">No</option>
                <option value="yes">Yes</option>
            </select>
            <label class="fas fa-info-circle" style="margin-left: 4px;"></label>
        </div>
        <br>
        <div class="icon-addon addon-md" style="display: flex; align-items: center;">          
        <input type="text" name="gst_no" class="form-control hidden" id="gst_no" placeholder="GSTIN" value="" maxlength="15" />
<script>
document.getElementById('gst_no').addEventListener('input', function (e) {
    // Convert to uppercase
    e.target.value = e.target.value.toUpperCase();
    // Limit to 15 characters
    if (e.target.value.length > 15) {
        e.target.value = e.target.value.slice(0, 15);
    }
});
</script>    
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
                                          <select class="form-control" name="contacttype"   id="contacttype" >
                                             <option selected value="">Choose
                                                <?php echo display("contact_type") ?>
                                             </option>
                                             <option value="walk-in">walk-in</option>
                                             <option value="Online">Online</option>
                                             <option value="House Guest">House Guest</option>
                                             <option value="Referred / Management Guest">Referred / Management Guest</option>
                                          </select>
                                          <label class="fas fa-address-book"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="mobileNo"><?php echo display("mobile_no") ?> <span  class="text-danger">*</span> </label>
                                       <div class="icon-addon addon-md">
                                          <input type="number"    name="phone"   required     class="form-control" id="mobileNo"
                                             placeholder="<?php echo display("mobile_no") ?>.">
                                          <label class="fas fa-mobile"></label>
                                       </div>
                                    </div>
                                 </div>

                                 <script>
    document.getElementById('mobileNo').addEventListener('keyup', function() {
        var firstmobValue = this.value;
        document.getElementById('c_moblie').value = firstmobValue;
    });
</script>



                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="email"><?php echo display("email") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="email" class="form-control" name="email" id="email"
                                             placeholder="example@email.com"     >
                                          <label class="fas fa-envelope"></label>
                                       </div>
                                    </div>
                                 </div>


                                 <script>
    document.getElementById('email').addEventListener('keyup', function() {
        var firstEmailValue = this.value;
        document.getElementById('c_email').value = firstEmailValue;
    });
</script>

 
                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="floatingSelect"><?php echo display("city") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="city"  name="city"  class="form-control" id="city"
                                             placeholder="<?php echo display("city") ?>">
                                          <label class="fas fa-globe-americas"></label>
                                       </div>
                                    </div>
                                 </div>
                                 <script>
                                    document.getElementById('city').addEventListener('input', function() {
                                        var inputValue = this.value;
                                        var regex = /^[a-zA-Z]*$/;
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>








                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="floatingSelect"><?php echo display("state") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="state"  name="state"  class="form-control" id="state"
                                             placeholder="<?php echo display("state") ?>">
                                          <label class="fas fa-globe-americas"></label>
                                       </div>
                                    </div>
                                 </div>


                                 
                                 <script>
                                    document.getElementById('state').addEventListener('input', function() {
                                        var inputValue = this.value;
                                         var regex = /^[a-zA-Z\s]*$/; // Modified regex to include space (\s)
                                        if (!regex.test(inputValue)) {
                                            this.value = inputValue.slice(0, -1);
                                        }
                                    });
                                 </script>


                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="zipcode"><?php echo display("zipcode") ?></label>
                                       <div class="icon-addon addon-md">
                                          <input type="text" name="zipcode" class="form-control" id="zipcode" placeholder="<?php echo display("zipcode") ?>" 
                                       >
                                          <label class="fas fa-code-branch"></label>
                                       </div>
                                    </div>
                             
                                 </div>

                                 <script>
    document.getElementById('zipcode').addEventListener('keyup', function() {
        var firstpincodeValue = this.value;
        document.getElementById('c_pincode').value = firstpincodeValue;
    });
</script>


                            


                                 <div class="col-md-6 mb-3">
                                    <div class="form-group mb-0">
                                       <label for="floatingSelect"><?php echo display("country") ?></label>
                                       <div class="icon-addon addon-md">
                                          <select name="country" class="form-control countries"  id="country" >
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
                                             <option value="India" selected >India</option>
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
                                             <option value="Lebanon" >Lebanon</option>
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


                                 <script>
    document.getElementById('country').addEventListener('change', function() {
        var countryValue = this.value;
        document.getElementById('c_country').value = countryValue;
    });
</script>
 
                                 <div class="col-md-12 mb-3">
                                    <div class="form-floating with-icon">
                                       <textarea class="form-control"
                                          name="address"     placeholder="<?php echo display("address") ?>" id="address"></textarea>
                                       <label for="address"><?php echo display("address") ?></label>
                                       <i class="fas fa-map-pin"></i>
                                    </div>
                                 </div>

                                 <script>
    document.getElementById('address').addEventListener('keyup', function() {
        var firstaddValue = this.value;
        document.getElementById('c_address').value = firstaddValue;
    });
</script>



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
        <label for="pitype"><?php echo display("photo_id_type") ?><span class="text-danger">*</span></label>
        <div class="icon-addon addon-md">
            <select name="id_type" id="pitype" required class="form-control">
                <option value="">Select Identity Type</option>
                <option value="driving_licence">Driving Licence</option>
                <option value="passport">Passport</option>
                <option value="aadhar_card">Aadhar Card</option>
                <option value="pan_card">PAN Card</option>
            </select>
            <label class="fas fa-images"></label>
        </div>
    </div>
</div>
<div class="col-md-6 mb-3">
    <div class="form-group mb-0">
        <label for="pid"><?php echo display("photo_id") ?> # <span class="text-danger">*</span></label>
        <div class="icon-addon addon-md">
            <input type="text" name="national_id" class="form-control" id="pid" placeholder="<?php echo display("photo_id") ?>" maxlength="14"  required>
            <label class="fas fa-images"></label>
        </div>
    </div>
</div>
  
<script>
document.getElementById('pid').addEventListener('input', function (e) {
    e.target.value = e.target.value.toUpperCase();
     if (e.target.value.length > 15) {
        e.target.value = e.target.value.slice(0, 15);
    }
});
</script>
 
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
                                             <!-- <input type="file" accept="image/*" name="imgfront"> -->
                                             <input id="files" name="imgfront" type="file"  />
                                             <output id="result"    >                   
                                          </span>
                                       </label>
                                    </div>
                                 </div>
                                 <div class="col-md-6 mb-3">
                                    <div class="image-upload position-relative overflow-hidden m-auto">
                                       <div class="upload__box">
                                          <div class="upload__btn-box">
                                             <label class="upload__btn">
                                                <p><?php echo display("back_side") ?></p>
                                                <input type="file" name="imgback"    class="upload__inputfile">
                                             </label>
                                          </div>
                                          <div class="upload__img-wrap"></div>
                                       </div>
                                    </div>
                                 </div>
                                 <div class="col-md-12 mb-3">
                                    <div class="form-floating with-icon">
                                       <textarea class="form-control"  name="remarks"  placeholder="Remarks"
                                          id="comments"></textarea>
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
                                       <div class="wrapper">
                                          <div class="box">
                                             <div class="js--image-preview"></div>
                                             <div class="upload-options">
                                                <label>
                                                <input type="file" name="imgguest"  class="image-upload" accept="image/*" />
                                                </label>
                                             </div>

                                          </div>

                                       </div>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <br>







               <div  id="contentToToggle"  style="border:1px solid;margin-left: 10px;margin-top: 13px;" >
                               
                            
                           <div class="card-header py-3">
                                 <h6 class="fs-17 font-weight-600 mb-0" style="text-align:center;" >GUEST REGISTRATION FORM</h6>
                              </div>
                              <div style="margin-left: 35px;">
                                 <img style='margin-left:10px;width:80px;'src="<?php echo base_url();?><?php echo (file_exists($comsettinginfo->invoice_logo)?$comsettinginfo->invoice_logo:'application/modules/ordermanage/assets/images/dmi_logo.jpeg')?>"
                                    class="img img-responsive height-mb" alt=""   >
                                 <address class="mt-10"   style="margin-left:104px;margin-top: -76px  !important;font-size: smaller;">
                                    <strong>St. Thomas Mount International Piligrime Centre <strong style="margin-left:516px;">
                                    <th>Reservation No : 
                                        <input type="text"  name="c_reservation"  id="c_reservation"   style="width:141px; margin-bottom: 7px;margin-left: 5px;" ></th>
                                    <br>
                                    <abbr>
                                       <strong><?php echo ('Hill Top , St.Thomas Mount , Chennai - 600016') ?></strong style="margin-left:132PX;">&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;   &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
                                       <th>Registration No : <input type="text" name="c_reg"  id="c_reg"   style="width:140px;margin-left:2px; margin-bottom: 7px;"></th>
                                    </abbr>
                                    <br>
                                    <abbr>
                                       <strong><?php echo ('Tamilnadu , India.') ?></strong style="margin-left:160PX;"> &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;  &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp;&nbsp;   &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp; &nbsp;  
                                       <th>Room  No : <input type="text"  name="c_roomno" id="c_roomno"  style="width:141px;    margin-left: 39px; margin-bottom: 7px;" ></th>
                                    </abbr>
                                    <br>
                                 </address>
                              </div>



                              <div class="card-body">
                                 <div class="row">
                                    <div class="col-md-6 mb-3">
                                       <table class="c-table" role="table" aria-label="Students" style="margin-top:-60px;margin-left: 31px;" >
                                          <thead>
                                             <tr>
                                                <td>FullName :</td>
                                                <td colspan="9" style="border-left: none;"><input type="text" name="c_full_name"   id="c_full_name" style="width:1012px;  margin-left:-15px;border: none;background-color: aliceblue;" >  </td>
                                             </tr>
                                          </thead>
                                          <tbody>
                                             <tr>
                                                <td colspan="10">Company / Travel Agent Name :<input type="text" name="c_company_taname"  id="c_company_taname"   style="border: none;margin-left: 10px;width: 79%;background-color: aliceblue;" ></td>
                                             </tr>
                                             <tr>
                                                <td> Address :</td>
                                                <td colspan="9" style="border-left: none;"><input type="text" name="c_address"  id="c_address"    style="width:101%;border: none;background-color: aliceblue;margin-left: -3%;" >  </td>
                                             </tr>
                                             <tr>
                                                <td colspan="6"> </td>
                                                <td> Pincode</td>
                                                <td colspan="3"><input type="text"  name="c_pincode"  id="c_pincode"  style="border: none;background-color: aliceblue;width: 169px;" ></td>
                                             </tr>
                                             <tr>
                                                <td>Res /Off :</td>
                                                <td style="border-left: none" > <input type="text" name="c_res_off"  id="c_res_off" style="border: none; margin-left: -28px;background-color: aliceblue;" ></td>
                                                <td>Mobile :</td>
                                                <td style="border-left: none" > <input type="number" name="c_moblie"   id="c_moblie"   style="margin-left: -8%;border: none;background-color: aliceblue;" ></td>
                                                <td>Res :</td>
                                                <td colspan="2"style="border-left: none" > <input type="text" name="c_res"  id="c_res"  style="margin-left: -8%;border: none;background-color: aliceblue;width: 106%; " ></td>
                                                <td  >DOB :</td>
                                                <td colspan="3" style="border-left: none"><input type="text" name="c_dob" id="c_dob"  style="margin-left: -25%;border: none;    width: 128%;background-color: aliceblue;" > </td>
                                             </tr>
                                             <tr>
                                                <td colspan="10"> Mail ID :<input type="email" name="c_email"  id="c_email" oninput="validateEmail(this)"  style="width:360px;border: none;background-color: aliceblue;" > Vehicle No :<input type="text"  name="c_vehicleno"  id="c_vehicleno" style="width:592px;border: none;background-color: aliceblue;" ><span id="validateemails" style="margin-top: 10px;"></span>
  </td>
                                             </tr>
                                           
                                             <tr>
                                                <td>Nationality:</td>
                                                <td style="border-left: none;" ><input type="text" name="c_nationality"  id="c_nationality"  style=" border: none;background-color: aliceblue;    width: 146px;    margin-left: -14px;" > </td>
                                             
                                                <td   colspan="2" >No of Person :</td>
                                                <td style="border-left: none;" ><input type="text" name="c_noofperson"  id="c_noofperson" style= "background-color: aliceblue;    border: none;width: 350%;margin-left: -159px;" > </td>
                                             
                                                <td>Adults :</td>
                                                <td style="border-left: none;" > <input type="text" name="c_adults"  id="c_adults"   style="width: 100%;border: none; margin-left: -15%;background-color: aliceblue;"> </td>
                                                <td>Children :</td>
                                                <td  colspan="2" style="border-left: none;" ><input type="text"  name="c_children" id="c_children"   style="width: 125px;border: none; margin-left: -15px;background-color: aliceblue;"> </td>
                                             </tr>
                                             <tr>
                                                <td  colspan="2" >Arrival Date : <input type="date" name="c_arrival"  id="c_arrival"  style="width:132px;border: none;background-color: aliceblue;" ></td>
                                               
                                                <td colspan="2"> Time : <input type="text" name="c_atime"  id="c_atime"  placeholder="HH:MM AM/PM"  style="width:170px;border: none;background-color: aliceblue;" ></td>
                                                 <td colspan="3">Departure Date : <input type="date" name="c_departutedate"  id="c_departutedate"   style="width:165px;border: none;background-color: aliceblue;" ></td>
                                                 <td  colspan="3" >Time : <input type="text" name="c_dtime"   id="c_dtime"  placeholder="HH:MM AM/PM"  style="width:155px;border: none;background-color: aliceblue;" ></td>
                                        
 
                                                </tr>



                                             <tr>
                                                <td colspan="3" >Arrived Form : <input type="text" name="c_aform" id="c_aform"  style="width:66%;border: none;background-color: aliceblue;" ></td>
                                                 <td colspan="3" style="border-left: none;" > Purpose of Visit : <input type="text" id="c_pov" name="c_pov"  style="width:59%;border: none;background-color: aliceblue;" ></td>
                                                 <td  colspan="4"  style="border-left: none;" >Proceeding To : <input type="text" id="c_proceedingto"  name="c_proceedingto"  style="width:234px;border: none;background-color: aliceblue;" > </td>
                                              </tr>
                                             <tr>
                                                <td style="border-right: none;">Amount :</td>
                                                <td style="border-left: none;"> <input type="text"  name="c_amount"  id="c_amount"  style="width:160px;border: none;background-color: aliceblue;    margin-left: -25px;" > </td>

                                                <td colspan="2" style="border-left: none;">Mode of Payment : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Cash <input type="checkbox" class="mode-of-payment" id="modeofpayment"   name="c_cash"  style="width: 40px;height: 20px;" > </td>
                                                 <td colspan="2" style="border-left: none;">Credit Card <input type="checkbox" class="mode-of-payment" name="c_credit" id="modeofpayment"  style="width: 40px;height: 20px;" >  </td> 
                                                 <td colspan="2"  style="border-left: none;">Company <input type="checkbox" class="mode-of-payment" name="c_company"  id="modeofpayment"   style="width: 40px;height: 20px;"  >  </td>
                                                 <td colspan="2"  style="border-left: none;">  Travel Agent <input type="checkbox" class="mode-of-payment" name="c_travel" id="modeofpayment"    style="width: 40px;height: 20px;" ></td>
                                              </tr>


                                          

                                             <script>
                                                // Add an event listener to each checkbox
                                                document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
                                                    checkbox.addEventListener('change', function() {
                                                        // Set the value of a hidden input based on the checked state of the checkboxes
                                                        document.querySelector('input[name="mode_of_payment"]').value = this.checked ? this.value : '';
                                                    });
                                                });
                                             </script>
                                             <tr >
                                                <td colspan="10"   >
                                                   <span style="margin-left: 470px;">  FOR FOREIGNERS ONLY  </span><br> <span style="margin-left: 500px;" >Form 'c' Rule 14</span>
                                                   <br>
                                                   <!-- <span>Passport No : </span> --> 
                                                   <span>Passport No : </span> <input name="c_passport" id="c_passport"  type="text">  &nbsp; <span  style="margin-left: 501px;" >Country&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : <input type="text" name="c_country" id="c_country"   style="margin-left:3px;width:166px;" > </span>
                                                   <br>
                                                   <br>
                                                   <span>Issued No  &nbsp;&nbsp;&nbsp;&nbsp;: </span> &nbsp;<input type="text" name="c_issued"  id="c_issued"   style="margin-left:-2px;" >  &nbsp; <span style="margin-left: 501px;">Place of Issues&nbsp;&nbsp;:<input type="text"  name="c_poi"  id="c_poi"  style="margin-left: 9px;">  </span>
                                                   <br>
                                                   <br>
                                                   <span>Vaild Till  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: </span>&nbsp;<input type="text" name="c_vaild" id="c_vaild"  style="margin-left:-1px;">  &nbsp; <span style="margin-left: 501px;">Visa Valid Till&nbsp;&nbsp;&nbsp;&nbsp;: <input  name="visa_validtill" id="visa_validtill"  style="margin-left: 3px;
                                                      " type="text"> </span>
                                                   <br>
                                                   <br>
                                                   <span>Proposed Departure from India  : </span> <input type="text" name="c_pdfi" id="c_pdfi"  >  &nbsp;
                                                   <br>
                                                   <br>
                                                   <!-- <span>Whether Empolyed in India  : </span><input type="text" name="c_weii" >   &nbsp;   -->
                                                   <span>Whether Employed in India:</span>
                                                   <label><input type="radio" name="c_weii" value="Yes"> Yes</label>
                                                   <label><input type="radio" name="c_weii" value="No"> No</label>
                                                   &nbsp;
                                                   <br>
                                                   <br>
                                                   <span style="margin-left: 25px;" > Signature of the Office      </span> &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp;  &nbsp; <span style="margin-left:556px;"> i agree to abide by the rules of the Hotel <br><span style="margin-left: 787px;"> and Promise vacate as mentioned above   </span>
                                                </td>
                                             </tr>
                                          </tbody>
                                       </table>
                                       <style>
                                          .c-table {
                                          width: 100%;
                                          border-collapse: collapse;
                                          border-bottom: 2px solid #333;
                                          border-right: 2px solid #333;
                                          caption {}
                                          th,
                                          td {
                                          vertical-align: top;
                                          border-top: 2px solid #333;
                                          border-left: 2px solid #333;
                                          padding: 8px;
                                          }
                                          thead {
                                          th {
                                          /* background: #000; */
                                          /* color: #fff; */
                                          }
                                          }
                                          tbody {
                                          th,
                                          td {}
                                          th {}
                                          td {}
                                          }
                                          }
                                       </style>

<script>
    function addInputListenerToElement(elementId) {
        document.getElementById(elementId).addEventListener('input', function() {
            var inputValue = this.value;
            var regex = /^[a-zA-Z]*$/;
            if (!regex.test(inputValue)) {
                this.value = inputValue.slice(0, -1);
            }
        });
    }
    // Add input listeners to multiple elements
    addInputListenerToElement('c_full_name');
    addInputListenerToElement('c_company_taname');
    addInputListenerToElement('c_nationality');
    addInputListenerToElement('c_proceedingto');
    addInputListenerToElement('c_pov');
    addInputListenerToElement('c_aform');
    addInputListenerToElement('c_poi');
    addInputListenerToElement('c_country');    
    addInputListenerToElement('c_pdfi');    
 
</script>
 
<script>

function addInputListenerToElement(elementId) {
        document.getElementById(elementId).addEventListener('input', function() {
            var inputValue = this.value;
            // var regex = /^[0-9]*$/; // Only allow numeric characters
            var regex = /^[0-9\/\-\.]*$/; // Allow numeric characters, /, -, and .
            if (!regex.test(inputValue)) {
                this.value = inputValue.slice(0, -1);
            }
        });
    }

    // Add input listener to the 'pincode' element
    addInputListenerToElement('c_pincode');
    addInputListenerToElement('c_moblie');
    addInputListenerToElement('c_dob');
    addInputListenerToElement('c_noofperson');
    addInputListenerToElement('c_adults');
    addInputListenerToElement('c_children');
    addInputListenerToElement('c_arrival');
    addInputListenerToElement('c_atime');
    addInputListenerToElement('c_departutedate');
    addInputListenerToElement('c_dtime');
    addInputListenerToElement('c_amount');
    addInputListenerToElement('c_reservation');
    addInputListenerToElement('c_reg');
    addInputListenerToElement('c_roomno');
    addInputListenerToElement('c_res');
   addInputListenerToElement('c_res_off');
    addInputListenerToElement('c_vaild');
    addInputListenerToElement('c_issued');
    addInputListenerToElement('c_passport');
    addInputListenerToElement('visa_validtill');
    addInputListenerToElement('zipcode');

    


// Validate Email
function validateEmail(input) {
         var regex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
         var submitButton = document.getElementById("checkSubmit");
          input.addEventListener("input", function(event) {
              var value = input.value;
              var newValue = '';
              for (var i = 0; i < value.length; i++) {
                  var char = value.charAt(i);
                  if (/[@._A-Za-z0-9-]/.test(char) || event.shiftKey) {
                      newValue += char;
                  }
              }
              input.value = newValue;
              var isValid = regex.test(input.value);
              if (isValid) {
                  // Check if there are additional characters after .com or .in
                  var lastPart = input.value.split('.').pop();
                  if (lastPart !== 'com' && lastPart !== 'in') {
                      isValid = false;
                  }
              }
              if (isValid) {
                  document.getElementById("validateemails").style.color = "green";
                  document.getElementById("validateemails").textContent = "Valid email address";
                  submitButton.disabled = false;
              } else {
                  document.getElementById("validateemails").style.color = "red";
                  document.getElementById("validateemails").textContent = "Invalid email address";
                  submitButton.disabled = true;
              }
          });
      }

    </script>

 
                                    </div>
  










                           </div>
                        </div>
                     </div>
                     <br/> <br/>
                     <div class="form-group text-right" style="margin-left: 10px;position: absolute;">
                        <br>
                        <button type="reset" class="btn btn-primary w-md m-b-5 bbb" onClick="document.location.reload(true)"><?php echo display('reset') ?></button>
                        <button type="submit" class="btn btn-success w-md m-b-5 bbb"   ><?php echo display('ad') ?></button>
                     </div>
                  </div>
               </div>
               <?php echo form_close() ?>
            </div>
         </div>
      </div>
   </div>
   <script src="<?php echo MOD_URL.$module;?>/assets/js/script.js"></script>
   <script>
      window.onload = function(){
              
              //Check File API support
              if(window.File && window.FileList && window.FileReader)
              {
                  var filesInput = document.getElementById("files");
                  
                  filesInput.addEventListener("change", function(event){
                      
                      var files = event.target.files; //FileList object
                      var output = document.getElementById("result");
                      
                      for(var i = 0; i< files.length; i++)
                      {
                          var file = files[i];
                          
                          //Only pics
                          if(!file.type.match('image'))
                            continue;
                          
                          var picReader = new FileReader();
                          
                          picReader.addEventListener("load",function(event){
                              
                              var picFile = event.target;
                              
                              var div = document.createElement("div");
                              
                         
                              
                                      div.innerHTML = "<img style='width:327px;' class='output_multiple_image' src='" + picFile.result + "'" +
                                      "title='" + picFile.name + "'/>";
      
      
      
                              output.insertBefore(div,null);            
                          
                          });
                          
                           //Read the image
                          picReader.readAsDataURL(file);
                      }                               
                     
                  });
              }
              else
              {
                  console.log("Your browser does not support File API");
              }
            }
      
       
      
            jQuery(document).ready(function () {
        ImgUpload();
      });
      
      function ImgUpload() {
        var imgWrap = "";
        var imgArray = [];
      
        $('.upload__inputfile').each(function () {
          $(this).on('change', function (e) {
            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
            var maxLength = $(this).attr('data-max_length');
      
            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            var iterator = 0;
            filesArr.forEach(function (f, index) {
      
              if (!f.type.match('image.*')) {
                return;
              }
      
              if (imgArray.length > maxLength) {
                return false
              } else {
                var len = 0;
                for (var i = 0; i < imgArray.length; i++) {
                  if (imgArray[i] !== undefined) {
                    len++;
                  }
                }
                if (len > maxLength) {
                  return false;
                } else {
                  imgArray.push(f);
      
                  var reader = new FileReader();
                  reader.onload = function (e) {
                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                    imgWrap.append(html);
                    iterator++;
                  }
                  reader.readAsDataURL(f);
                }
              }
            });
          });
        });
      
        $('body').on('click', ".upload__img-close", function (e) {
          var file = $(this).parent().data("file");
          for (var i = 0; i < imgArray.length; i++) {
            if (imgArray[i].name === file) {
              imgArray.splice(i, 1);
              break;
            }
          }
          $(this).parent().parent().remove();
        });
      }
   </script>
   <style>
      @import url(https://fonts.googleapis.com/icon?family=Material+Icons);
      @import url('https://fonts.googleapis.com/css?family=Raleway');
      // variables
      $base-color: cadetblue;
      $base-font: 'Raleway', sans-serif;
      .output_multiple_image{
      width: 120px;
      margin: 10px;    
      }
      .upload {
      &__box {
      padding: 40px;
      }
      &__inputfile {
      width: .1px;
      height: .1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
      }
      &__btn {
      display: inline-block;
      font-weight: 600;
      color: #fff;
      text-align: center;
      min-width: 116px;
      padding: 5px;
      transition: all .3s ease;
      cursor: pointer;
      border: 2px solid;
      background-color: #4045ba;
      border-color: #4045ba;
      border-radius: 10px;
      line-height: 26px;
      font-size: 14px;
      &:hover {
      background-color: unset;
      color: #4045ba;
      transition: all .3s ease;
      }
      &-box {
      margin-bottom: 10px;
      }
      }
      &__img {
      &-wrap {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -10px;
      }
      &-box {
      width: 200px;
      padding: 0 10px;
      margin-bottom: 12px;
      }
      &-close {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background-color: rgba(0, 0, 0, 0.5);
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: center;
      line-height: 24px;
      z-index: 1;
      cursor: pointer;
      &:after {
      content: '\2716';
      font-size: 14px;
      color: white;
      }
      }
      }
      }
      .img-bg {
      background-repeat: no-repeat;
      /* background-position: center; */
      background-size: cover;
      position: relative;
      padding-bottom: 100%;
      }
      body {
      font-family: $base-font;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      background-color: lighten($base-color, 45%);
      }
      .wrapper{
      display: flex;
      flex-direction: row;
      flex-wrap: wrap;
      align-items: center;
      justify-content: center;
      }
      h1 {
      font-family: inherit;
      margin: 0 0 .75em 0;
      color: desaturate($base-color, 15%);
      text-align: center;
      }
      .box {
      display: block;
      min-width: 200px;
      height: 270px;
      margin: 10px;
      background-color: white;
      border-radius: 5px;
      box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
      transition: all 0.3s cubic-bezier(.25,.8,.25,1);
      overflow: hidden;
      }
      .upload-options {
      position: relative;
      height: 75px;
      background-color: $base-color;
      cursor: pointer;
      overflow: hidden;
      text-align: center;
      transition: background-color ease-in-out 150ms;
      &:hover {
      background-color: lighten($base-color, 10%);
      }
      & input {
      width: 0.1px;
      height: 0.1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
      }
      & label {
      display: flex;
      align-items: center;
      width: 100%;
      height: 100%;
      font-weight: 400;
      text-overflow: ellipsis;
      white-space: nowrap;
      cursor: pointer;
      overflow: hidden;
      &::after {
      content: 'add'; 
      font-family: 'Material Icons';
      position: absolute;
      font-size: 2.5rem;
      color: rgba(230, 230, 230, 1);
      top: calc(50% - 2.5rem);
      left: calc(50% - 1.25rem);
      z-index: 0;
      }
      & span {
      display: inline-block;
      width: 50%;
      height: 100%;
      text-overflow: ellipsis;
      white-space: nowrap;
      overflow: hidden;
      vertical-align: middle;
      text-align: center;
      &:hover i.material-icons {
      color: lightgray;        
      }
      }
      }
      }
      .js--image-preview {
      height: 225px;
      width: 100%;
      position: relative;
      overflow: hidden;
      background-image: url('');
      background-color: white;
      background-position: center center;
      background-repeat: no-repeat;
      background-size: cover;
      &::after {
      content: "photo_size_select_actual"; 
      font-family: 'Material Icons';
      position: relative;
      font-size: 4.5em;
      color: rgba(230, 230, 230, 1);
      top: calc(50% - 3rem);
      left: calc(50% - 2.25rem);
      z-index: 0;
      }
      &.js--no-default::after {
      display: none;
      }
      &:nth-child(2) {
      background-image: url('http://bastianandre.at/giphy.gif');
      }
      }
      i.material-icons {
      transition: color 100ms ease-in-out;
      font-size: 2.25em;
      line-height: 55px;
      color: white;
      display: block;
      }
      .drop {
      display: block;
      position: absolute;
      background: transparentize($base-color, .8);
      border-radius: 100%;
      transform:scale(0);
      }
      .animate {
      animation: ripple 0.4s linear;
      }
      @keyframes ripple {
      100% {opacity: 0; transform: scale(2.5);}
      }
      input::-webkit-outer-spin-button,
      input::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
      }
      /* Firefox */
      input[type=number] {
      -moz-appearance: textfield;
      }
   </style>
   <script>
      function initImageUpload(box) {
      let uploadField = box.querySelector('.image-upload');
      
      uploadField.addEventListener('change', getFile);
      
      function getFile(e){
      let file = e.currentTarget.files[0];
      checkType(file);
      }
      
      function previewImage(file){
      let thumb = box.querySelector('.js--image-preview'),
          reader = new FileReader();
      
      reader.onload = function() {
        thumb.style.backgroundImage = 'url(' + reader.result + ')';
      }
      reader.readAsDataURL(file);
      thumb.className += ' js--no-default';
      }
      
      function checkType(file){
      let imageType = /image.*/;
      if (!file.type.match(imageType)) {
        throw 'Datei ist kein Bild';
      } else if (!file){
        throw 'Kein Bild gewählt';
      } else {
        previewImage(file);
      }
      }
      
      }
      
      // initialize box-scope
      var boxes = document.querySelectorAll('.box');
      
      for (let i = 0; i < boxes.length; i++) {
      let box = boxes[i];
      initDropEffect(box);
      initImageUpload(box);
      }
      
      
      
      /// drop-effect
      function initDropEffect(box){
      let area, drop, areaWidth, areaHeight, maxDistance, dropWidth, dropHeight, x, y;
      
      // get clickable area for drop effect
      area = box.querySelector('.js--image-preview');
      area.addEventListener('click', fireRipple);
      
      function fireRipple(e){
      area = e.currentTarget
      // create drop
      if(!drop){
        drop = document.createElement('span');
        drop.className = 'drop';
        this.appendChild(drop);
      }
      // reset animate class
      drop.className = 'drop';
      
      // calculate dimensions of area (longest side)
      areaWidth = getComputedStyle(this, null).getPropertyValue("width");
      areaHeight = getComputedStyle(this, null).getPropertyValue("height");
      maxDistance = Math.max(parseInt(areaWidth, 10), parseInt(areaHeight, 10));
      
      // set drop dimensions to fill area
      drop.style.width = maxDistance + 'px';
      drop.style.height = maxDistance + 'px';
      
      // calculate dimensions of drop
      dropWidth = getComputedStyle(this, null).getPropertyValue("width");
      dropHeight = getComputedStyle(this, null).getPropertyValue("height");
      
      // calculate relative coordinates of click
      // logic: click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center
      x = e.pageX - this.offsetLeft - (parseInt(dropWidth, 10)/2);
      y = e.pageY - this.offsetTop - (parseInt(dropHeight, 10)/2) - 30;
      
      // position drop and animate
      drop.style.top = y + 'px';
      drop.style.left = x + 'px';
      drop.className += ' animate';
      e.stopPropagation();
      
      }
      }
      
      
      
      
      
      
      
      
      
      
      
      
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

        $('.mode-of-payment').change(function(){
            $('.mode-of-payment').not(this).prop('checked', false);
        });
      
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
      
       
      $("#myForm").validate({
      errorLabelContainer: "#messageBox",
      wrapper: "li",
      submitHandler: function() { alert("Submitted!") }
      });

   </script>