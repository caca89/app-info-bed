<div class="page-header page-header-default animated fadeInDown">
    <div class="page-header-content">
        <div class="page-title">
            <h1><i class="<?php echo $module_icon; ?> position-left"></i> <?php echo $module_title; ?></h1>
        </div>

        <div class="heading-elements">
            <i class="<?php echo $module_icon; ?>"></i>
        </div>
    </div>

    <div class="breadcrumb-line">
        <ul class="breadcrumb">
            <li><a href="<?php echo site_url(); ?>"><i class="icon-home4 position-left"></i> Home</a></li>
            <li class="active"><?php echo $module_title; ?></li>
        </ul>
    </div>
</div>

<div class="content animated fadeInLeftBig">
    <div class="panel panel-default">
        <div id="infoMessage"></div>
        <div class="alert-container">
            <?php
            echo (!empty($success_message)) ? $success_message : '';
            echo (!empty($error_message)) ? $error_message : '';
            ?>
        </div>
        <div class="panel-action">
            <a href="<?php echo current_url(); ?>/create" class="btn btn-labeled bg-blue ajaxan" title="Tambah <?php echo $module_title; ?>">
                <b><i class="icon-plus2"></i></b> Tambah
            </a>
            <a href="#" class="btn btn-labeled btn-danger" id="btn_delete">
                <b><i class="icon-trash"></i></b> Hapus
            </a>
        </div>
        <table id="table_grid" class="table table-condensed table-striped table-hover">
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
                    <th style="width:100px"></th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th></th>
                    <?php if ($table['columns']): foreach ($table['columns'] as $key => $column): ?>
                    <th>
                        <div class="form-group has-feedback-left">
                            <input type="text" class="form-control" placeholder="filter">
                            <div class="form-control-feedback">
                                <i class="icon-search4 text-size-base"></i>
                            </div>
                        </div>
                    </th>
                    <?php endforeach; endif; ?>
                    <th></th>
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
$columns .= '{"data":"actions"}';
?>
<script type="text/javascript">
var current_url = '<?php echo current_url(); ?>';
var table = $('#table_grid').DataTable({
    "ajax": current_url + "/get_list",
    "columns": [<?php echo $columns; ?>],
    "columnDefs": [ { orderable: false, targets: [<?php echo $table['dsorts']; ?>] } ],
    "order": [[ 1, "asc" ]]
});
table.columns().every( function() {
    var that = this;
    $( 'input', this.footer() ).on( 'keyup change', function () {
        that.search( this.value ).draw();
    });
    $( 'select', this.footer() ).on( 'change', function () {
        that.search( this.value ).draw();
    });
});
</script>
