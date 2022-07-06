<?php

/**
 * GeeS_Settings_Page
 */
abstract class GeeS_Settings_Page {

	protected $id    = '';
	protected $label = '';
	protected $ActiveTabs = array();
	protected $ActiveTab = '';

	/**
	 * Constructor
	 */
	public function __construct() {
		$tab = (isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		if (!empty( $tab ) ){
			$this->ActiveTabs["$tab"] = "  nav-tab-active";			
			$this->ActiveTab = $tab;
		}
	}

	public function get_tab( ) {
		$tab = (isset($_GET['tab']) ) ? $_GET['tab'] : 'general';
		if (!empty( $tab ) ){
			return $this->$tab();
		}
	}
	protected function getActiveTab($tab){
		if (is_array($this->ActiveTabs) and isset($this->ActiveTabs[$tab]) and !empty($this->ActiveTabs[$tab]))
			return $this->ActiveTabs[$tab];
	}
	public function message( $error_no, $msg ){
		$error_no = intval($error_no);

		if( ($error_no > 60099) or empty($error_no) ){
			$error_msg = 'Błąd : '.$msg;
			if( $error_no > 60099)
				$error_msg.= ' Nr błędu: '.$error_no;
			echo '<div class="error settings-error">';
			echo '<p><strong>'. $msg .'</strong></p>';
			echo '</div>';
		} else {
			echo '<div class="updated settings-error">';
			echo '<p><strong>'. $msg .'</strong></p>';
			echo '</div>';
		}

	}


	/**
	 * Output the settings
	 */
	public function output() {
		$settings = $this->get_settings();

		//WC_Admin_Settings::output_fields( $settings );
	}

}
