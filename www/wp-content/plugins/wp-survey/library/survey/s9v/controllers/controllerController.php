<?php
class s9v_controllers_controllerController extends w11v_Controller_Action_Control {
	public function ControllerMeta()
	{
		return $this->application()->settings()->name.'Controller';
	}
	private $s9v_forms = null;
	public function catchAllAction() {
		$pages = $this->get_path ( get_option ( 'home' ) );
		$count = count ( $pages );
		if ($count > 1) {
			$pi = pathinfo ( $pages [$count - 1] );
			$this->s9v_forms = new s9v_forms ( $this->application (), $pi ['filename'] );
			$this->set_template_folders ( array ($this->s9v_forms->type (), 'Default' ) );
			switch ($pi ['extension']) {
				case 'csv' :
				case 'png' :
					$table = new s9v_table ( strtolower ( $this->s9v_forms->type () . '_' . $pi ['filename'] ) );
					$this->$pi ['extension'] ( $table, $pi ['filename'] );
					break;
				case 'sav' :
					$this->save($pi ['filename']);
					break;
			}
		}
		return;
	}
	private function save($options)
	{
		$this->txt_headers ();
		$settings = $this->s9v_forms->settings ();
		$settings = serialize($settings);
		$settings = gzcompress($settings,9);
		$settings = base64_encode($settings);
		$t=mysql_query("select version() as ve");
		echo mysql_error();
		$r=mysql_fetch_object($t);
		$message="/*\n\tThe only information about your system is that in the diag attributes.\n\tThe diag file only encodes the settings to reduces its size and preserve its integrity when posted and NOT to provide secutiy.\n\tWe recommend removing any personal information from the form before posting.\n*/";
		$settings="{$message}\n[diag form='".$options."' plugin='".$this->application()->Settings()->name."' version='".$this->application()->Settings()->version."' phpversion='".phpversion()."' wpversion='".get_bloginfo( 'version' )."' mysqlversion='".$r->ve."' hash='".md5($settings.$this->application()->Settings()->name)."']\n{$settings}\n[/diag]";
		echo $settings;
	}
	private function csv($table, $page) {
		$this->csv_headers ( $page);
		$data = $table->get ();
		if (count ( $data ) > 0) {
			echo implode ( ',', array_keys ( $data [0] ) ) . "\n";
		}
		foreach ( $data as $datum ) {
			echo implode ( ',', $datum ) . "\n";
		}
	}
	private function png($table, $page) {
		$settings = $this->s9v_forms->settings ();
		//$this->debug($settings);
		$radios = $this->s9v_forms->get_type ();
		foreach ( $radios as $radio ) {
			$details = $table->option_count ( $radio );
			$max = 0;
			if (count ( $details ['Values'] ) >= 1) {
				$max = max ( $details ['Values'] );
			}
			$sum = array_sum ( $details ['Values'] );
			$values = $details ['Values'];
			$labels = implode ( '|', array_reverse ( array_keys ( $details ['Values'] ) ) );
			$labels = array_keys ( $details ['Values'] );
			$name = $details ['Name'];
			$height = ((count ( $details ['Values'] )) * 24) + 60;
			$qs = array ();
			$qs ['cht'] = $settings ['graph']; // chart type.
			//$qs ['cht'] = 'bhs'; // chart type.
			//$qs ['cht'] = 'bvs'; // chart type.
			//$qs ['cht'] = 'p3'; // chart type.
			//$qs ['cht'] = 'p'; // chart type.
			switch ($qs ['cht']) {
				case 'bhs' :
				case 'bvs' :
					$qs ['chbh'] = 'a'; // width , bar seperation, group speration
					switch ($qs ['cht']) {
						case 'bhs' :
							$qs ['chxt'] = "y,x"; // char axis
							$qs ['chxl'] = "0:|" . implode ( '|', array_reverse ( $labels ) ) . "|1:|{$sum} Votes"; // chart axis labels
							break;
						case 'bvs' :
							$qs ['chxt'] = "x,x"; // char axis
							$qs ['chxl'] = "0:|" . implode ( '|', array_reverse ( $labels ) ) . "|1:|{$sum} Votes"; // chart axis labels
							break;
					}
					$qs ['chm'] = "N*p0*,000000,0,,11,,e"; // not sure displays %age though
					$qs ['chxp'] = "1,50"; // chart axis postistion
					$qs ['chxr'] = "0,0,{$max}"; // chart axis range
					$qs ['chxs'] = "1,676767,11.5,0,_,676767"; //turn of x axis
					$values = $this->percent ( $values, 1, '' );
					$qs ['chds'] = "0," . max ( $values ); // chart data scale
					break;
				case 'p' :
				case 'p3' :
					$name .= ' (' . array_sum ( $values ) . ' Votes)';
					$qs ['chl'] = implode ( '|', $this->percent ( $values ) ); // chart data
					$qs ['chdl'] = implode ( '|', $labels ); // chart data
					$qs ['chxt'] = "x"; // char axis
					break;
			}
			$qs ['chtt'] = "{$name}"; // chart name
			$qs ['chd'] = "t:" . implode ( ',', $values ); // chart data
			$qs ['chco'] = str_replace ( "\n", '|', $settings ['colors'] );
			$qs ['chs'] = $settings ['image_width']."x{$height}"; // chart size
			$http = new b11v_Http ( 'http://chart.apis.google.com/chart' );
			$http->method ( 'POST' );
			$http->data ( $qs );
			$http->display ();
		}
	}
	private function percent($array, $scale = 100, $suffix = '%') {
		$sum = array_sum ( $array );
		foreach ( $array as $key => $value ) {
			if ($sum == 0) {
				$array [$key] = '0' . $suffix;
			} else {
				if ($scale == 1) {
					$array [$key] = (($value / $sum) * $scale) . $suffix;
				} else {
					$array [$key] = round ( ($value / $sum) * $scale, 0, PHP_ROUND_HALF_UP ) . $suffix;
				}
			}
		}
		return $array;
	}
	protected function get_path($root = '') {
		$page = strtolower ( $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'] );
		$rootlen = strlen ( str_replace ( 'http://', '', $root ) );
		$page = trim ( substr ( $page, $rootlen ), '/' );
		$pages = explode ( '/', $page );
		return $pages;
	}
}