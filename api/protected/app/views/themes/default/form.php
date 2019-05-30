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
    <form id="form" class="form-horizontal" method="post" action="<?php echo $module_url.'/save'; ?>">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title"><?php echo $form['title']; ?></h4>
                <div class="heading-elements">
                    <div class="heading-btn">
                        <a href="<?php echo $module_url; ?>" class="btn btn-labeled btn-danger ajaxan" title="<?php echo $module_title; ?>">
                            <b><i class="icon-undo"></i></b>Kembali
                        </a>
                        <button type="submit" class="btn btn-labeled bg-primary" id="form_submit">
                            <b><i class="icon-floppy-disk"></i></b> Simpan
                        </button>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div id="infoMessage"></div>
                <?php echo $form['output']; ?>
            </div>
            <div class="panel-footer">
                <div class="pull-right">
                    <a href="<?php echo $module_url; ?>" class="btn btn-labeled btn-danger ajaxan" title="<?php echo $module_title; ?>">
                        <b><i class="icon-undo"></i></b>Kembali
                    </a>
                    <button type="submit" class="btn btn-labeled bg-primary" id="form_submit">
                        <b><i class="icon-floppy-disk"></i></b> Simpan
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script type="text/javascript">
    var current_url = '<?php echo current_url(); ?>';
</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/core/form.js'); ?>"></script>