<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    $(document).ready(function () {
        oTable = $('#REData').dataTable({
            "aaSorting": [[2, "desc"], [3, "desc"]],
            "aLengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "<?=lang('all')?>"]],
            "iDisplayLength": <?=$Settings->rows_per_page?>,
            'bProcessing': true, 'bServerSide': true,
            'sAjaxSource': '<?=admin_url('cheque/getCheque' . ($warehouse_id ? '/' . $warehouse_id : '')); ?>',
            'fnServerData': function (sSource, aoData, fnCallback) {
                aoData.push({
                    "name": "<?=$this->security->get_csrf_token_name()?>",
                    "value": "<?=$this->security->get_csrf_hash()?>"
                });
                $.ajax({'dataType': 'json', 'type': 'POST', 'url': sSource, 'data': aoData, 'success': fnCallback});
            },
            'fnRowCallback': function (nRow, aData, iDisplayIndex) {
                nRow.id = aData[0];
                nRow.className = "ocheque_link";
                return nRow;
            },


            "aoColumns": [{"bSortable": false,"mRender": checkbox}, null, null, null, null, null, {"mRender": currencyFormat}, null, null, null, {"bSortable": false}],

            "fnFooterCallback": function (nRow, aaData, iStart, iEnd, aiDisplay) {
                var gtotal = 0;
                for (var i = 0; i < aaData.length; i++) {
                    gtotal += parseFloat(aaData[aiDisplay[i]][6]);
                }
                var nCells = nRow.getElementsByTagName('th');
                nCells[6].innerHTML = currencyFormat(parseFloat(gtotal));
            }
        }).fnSetFilteringDelay().dtFilter([
            {column_number: 1, filter_default_label: "[<?=lang('Type');?>]", filter_type: "text", data: []},
            {column_number: 2, filter_default_label: "[<?=lang('TR Date');?>]", filter_type: "text", data: []},
            {column_number: 3, filter_default_label: "[<?=lang('Deposit Date');?>]", filter_type: "text", data: []},
            {column_number: 4, filter_default_label: "[<?=lang('Code');?>]", filter_type: "text", data: []},
            {column_number: 5, filter_default_label: "[<?=lang('Cheque Number');?>]", filter_type: "text", data: []},
            {column_number: 7, filter_default_label: "[<?=lang('Bank Name');?>]", filter_type: "text", data: []},
            {column_number: 8, filter_default_label: "[<?=lang('Deposited');?>]", filter_type: "text", data: []},
            {column_number: 9, filter_default_label: "[<?=lang('Cheque Name');?>]", filter_type: "text", data: []},
        ], "footer");



        $(document).on('click', '.reedit', function (e) {
            if (localStorage.getItem('reitems')) {
                e.preventDefault();
                var href = $(this).attr('href');
                bootbox.confirm("<?=lang('you_will_loss_return_data')?>", function (result) {
                    if (result) {
                        window.location.href = href;
                    }
                });
            }
        });
    });

</script>

<?php if ($Owner || $GP['bulk_actions']) {
        echo admin_form_open('cheque/cheque_actions', 'id="action-form"');
    }
?>
<div class="box">
    <div class="box-header">
        <h2 class="blue"><i
                class="fa-fw fa fa-random"></i><?=lang('Cheque') ?>
        </h2>

        <div class="box-icon">
            <ul class="btn-tasks">
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="icon fa fa-tasks tip" data-placement="left" title="<?=lang("actions")?>"></i>
                    </a>
                    <ul class="dropdown-menu pull-right tasks-menus" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="<?=admin_url('cheque/add')?>">
                                <i class="fa fa-plus-circle"></i> <?=lang('Add Cheque')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="deposited">
                                <i class="fa fa-money"></i> <?=lang('Deposited')?>
                            </a>
                        </li>
                        <li>
                            <a href="#" id="excel" data-action="export_excel">
                                <i class="fa fa-file-excel-o"></i> <?=lang('export_to_excel')?>
                            </a>
                        </li>
                        <li class="divider"></li>
                        <li>
                            <a href="#" class="bpo" title="<b><?=lang("Delete Cheque")?></b>" data-content="<p><?=lang('r_u_sure')?></p><button type='button' class='btn btn-danger' id='delete' data-action='delete'><?=lang('i_m_sure')?></a> <button class='btn bpo-close'><?=lang('no')?></button>" data-html="true" data-placement="left">
                                <i class="fa fa-trash-o"></i> <?=lang('Delete Cheque')?>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div class="box-content">
        <div class="row">
            <div class="col-lg-12">

                <p class="introtext"><?=lang('list_results');?></p>

                <div class="table-responsive">
                    <table id="REData" class="table table-bordered table-hover table-striped">
                        <thead>
                        <tr>
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th>Type</th>
                            <th>TR Date</th>
                            <th>Deposit Date</th>
                            <th>Cheque Code</th>
                            <th>Cheque Number</th>
                            <th>Amount</th>
                            <th>Bank Name</th>
                            <th>Deposited</th>
                            <th>Account Name</th>
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td colspan="8" class="dataTables_empty"><?= lang("loading_data"); ?></td>
                        </tr>
                        </tbody>
                        <tfoot class="dtFilter">
                        <tr class="active">
                            <th style="min-width:30px; width: 30px; text-align: center;">
                                <input class="checkbox checkft" type="checkbox" name="check"/>
                            </th>
                            <th></th><th></th><th></th><th></th><th></th>
                            <th><?= lang("grand_total"); ?></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="width:80px; text-align:center;"><?= lang("actions"); ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<?php if ($Owner || $GP['bulk_actions']) {?>
    <div style="display: none;">
        <input type="hidden" name="form_action" value="" id="form_action"/>
        <?=form_submit('performAction', 'performAction', 'id="action-form-submit"')?>
    </div>
    <?=form_close()?>
<?php }
?>
