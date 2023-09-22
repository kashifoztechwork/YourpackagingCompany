<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class M_Bundels extends Bundles{

	public function Resources(){

		$this->Style('Common',[
			'lib/iconfonts/mdi/css/materialdesignicons.min.css',
			'lib/iconfonts/puse-icons-feather/feather.css',
			'lib/css/vendor.bundle.base.css',
			'lib/css/vendor.bundle.addons.css',
			'lib/iconfonts/font-awesome/css/font-awesome.min.css',
			'lib/icheck/skins/all.css',
			'css/jquery.nestable.css',
			'css/daterangepicker.css',
			'css/bootstrap-treeview.css',
			'css/style.css'
		]);

		$this->Style('FrontCSS',[
			// 'css/front/bootstrap.min.css',
			// 'css/front/animate.min.css',
			/*'css/front/fontawesome.min.css',*/
			// 'css/front/magnific-popup.min.css',
			// 'css/front/nice-select.min.css',
			// 'css/front/jquery-ui.min.css',
			// 'css/front/flaticon.min.css',
			// 'css/front/slick.min.css',
			//'css/front/style.css',
			'assets/css/bootstrap.min.css',
			'assets/css/animate.css',
			'assets/css/owl.carousel.min.css',
			'assets/css/owl.theme.default.min.css',
			'assets/css/nice-select.css',
			'assets/css/magnific-popup.css',
			'assets/css/style.css'
		]);

    	$this->Style('FontCSS',['https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css'],true);
		//$this->Style('SummerNoteCSS',['https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css'],true);

		$this->Script('Common',[
			'lib/js/vendor.bundle.base.js',
			'lib/js/vendor.bundle.addons.js',
			'js/off-canvas.js',
	        'js/hoverable-collapse.js',
	        'js/misc.js',
	        'js/settings.js',
	        'js/todolist.js',
	        'js/form-validation.js',
	        'js/bt-maxLength.js',
	        'js/iCheck.js',
	        'js/jquery.nestable.js',
	        'js/daterangepicker.js',
	        'js/bootstrap-treeview.js',
	        'js/filters.js',
	        'js/custom.js?v=1'
		]);

		$this->Script('TinyMCE',['https://cdn.tiny.cloud/1/5jy8ob5fhj6aelmjq1zyiny81hn3pntq6fgo2idap7u9zdec/tinymce/6/tinymce.min.js'],true);

		$this->Script('JSTrigger',['https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js'],true);

		//$this->Script('SummerNoteJS',['https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js'],true);

		$this->Script('FrontJS',[
			// 'js/front/bootstrap.min.js',
			// 'js/front/isotope.min.js',
			// 'js/front/appear.min.js',
	  //       'js/front/imageload.min.js',
	  //       'js/front/jquery-ui.min.js',
	  //       'js/front/circle-progress.min.js',
	  //       'js/front/jquery.magnific-popup.min.js',
	  //       'js/front/jquery.nice-select.min.js',
	  //       'js/front/skill.bars.jquery.min.jss',
	  //       'js/front/slick.min.js',
	  //       'js/front/wow.min.js',
	  //       'js/front/main.js',
				'assets/js/jquery-3.5.1.min.js',
				'assets/js/bootstrap.bundle.min.js',
				'assets/js/owl.carousel.min.js',
				'assets/js/mCustomScrollbar.js',
				'assets/js/nice-select.min.js',
				'assets/js/circle-progress.min.js',
				'assets/js/magnific-popup.min.js',
				'assets/js/isotope.pkgd.js',
				'assets/js/masonry.pkgd.min.js',
				'assets/js/countto.js',
				'assets/js/jquery-ui.js',
				'assets/js/parallax.min.js',
				'assets/js/wow.min.js',
				'assets/js/custom.js',
		]);

		$this->Script('Wizard',['js/wizard.js']);
		$this->Script('SignedUp',['js/custom/signup.js']);
		$this->Script('UsersMapping',['js/custom/usersmapping.js']);
	}
}
