<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cheque Report</title>
    <link href="<?= $assets ?>styles/pdf/bootstrap.min.css" rel="stylesheet">
    <link href="<?= $assets ?>styles/pdf/pdf.css" rel="stylesheet">
</head>

<body>
<style type="text/css">
@media print {
  @page { margin: 0; }
  body { margin: 1.6cm; }
}
</style>
<div class="modal-dialog modal-lg no-modal-header">
    <div class="modal-content">
        <div class="modal-body">
            <?php if ($Settings->logo2) {
                $path = base_url() . 'assets/uploads/logos/' . $Settings->logo2;
                $image_type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $image_type . ';base64,' . base64_encode($data);
                ?>
                <div style="display:inline-block; width:40%;">
                    <img src="<?= $base64; ?>">
                </div>
            <?php } ?>
              <div style="display:inline-block; width:24%;">
                    <p class="bold">
                        <?= lang("Print Date"); ?>: <?= date('Y-m-d H:i:s'); ?><br>
                        <?= lang("Report"); ?>: Cheque Report<br>
                    </p>
              </div>
              <div style="display:inline-block; width:33%;">
                    <p class="bold">
                      Report Based From: <?= $from . ' - ' . $to ?><br>
                      Type: <?= $type ?><br>
                      Deposited?: <?= $is_deposited ?>
                    </p>
                </div>
            <div class="table-responsive">
                <table class="table table-bordered table-hover print-table order-table">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>TR Date</th>
                            <th>Cheque #</th>
                            <th>Deposit Date</th>
                            <th>Amount</th>
                            <th>Bank Name</th>
                            <th>Deposited</th>
                            <th>Account Name</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                             ?>
                            <?php if($cheques){?>
                                <?php 
                                foreach($cheques as $cheque){?>
                                    <?php
                                    $total += $cheque->amount;
                                     ?>
                                    <tr>
                                        <td><?= $cheque->type == 1 ? 'Customer' : 'Supplier' ?></td>
                                        <td><?= $cheque->transaction_date ?></td>
                                        <td><?= $cheque->cheque_number ?></td>
                                        <td><?= $cheque->deposit_date ?></td>
                                        <td><?= number_format($cheque->amount, 2) ?></td>
                                        <td><?= $cheque->bank_name ?></td>
                                        <td><?= $cheque->is_deposited == 1 ? 'Yes' : 'No' ?></td>
                                        <td><?= $cheque->account_name ?></td>
                                        
                                    </tr>
                                <?php }
                             ?>

                            <?php }else{ ?>
                                <tr><td colspan="9" class="dataTables_empty">No data available.</td> </tr>
                            <?php } ?>
                            
                        
                        </tbody>
                        <tfoot>
                            <tr>
                            <th></th><th></th><th></th><th></th><th></th>
                            <th><?= number_format($total, 2) ?></th>
                            <th></th><th></th><th></th>
                        </tr>
                        </tfoot>
                </table>
            </div>      
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready( function() {
        $('.tip').tooltip();
    });
</script>

</body>
</html>