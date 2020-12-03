<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Product Price List Report') ?>
        </h2>
    </div>
    <div class="box-content">
        <form action="<?= admin_url('reports/price_list') ?>" >
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
            <div class="col-md-4">
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" name="category_id" required>
                        <option value ="all">All</option>
                        <?php foreach($categories as $category): ?>
                            <option value="<?= $category->id ?>"><?= $category->name ?></option>
                        <?php endforeach; ?>
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
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Current Cost</th>
                            <th>Current Price</th>
                            <th>Last Highest Cost</th>
                            <th>Last Lowest Cost</th>
                            <!-- <th>Recent Price</th> -->
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
                                        <td><?= $product->last_highest_cost ? $product->last_highest_cost : $product->cost  ?></td>
                                        <td><?= $product->last_lowest_cost ? $product->last_lowest_cost : $product->cost  ?></td>
                                        <!-- <td><?= $product->recent_price ? $product->recent_price : $product->price  ?></td> -->
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
</div>