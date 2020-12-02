<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supplier Transactions</title>
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
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                ?>
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= $base64; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-7">
                    <p class="bold">
                        <?= lang("Print Date"); ?>: <?= date('Y-m-d H:i:s'); ?><br>
                        <?= lang("Report"); ?>: Supplier Transactions<br>
                    </p>
                    </div>
                    <div class="col-xs-5">
                    <p class="bold">Report Based From:<br>
                    	<?= $from . ' - ' . $to ?><br>
                    </p>
                     
                    </div>
                </div>
            </div>

            <div class="row" style="margin-bottom:15px;">
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover print-table order-table">
                    <thead>
                        <tr>
                            <th>Company</th>
                            <th>Total</th>
                            <th>Paid</th>
                            <th>Balance</th>
                        </tr>
                        </thead>
                        <tbody>
                        	<?php
                        	$total = 0;
                        	$paid = 0;
                        	$balace = 0;
                        	 ?>
                            <?php if($suppliers){?>
                                <?php 
                                foreach($suppliers as $supplier){?>
                                	<?php
                                	$total += $supplier->total;
                                	$paid += $supplier->paid;
                                	$balance += $supplier->balance;
                                	 ?>
                                    <tr>
                                        <td><?= $supplier->company ?></td>
                                        <td><?= number_format($supplier->total, 2) ?></td>
                                        <td><?= number_format($supplier->paid, 2) ?></td>
                                        <td><?= number_format($supplier->balance, 2) ?></td>
                                    </tr>
                                <?php }
                             ?>

                            <?php }else{ ?>
                                <tr><td colspan="4" class="dataTables_empty">No data available.</td> </tr>
                            <?php } ?>
                            
                        
                        </tbody>
                    <tfoot>
                        <tr>
                            <th>Totals:</th>
                            <th><?= number_format($total, 2) ?></th>
                            <th><?= number_format($paid, 2) ?></th>
                            <th><?= number_format($balance, 2) ?></th>
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