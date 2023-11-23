<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i class="fa-fw fa fa-edit"></i><?= lang('Edit Cheque'); ?></h2>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"><?php echo lang('update_info'); ?></p>
                <?php
                $attrib = array('data-toggle' => 'validator', 'role' => 'form');
                echo admin_form_open_multipart("cheque/edit/".$id, $attrib);
                ?>
                <div class="row">
                    <div class="row">
                    <div class="col-lg-12">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Cheque Type", "type"); ?>
                                    <?php
                                    $type["0"] = "Supplier";
                                    $type["1"] = "Customer";
                                    echo form_dropdown('type', $type, $inv->type, 'id="type" data-placeholder="' . lang("select") . ' ' . lang("Type") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
              
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Deposit Date", "deposit_date"); ?>
                                    <?php echo form_input('deposit_date', $this->sma->dateTime($inv->deposit_date, 'd/m/Y'), 'class="form-control input-tip date" id="deposit_date" required="required"'); ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Transaction Date", "transaction_date"); ?>
                                    <?php echo form_input('transaction_date', $this->sma->dateTime($inv->transaction_date, 'd/m/Y'), 'class="form-control input-tip date" id="transaction_date" required="required"'); ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Amount", "amount"); ?>
                                    <?php echo form_input('amount', $inv->amount, 'type="number" step="0.01" class="form-control input-tip" id="amount" required="required"'); ?>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Cheque Code", "cheque_code"); ?>
                                    <?php echo form_input('cheque_code', $inv->cheque_code, 'class="form-control input-tip" id="cheque_code" required="required"'); ?>
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Cheque Number", "cheque_number"); ?>
                                    <?php echo form_input('cheque_number', $inv->cheque_number, 'type="number" class="form-control input-tip" id="cheque_number" required="required"'); ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Bank Name", "bank_name"); ?>
                                    <?php echo form_input('bank_name', $inv->bank_name, 'class="form-control input-tip" id="bank_name"'); ?>
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Account Name", "account_name"); ?>
                                    <?php echo form_input('account_name', $inv->account_name, 'class="form-control input-tip" id="account_name"'); ?>
                                </div>
                            </div>
           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Account Number", "account_number"); ?>
                                    <?php echo form_input('account_number', $inv->account_name, 'class="form-control input-tip" id="account_number"'); ?>
                                </div>
                            </div>

                        <div class="col-md-4">
                                <div class="form-group">
                                    <?= lang("Deposited", "is_deposited"); ?>
                                    <?php
                                    $deposited[0] = "No";
                                    $deposited[1] = "Yes";
                                    echo form_dropdown('is_deposited', $deposited, $inv->is_deposited, 'id="type" data-placeholder="' . lang("select") . ' ' . lang("is_deposited") . '" required="required" class="form-control input-tip select" style="width:100%;"');
                                    ?>
                                </div>
                            </div>
                        <div class="col-md-12">
                            <div
                                class="fprom-group"><?php echo form_submit('add_return', lang("submit"), 'id="add_return" class="btn btn-primary" style="padding: 6px 15px; margin:15px 0;"'); ?>
                                </div>
                        </div>
                    </div>
                </div>

                <?php echo form_close(); ?>

            </div>

        </div>
    </div>
</div>
