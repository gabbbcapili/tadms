<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Supplier Transactions') ?>
        </h2>
    </div>
    <div class="box-content">
        <form action="<?= admin_url('reports/suppliers_transactions') ?>" >
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label>Start Date</label>
                    <input type="date" name="from" class="form-control" value="<?= $this->input->get('from') ?>" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>End Date</label>
                    <input type="date" name="to" class="form-control" value="<?= $this->input->get('to') ?>" required>
                </div>
            </div>
        </div><br>
        <div class="row">
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary" name="submit" value="view">View Report</button>
                <button type="submit" class="btn btn-primary" name="submit" value="pdf">Generate PDF</button>
            </div>
        </div>
        </form>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"></p>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover table-striped">
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
</div>