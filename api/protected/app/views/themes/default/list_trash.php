<div class="page-header page-header-default animated fadeIn">
    <div class="page-header-content">
        <div class="page-title">
            <h1>
                <i class="<?php echo $module_icon; ?> position-left"></i> <?php echo $module_title; ?> <small><?php echo lang('list_trash'); ?></small>
                <a href="<?php echo $module_url; ?>" class="btn btn-primary btn-labeled btn-sm ml-10 ajaxan" title="<?php echo $module_title; ?>">
                    <b><i class="icon-arrow-left8"></i></b> <?php echo lang('form_back_to_list'); ?>
                </a>
            </h1>
        </div>

        <div class="heading-elements">
            <i class="<?php echo $module_icon; ?>"></i>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><i class="icon-home4 position-left"></i> Home</a></li>
            <li><a href="<?php echo $module_url; ?>" class="ajaxan" title="<?php echo $module_title; ?>"><?php echo $module_title; ?></a></li>
            <li class="active"><a href="<?php echo $module_url; ?>/index_trash" class="ajaxan" title="<?php echo sprintf(lang('list_trash_of'), $module_title); ?>"><?php echo lang('list_trash'); ?></a></li>
        </ul>
    </div>
</div>
<div class="content animated fadeIn">
    <div class="panel panel-default">
        <div id="infoMessage"></div>
        <div class="alert-container">
            <?php
            echo (!empty($success_message)) ? $success_message : '';
            echo (!empty($error_message)) ? $error_message : '';
            ?>
        </div>
        <div class="panel-action row">
            <div class="col-xs-8">
                <?php echo form_dropdown('bulk-action-select', $bulk_actions, NULL, 'id="bulk-action-select" class="form-control"'); ?>
            </div>
            <div class="col-xs-4">
                <a href="#" id="bulk-action-apply" class="btn btn-primary btn-labeled">
                    <b><i class="icon-checkmark4"></i></b> <?php echo lang('list_apply'); ?>
                </a>
            </div>
        </div>
        <table id="table-grid" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th style="width:35px"><input type="checkbox" name="checkAll" value=""></th>
                    <?php
                    if ($table['columns']) {
                        foreach ($table['columns'] as $key => $column) {
                            echo '<th style="width:'.$column['width'].'">'.$column['title'].'</th>';
                        }
                    }
                    ?>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <?php if ($table['columns']): foreach ($table['columns'] as $key => $column): ?>
                    <th>
                        <?php if ($column['filter']['type'] == 'text') : ?>
                        <div class="form-group has-feedback-left">
                            <input type="text" class="form-control" placeholder="filter">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-size-base"></i>
                            </div>
                        </div>
                        <?php elseif ($column['filter']['type'] == 'date') : ?>
                        <div class="form-group has-feedback-left">
                            <input type="text" class="form-control date" placeholder="filter">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-size-base"></i>
                            </div>
                        </div>
                        <?php elseif ($column['filter']['type'] == 'dropdown') : ?>
                            <?php $dropdown_class = (isset($column['filter']['class'])) ? 'form-control '.$column['filter']['class'] : 'form-control'; ?>
                            <?php echo form_dropdown('filter', $column['filter']['dropdown'], NULL, 'class="'.$dropdown_class.'"'); ?>
                        <?php endif; ?>
                    </th>
                    <?php endforeach; endif; ?>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
<?php
$columns = '{"data":"check"},';
if ($table['columns']) {
    foreach ($table['columns'] as $key => $column) {
        $columns .= '{"data":"'.$key.'", "name":"'.$column['name'].'"},';
    }
}
?>
<script type="text/javascript">
var current_url = '<?php echo current_url(); ?>';
var module_url = '<?php echo $module_url; ?>';
var table = $('#table-grid').DataTable({
    "ajax": current_url + "?get_list=true",
    "columns": [<?php echo $columns; ?>],
    "columnDefs": [ { orderable: false, targets: [<?php echo $table['disable_sorting']; ?>] } ],
    "order": [[ <?php echo $table['default_sort_col'].', "'.$table['default_sort_order'].'"'; ?> ]]
});
table.columns().every( function() {
    var that = this;
    var search = $.fn.dataTable.util.throttle(
        function (val) {
            that.search(val).draw();
        },
        1000
    );

    $( 'input', this.footer() ).on( 'keyup', function () {
        search(this.value);
    });
    $( 'input', this.footer() ).on( 'change', function () {
        that.search( this.value ).draw();
    });
    $( 'select', this.footer() ).on( 'change', function () {
        that.search( this.value ).draw();
    });
});
</script>