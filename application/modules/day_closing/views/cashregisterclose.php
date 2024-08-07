<div class="modal-header">
    <h3 class="m-0 p-0" style="text-align:center;"><?php echo display("day_closing"); ?> <span id="rpth">(
            <?php echo date('d M, Y H:i')?> )</span> </h3>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">&times;</span></button>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-bordered table-striped table-hover" id="paymentTable">
                        <thead>
                            <tr>
                                <th><?php echo display('sl_no') ?></th>
                                <th><?php echo 'Payment Method' ?></th>
                                <th><?php echo ('Received') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            if (!empty(totals)) {
                                $sl = 1;
                               foreach ($totals as $paymentType => $totalAmount) {
                                    $total += $totalAmount;
                            ?>
                                    <tr>
                                        <td><?php echo $sl; ?></td>
                                        <td><?php echo $paymentType; ?></td>
                                        <td><?php echo $totalAmount; ?></td>
                                    </tr>
                            <?php
                                    $sl++;
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2" align="right"><?php echo display('total') ?>:</td>
                                <td align="left"><?php echo number_format($total, 3); ?></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
      <?php echo form_open('','method="post" name="cashopen" id="cashopenfrm"')?>
                    <input type="hidden" id="registerid" name="registerid" value="<?php echo $registerinfo->id;?>" />
                    <div class="col-md-12">
                        <div class="form-group row">
                            <label for="4digit"
                                class="col-sm-4 col-form-label"><?php echo display('total_amount');?></label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control" id="totalamount" name="totalamount"
                                    value="<?php echo number_format($total+$registerinfo->opening_balance,3, '.', ''); ?>" />
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="4digit" class="col-sm-4 col-form-label"><?php echo "Note";?></label>
                            <div class="col-sm-7">
                                <textarea id="closingnote" class="form-control" name="closingnote" cols="30" rows="3"
                                    placeholder="Closing Note"></textarea>
                            </div>
                        </div>
                        <!--<div class="form-group text-right">-->
                        <!--    <div class="col-sm-11 pr-0">-->
                        <!--        <button type="button" id="openclosecash" class="btn btn-success w-md m-b-5"-->
                        <!--            ><?php //echo display('add_closing_balance');?></button>-->
                        <!--    </div>-->
                        <!--</div>-->
                    </div>
                    </form>
        <div class="col-sm-12">
            <br/>
            <button type="button" style="font-weight:bold;" id="toggleCashDenominators" class="btn btn-primary mb-3">Cash Denominators</button>
            <button type="button" id="openclosecash" style='margin-top: -18px;' class="btn btn-success w-md m-b-5"
                                    onclick="closecashregister()"><?php echo display('add_closing_balance');?></button>
            <div id="cashDenominators" style="display: none;">
                <table class="table table-bordered table-hover">
                      <thead>
                                <tr>
                                    <th class="text-center"><?php echo display('note_name') ?></th>
                                    <th class="text-center"><?php echo display('pcs') ?></th>
                                    <th class="text-center"><?php echo display('ammount') ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                 <tr>
                                    <td class="2000"><?php echo '2000'; ?></td>
                                    <td><input type="number" class="form-control text_0" name="thousands" onkeyup="cashCalculator()"  onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_0_bal" value="0" readonly=""></span></td>
                                </tr> 
                                <tr>
                                    <td class="1000"><?php echo ('1000') ?></td>
                                    <td><input type="number" class="form-control text_1" name="thousands" onkeyup="cashCalculator()"  onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_1_bal" value="0" readonly=""></span></td>
                                </tr> 
                                <tr>
                                    <td class="500"><?php echo ('500') ?></td>
                                    <td><input type="number" class="form-control text_2" name="fivehnd" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_2_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="100"><?php echo ('100') ?></td>
                                    <td><input type="number" class="form-control text_3" name="hundrad" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_3_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="50"><?php echo ('50') ?></td>
                                    <td><input type="number" class="form-control text_4" name="fifty" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_4_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="20"><?php echo ('20') ?></td>
                                    <td><input type="number" class="form-control text_5" name="twenty" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_5_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="10"><?php echo ('10') ?></td>
                                    <td><input type="number" class="form-control text_6" name="ten" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_6_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="5"><?php echo ('5') ?></td>
                                    <td><input type="number" class="form-control text_7" name="five" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_7_bal" value="0" readonly=""></span></td>
                                </tr>   
                                <tr>
                                    <td class="2"><?php echo ('2') ?></td>
                                    <td><input type="number" class="form-control text_8" name="two" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_8_bal" value="0" readonly=""></span></td>
                                </tr>
                                <tr>
                                    <td class="1"><?php echo ('1') ?></td>
                                    <td><input type="number" class="form-control text_9" name="one" onkeyup="cashCalculator()" onchange="cashCalculator()"></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="text_9_bal" value="0" readonly=""></span></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="2" align="right"><b><?php echo display('grand_total') ?></b></td>
                                    <td><span class='form-control' style='background-color: #eee;'><?php   echo $currency; ?>  
                                    <input type="text" style="background-color: inherit;border: none;" class="total_money" value="0.00" readonly="" name="grndtotal"></span></td>
                                </tr>
                                <?php echo form_close() ?>
                            </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Toggle the visibility of cash denominators
        $('#toggleCashDenominators').click(function() {
            $('#cashDenominators').toggle();
        });
    });
    function cashCalculator() {
         var mul0 = $('.text_0').val();
        var text_0_bal = mul0 * 2000;
        $('.text_0_bal').val(text_0_bal);

        var mul1 = $('.text_1').val();
        var text_1_bal = mul1 * 1000;
        $('.text_1_bal').val(text_1_bal);

        var mul2 = $('.text_2').val();
        var text_2_bal = mul2 * 500;
        $('.text_2_bal').val(text_2_bal);

        var mul3 = $('.text_3').val();
        var text_3_bal = mul3 * 100;
        $('.text_3_bal').val(text_3_bal);

        var mul4 = $('.text_4').val();
        var text_4_bal = mul4 * 50;
        $('.text_4_bal').val(text_4_bal);

        var mul5 = $('.text_5').val();
        var text_5_bal = mul5 * 20;
        $('.text_5_bal').val(text_5_bal);

        var mul6 = $('.text_6').val();
        var text_6_bal = mul6 * 10;
        $('.text_6_bal').val(text_6_bal);

        var mul7 = $('.text_7').val();
        var text_7_bal = mul7 * 5;
        $('.text_7_bal').val(text_7_bal);

        var mul8 = $('.text_8').val();
        var text_8_bal = mul8 * 2;
        $('.text_8_bal').val(text_8_bal);

        var mul9 = $('.text_9').val();
        var text_9_bal = mul9 * 1;
        $('.text_9_bal').val(text_9_bal);


        var total_money = (text_0_bal + text_1_bal + text_2_bal + text_3_bal + text_4_bal + text_5_bal + text_6_bal + text_7_bal + text_8_bal + text_9_bal);

        $('.total_money').val(total_money);
    }

</script>

