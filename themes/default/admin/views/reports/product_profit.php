<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Product Profit Report') ?>
        </h2>
    </div>
    <div class="box-content">
        <form action="<?= admin_url('reports/profit_based') ?>" >
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
                    <label>Report Based On</label>
                    <select class="form-control" name="based_on">
                        <option value="last_highest_cost" <?= $this->input->get('based_on') == 'last_highest_cost' ? 'selected':  '' ?>>Last Highest Price</option>
                        <option value="last_lowest_cost" <?= $this->input->get('based_on') == 'last_lowest_cost' ? 'selected':  '' ?>>Last Lowest Price</option>
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
</div>