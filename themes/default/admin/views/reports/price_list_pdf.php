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
                        <?= lang("Report"); ?>: Price List Report<br>
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
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Current Cost</th>
                            <th>Current Price</th>
                            <th>Last Highest Price</th>
                            <th>Last Lowest Price</th>
                            <th>Recent Price</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($products){?>
                                <?php 
                                foreach($products as $product){?>
                                    <tr>
                                        <td><?= $product->code ?></td>
                                        <td><?= $product->name ?></td>
                                        <td><?= $product->cost ?></td>
                                        <td><?= $product->price ?></td>
                                        <td><?= $product->last_highest_price ? $product->last_highest_price : $product->price  ?></td>
                                        <td><?= $product->last_lowest_price ? $product->last_lowest_price : $product->price  ?></td>
                                        <td><?= $product->recent_price ? $product->recent_price : $product->price  ?></td>
                                    </tr>
                                <?php }
                             ?>

                            <?php }else{ ?>
                                <tr><td colspan="8" class="dataTables_empty">No data available.</td> </tr>
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