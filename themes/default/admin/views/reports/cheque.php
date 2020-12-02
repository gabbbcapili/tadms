<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Cheque Payments') ?>
        </h2>
    </div>
    <div class="box-content">
        <form action="<?= admin_url('reports/cheque') ?>" >
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
                <div class="form-group">
                    <label>Type</label>
                    <select class="form-control" name="type">
                        <option value="All" <?= $this->input->get('type') == 'All' ? 'selected':  '' ?>>All</option>
                        <option value="0" <?= $this->input->get('type') == '0' ? 'selected':  '' ?>>Supplier</option>
                        <option value="1" <?= $this->input->get('type') == '1' ? 'selected':  '' ?>>Customer</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label>Deposited?</label>
                    <select class="form-control" name="is_deposited">
                        <option value="All" <?= $this->input->get('is_deposited') == 'All' ? 'selected':  '' ?>>All</option>
                        <option value="1" <?= $this->input->get('is_deposited') == '1' ? 'selected':  '' ?>>Yes</option>
                        <option value="0" <?= $this->input->get('is_deposited') == '0' ? 'selected':  '' ?>>No</option>
                    </select>
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
                            <th>Type</th>
                            <th>TR Date</th>
                            <th>Deposit Date</th>
                            <th>Cheque Code</th>
                            <th>Cheque Number</th>
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
                                        <td><?= $cheque->deposit_date ?></td>
                                        <td><?= $cheque->cheque_code ?></td>
                                        <td><?= $cheque->cheque_number ?></td>
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
</div>