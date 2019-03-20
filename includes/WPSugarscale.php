<?php
/**
 * WP-Sugarscale dropdown menu object
 */
class WPSugarscale
{
	protected $params; //Original parameters
	protected $atts;   //Sanitized parameters
	private $ratio;
	private $liquid;
	
	function __construct($atts = array())
	{
		$this->params = $atts;
		$this->atts = $this->sanitize_atts($atts);
	}
	
	/**
	 * Parse sweetener data
	 */
	private function parse_sweeteners($short_names = "1")
	{
		$sweeteners = false;
		$json = wp_remote_get("http://sugarscale.co/sweeteners?json=1&use_short_names=$short_names");
		
		if ( is_array($json) && !empty($json["body"]) )
		{
			$sweeteners = json_decode($json["body"]);
		}

		if ( !$sweeteners ) //Fall back to local files
		{
			$use_short_names = true;
			include_once plugin_dir_path( __FILE__ )."Sweetener.php";
			include plugin_dir_path( __FILE__ )."sweetener_list.php";
			$sweeteners = &$categories;
		}
		
		return $sweeteners;
	}
	
	/**
	 * Get sweetener select menu
	 */
	function get_sweetener_list($action = true)
	{
		$selected = $this->atts["sweetener"];
		$sweeteners = $this->parse_sweeteners($action ? "1" : "0");
		$recommended = false;
		$ratio = &$this->ratio;
		$liquid = &$this->liquid;
		
		if ( empty($selected) )
		{
			$selected = "sugar";
		}
		
		if ( !empty($sweeteners) )
		{
			 $select = "";
			 $select .= "<select class='sweeteners' style='border: 1px solid black !important;'";

			 if ( $action )
			 {
				$select .= " onchange='window.sugarscale.wpConvert(this);return false;'";
			 }
			 
			 else
			 {
				$select .= " style='border:1px solid darkGray;'";
			 }
			 
			 $select .= ">";
			 
			 foreach ( $sweeteners as $category => $list )
			 {
				$select .= "<optgroup label='$category'>";
				
				if ( is_array($list) || is_object($list) )
				{
				   foreach ( $list as $name => $sweetener )
				   {
					  $recommended = false;
					  $select .= "<option value='$name' data-ratio='$sweetener->ratio'";
					  
					  if ( $name == $selected || $sweetener->name == $selected )
					  {
						 $select .= " selected";
						 $sweetener_id = $name;
						 $ratio = $sweetener->ratio;
						 $liquid = $sweetener->liquid;
						 $recommended = true;
					  }
					  
					  $select .= ">$sweetener->name";
					  if ( $recommended && $action ) { $select .= " [recommended]"; }
					  $select .= "</option>";
				   }
				}
				
				$select .= "</optgroup>";
		 }
		 
		 $select .= "</select>";
		 return $select;
		}
	   
	   return $selected;
	}
	
	function get_unit_ratio($units)
	{
		if ( $units == "tbsp" )
		{
			return 0.0625;
		}
		
		if ( $units == "tsp" )
		{
			return 0.0208333333;
		}
		
		return 1;
	}
	
	protected function sanitize_atts($atts)
	{
		$filter_amt = preg_replace("/[^0-9+.\/ ]/", "", $atts["amt"]);
		$filter_amt = str_replace(" ", "+", $filter_amt); //No spaces
		$filter_amt = str_replace("++", "", $filter_amt);
		$atts["amt"] = eval("return $filter_amt;"); //Evaluate things like "1 1/2"
		
		if ( is_string($atts["units"]) )
		{
			$atts["units"] = trim($atts["units"]);
			$atts["units"] = strtolower($atts["units"]);
			$atts["units"] = preg_replace("/[^a-z]/", "", $atts["units"]);
		}
		
		return $atts;
	}
	
	protected function validate_atts($atts, $params)
	{
		$valid_units = array("cup", "cups", "tbsp", "tsp");
		
		if ( !is_numeric($atts["amt"]) )
		{
			return "Error parsing shortcode: could not evaluate $params[amt] as a valid number";
		}
		
		if ( !in_array($atts["units"], $valid_units) )
		{
			return "Error parsing shortcode: unsupported units $params[units] (expecting ".implode("|", $valid_units).")";
		}
		
		return false;
	}

