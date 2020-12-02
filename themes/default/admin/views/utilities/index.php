<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Product Utilities') ?>
        </h2>
    </div>
    <div class="box-content">
        <form action="<?= admin_url('utilities/product') ?>" >
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
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
        </form>
    </div>
    <div class="box-content">
        <?php
            $attrib = array('data-toggle' => 'validator', 'role' => 'form');
         echo admin_form_open_multipart("utilities/updateProducts", $attrib); ?>
        <div class="row">
            <div class="col-lg-12">
                <p class="introtext"></p>
                <div class="table-responsive">
                    <table id="REData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th>Product Code</th>
                            <th>Name</th>
                            <th>Current Cost</th>
                            <th>Last Highest Cost</th>
                            <th>Last Lowest Cost</th>
                            <th>Current Price</th>
                            <th>Last Highest Price</th>
                            <th>Last Lowest Price</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php if($products){?>
                                <input type="hidden" name="to" value="<?= $this->input->get('to') ?>">
                                <input type="hidden" name="from" value="<?= $this->input->get('from') ?>">
                                <?php 
                                foreach($products as $product){?>
                                    <tr>
                                        <td><div class="text-center"><input class="checkbox multi-select" type="checkbox" name="product[<?= $product->id ?>][checkbox]"></div></td>
                                        <td><?= $product->code ?></td>
                                        <td><?= $product->name ?></td>
                                        <td><input class="form-control" type="number" name="product[<?= $product->id ?>][cost]" value="<?= $product->cost ?>"></td>
                                        <td><?= $product->last_highest_cost ? $product->last_highest_cost : $product->cost  ?></td>
                                        <td><?= $product->last_lowest_cost ? $product->last_lowest_cost : $product->cost  ?></td>
                                        <td><input class="form-control" type="number" name="product[<?= $product->id ?>][price]" value="<?= $product->price ?>"></td>
                                        <td><?= $product->last_highest_price ? $product->last_highest_price : $product->price  ?></td>
                                        <td><?= $product->last_lowest_price ? $product->last_lowest_price : $product->price  ?></td>
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
        <?php if($products){ ?>
            <div class="row">
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-primary">Update Products</button>
                    </div>
            </div>
        <?php } ?>
        </form>
    </div>
</div>