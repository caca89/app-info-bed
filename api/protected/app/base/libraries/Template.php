<?php (defined('BASEPATH')) or exit('No direct script access allowed');

/**
* eXcode Framework
* A framework for PHP development
*
* @package     eXcode Framework
* @author      eXcellentCODE Dev Team
* @copyright   Copyright (c) 2016, PT. Cipta Adidaya Teknologi (http://excellent-code.com)
*/

class Template
{

    public function __construct()
    {
        $this->app = & get_instance();
    }

    public function _default($default = 'default')
    {
        $this->app->output->set_template($default);

		//<!-- Global stylesheets -->
		// $this->app->load->css("https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900");
		$this->app->load->css("assets/css/icons/icomoon/styles.css");
		$this->app->load->css("assets/css/minified/bootstrap.min.css");
		$this->app->load->css("assets/css/minified/core.min.css");
		$this->app->load->css("assets/css/minified/components.min.css");
		$this->app->load->css("assets/css/minified/colors.min.css");
		$this->app->load->css("assets/css/extras/animate.min.css");
        // $this->app->load->css('assets/js/plugins/bootstrap-fileinput/bootstrap-fileinput.css'); // input file
		$this->app->load->css("assets/css/custom.css");
		//<!-- /global stylesheets -->

		//<!-- Core JS files -->
        $this->app->load->js('assets/js/core/libraries/jquery.min.js');
        $this->app->load->js('assets/js/core/libraries/bootstrap.min.js');
        $this->app->load->js('assets/js/plugins/loaders/pace.min.js');
        $this->app->load->js('assets/js/plugins/forms/jquery.form.min.js');
        $this->app->load->js('assets/js/plugins/forms/validation/validate.min.js');
        $this->app->load->js('assets/js/plugins/forms/styling/uniform.min.js');
        $this->app->load->js('assets/js/plugins/forms/styling/switch.min.js');
        $this->app->load->js('assets/js/plugins/forms/selects/select2.min.js');
        $this->app->load->js('assets/js/plugins/forms/selects/bootstrap_select.min.js');
        $this->app->load->js('assets/js/plugins/forms/inputs/autosize.min.js');
        $this->app->load->js('assets/js/plugins/forms/inputs/touchspin.min.js');
        $this->app->load->js('assets/js/plugins/forms/inputs/formatter.min.js');
        $this->app->load->js('assets/js/plugins/tables/datatables/datatables.min.js');
        $this->app->load->js('assets/js/plugins/tables/datatables/extensions/buttons.min.js');
        // $this->app->load->js('assets/js/plugins/tables/datatables/extensions/jszip/jszip.min.js');
        // $this->app->load->js('assets/js/plugins/tables/datatables/extensions/pdfmake/pdfmake.min.js');
        // $this->app->load->js('assets/js/plugins/tables/datatables/extensions/pdfmake/vfs_fonts.min.js');
        $this->app->load->js('assets/js/plugins/pickers/datepicker.js');
        $this->app->load->js('assets/js/plugins/notifications/bootbox.min.js');
        $this->app->load->js('assets/js/plugins/notifications/noty.min.js');
        $this->app->load->js('assets/js/plugins/notifications/sweet_alert.min.js');
        // $this->app->load->js('assets/js/plugins/ui/nicescroll.min.js');
        $this->app->load->js('assets/js/plugins/ui/ripple.min.js');
        // $this->app->load->js('assets/js/plugins/ui/material.js');
        // $this->app->load->js('assets/js/ckeditor/ckeditor.js');
        // $this->app->load->js('assets/js/ckeditor/adapters/jquery.js');
		// $this->app->load->js('assets/js/plugins/bootstrap-fileinput/bootstrap-fileinput.js');
        //$this->app->load->js('assets/js/pages/layout_fixed_custom.js');
        $this->app->load->js('assets/js/core/app.js');
        // $this->app->load->js('assets/js/core/grid.js');
        $this->app->load->js('assets/js/core/loader.js');
        $this->app->load->js('assets/js/core/helpers.js');
	}

    public function _login($default = 'login')
    {
        $this->app->output->set_template($default);

		//<!-- Global stylesheets -->
		$this->app->load->css("https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900");
		$this->app->load->css("assets/css/icons/icomoon/styles.css");
		$this->app->load->css("assets/css/minified/bootstrap.min.css");
		$this->app->load->css("assets/css/minified/core.min.css");
		$this->app->load->css("assets/css/minified/components.min.css");
		$this->app->load->css("assets/css/minified/colors.min.css");
		$this->app->load->css("assets/css/extras/animate.min.css");
		//<!-- /global stylesheets -->


        $this->app->load->js('assets/js/core/libraries/jquery.min.js');
        $this->app->load->js('assets/js/core/libraries/bootstrap.min.js');
        // $this->app->load->js('assets/js/plugins/loaders/pace.min.js');
        // $this->app->load->js('assets/js/plugins/loaders/blockui.min.js');
        // $this->app->load->js('assets/js/plugins/forms/jquery.form.min.js');
        // $this->app->load->js('assets/js/plugins/forms/validation/validate.min.js');
        // $this->app->load->js('assets/js/plugins/forms/styling/uniform.min.js');
        // $this->app->load->js('assets/js/modules/login.js');

        $this->app->load->js('assets/js/angular/vendors/angular.min.js');
        $this->app->load->js('assets/js/angular/vendors/angular-route.min.js');

        $this->app->load->js('assets/js/angular/package/dirPagination.js');
        $this->app->load->js('assets/js/angular/routes.js');

	}

}