	function format_fractions($amt)
	{
		$amt = " $amt ";
		
		for ( $num = 1; $num <= 32; $num++ )
		{
			for ( $den = 1; $den <= 32; $den++ )
			{
				$amt = str_replace(" $num/$den ", " <sup>$num</sup>&frasl;<sub>$den</sub> ", $amt);
			}
		}
		
		$amt = trim($amt);
		return $amt;
	}
	
	protected function get_main_script()
	{
		return <<<HTML
			window.sugarscale = new CupConverter();
			
			sugarscale.wpConvert = function(sel)
			{
				var span = jQuery(sel).prev();
				var unitRatio = span.data('unitratio');
				var amt = span.data('amt');
				var ratio = span.data('ratio');
				var ratio2 = jQuery(sel).find(':selected').data('ratio');
				var liquid = span.data('liquid');
				var result = sugarscale.convert(null, unitRatio, amt, ratio, ratio2, liquid);
				var fmt = sugarscale.formatMeasurement(result, liquid, unitRatio), unit = '';
				
				if ( fmt.split )
				{
					var tok = fmt.split(' ');
					fmt = tok.slice(0, -1).join(' ');
					unit = tok.slice(-1);
				}
				
				span.children('span').first().html(fmt);
				span.children('span').last().html(unit);
			};
			
			(function($)
			 {
				 $(function()
				  {
					  $('select.sweeteners[onchange]').each(function()
					  {
						  window.sugarscale.wpConvert.call(this, this);
					  }).parents('a').attr('onclick', 'return false;');
				  });
			 })(jQuery);
HTML;
	}
	
	/**
	 * Convert shortcode to HTML
	 */
	function widget()
	{
		$params = $this->params;
		$atts = $this->atts;
		$error = $this->validate_atts($atts, $params);
		$unit_ratio = $this->get_unit_ratio($atts["units"]);
		$main_script = $this->get_main_script();
		
		if ( !$error )
		{
			$sweetener_list = $this->get_sweetener_list();
			
			$script_tags = "<script src='".plugins_url("Converter.js", __FILE__)."'></script>
			<script src='".plugins_url("CupConverter.js", __FILE__)."'></script>
			<script>
			$main_script
			</script>";
			
			$fraction = (strpos($params["amt"], "/") === false) ? "0" : "1";
			$span = "<span style='text-decoration:none !important;color:black !important;white-space: nowrap;' class='wrap_ingredient'>";
			$span .= $script_tags;
			$span .= "<span data-amt='$atts[amt]' data-liquid='$this->liquid' data-unitratio='$unit_ratio' data-units='$atts[units]' data-ratio='$this->ratio'><span";
			$span .= " data-normalized='$atts[amt]' data-fraction='$fraction' data-original='$params[amt]' class='wpurp-recipe-ingredient-quantity'"; //For recipe plugin compatibility
			$span .= ">";
			$span .= $this->format_fractions($params["amt"]);
			$span .= "</span> <span class='units'>$atts[units]</span></span>&nbsp;&nbsp;$sweetener_list</span>";
			return $span;
		}
		
		return $error;
	}
	
	/**
	 * Output contents of dialog
	 */
	function dialog()
	{
		$dialog = "<div style='text-align:center;display:none;margin:10px;' id='sugarscale_dialog_contents'>";
		$dialog .= "<input type=text class='amt' value='1' style='border:1px solid darkGray;width:48px;height:28px;padding:2px;'>&nbsp;&nbsp;";
		$dialog .= "<select class='units' style='border:1px solid darkGray;'><option value='cups' selected>cup(s)</option><option value='tbsp'>tbsp</option><option value='tsp'>tsp</option></select>&nbsp;&nbsp;";
		$dialog .= $this->get_sweetener_list(false);
		$dialog .= "</div>";
		return $dialog;
	}
}
