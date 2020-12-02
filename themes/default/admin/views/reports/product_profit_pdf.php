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
                <div class="text-center" style="margin-bottom:20px;">
                    <img src="<?= $base64; ?>">
                </div>
            <?php } ?>
            <div class="well well-sm">
                <div class="row bold">
                    <div class="col-xs-7">
                    <p class="bold">
                        <?= lang("Print Date"); ?>: <?= date('Y-m-d H:i:s'); ?><br>
                        <?= lang("Report"); ?>: Profit based on <?= $based_on_name ?><br>
                    </p>
                    </div>
                    <div class="col-xs-5">
                    <p class="bold">Report Based From:<br>
                        <?= $from . ' - ' . $to ?><br>
                        <?= $based_on_name ?><br>
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
                            <th>Reference</th>
                            <th>Item Code</th>
                            <th>Name</th>
                            <th>Qty</th>
                            <th><?= $based_on_name ? $based_on_name : '--' ?></th>
                            <th>Rate</th>
                            <th>Total Amt</th>
                            <th>Paid Amt</th>
                            <th>Balance</th>
                            <th>TR Date</th>
                            <th>Profit</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($customers){?>
                                <?php 
                                foreach($customers as $customer){?>
                                    <?php
                                    $qty = 0;
                                    $amt = 0;
                                    $profit= 0;
                                     ?>
                                    <tr class="text-primary">
                                        <td colspan="3"><?= $customer['info']->customer?></td>
                                        <td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>
                                    </tr>
                                    <?php foreach($customer['items'] as $item){ ?>
                                        <?php
                                        $qty += $item->quantity;
                                        $amt += $item->subtotal;
                                        $profit += $item->profit;
                                        ?>
                                        <tr>
                                            <td><?= $item->reference_no; ?></td>
                                            <td><?= $item->product_code; ?></td>
                                            <td><?= $item->product_name; ?></td>
                                            <td><?= $item->quantity; ?></td>
                                            <td><?= number_format($item->{$based_on}, 2); ?></td>
                                            <td><?= number_format($item->unit_price, 2); ?></td>
                                            <td><?= number_format($item->subtotal, 2); ?></td>
                                            <td></td>
                                            <td></td>
                                            <td><?= $this->sma->dateTime($item->date, 'Y-m-d'); ?></td>
                                            <td><?= number_format($item->profit, 2); ?></td>
                                        </tr>
                                    <?php }?>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">Totals Per Transaction:</td>
                                        <td><?= number_format($qty, 2) ?></td>
                                        <td></td>
                                        <td></td>
                                        <td><?= number_format($amt, 2) ?></td>
                                        <td><?= number_format($customer['info']->paid, 2) ?></td>
                                        <td class="<?= $customer['info']->balance > 0 ? 'text-danger' : '' ?>"><?= number_format($customer['info']->balance, 2) ?></td>
                                        <td></td>
                                        <td><?= number_format($profit, 2) ?> </td>
                                    </tr>
                                    <tr class="btn-primary">
                                        <td colspan="11"></td>
                                    </tr>
                                <?php }?>

                            <?php }else{ ?>
                                <tr><td colspan="11" class="dataTables_empty">No data available.</td> </tr>
                            <?php } ?>
                        </tbody>
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