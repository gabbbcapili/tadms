<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                <i class="fa fa-2x">&times;</i>
            </button>
            <button type="button" class="btn btn-xs btn-default no-print pull-right" style="margin-right:15px;" onclick="window.print();">
                <i class="fa fa-print"></i> <?= lang('print'); ?>
            </button>
            <?php if ($logo) { ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= base_url() . 'assets/uploads/logos/' . $biller->logo; ?>"
                         alt="<?= $biller->company != '-' ? $biller->company : $biller->name; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-6">
                    <p class="bold">
                        <?= lang("Deposit Date"); ?>: <?= $this->sma->hrld($cheque->deposit_date); ?><br>
                        <?= lang("Transaction Date"); ?>: <?= $this->sma->hrld($cheque->transaction_date); ?><br>
                        <?= lang("type"); ?>: <?= $cheque->type == 1 ? 'Customer' : 'Supplier'; ?><br>
                        <?= lang("type"); ?>: <?= $cheque->is_deposited == 1 ? 'Yes' : 'No'; ?><br>
                        <?= lang("Amount"); ?>: <?= $cheque->amount ?><br>
                    </p>
                    </div>
                    <div class="col-xs-6">
                       <p class="bold">
                        <?= lang("Cheque Code"); ?>: <?= $cheque->cheque_code; ?><br>
                        <?= lang("Cheque Number"); ?>: <?= $cheque->cheque_number; ?><br>
                        <?= lang("Bank Name"); ?>: <?= $cheque->bank_name ?><br>
                        <?= lang("Account Name"); ?>: <?= $cheque->account_name; ?><br>
                        <?= lang("Account Number"); ?>: <?= $cheque->account_number; ?><br>
                    </p>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row" style="margin-bottom:15px;">

                <div class="col-xs-6">
                    <?php echo $this->lang->line("Created By"); ?>:<br/>
                    <h2 style="margin-top:10px;"><?= $created_by->first_name . " " . $created_by->last_name ?></h2>
                        Created At: <?= $cheque->created_at ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>
