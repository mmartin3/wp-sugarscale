<?php
if ( !$use_short_names )
{
	$use_short_names = $_GET["use_short_names"];
}

$short_names = array("saccharin" => "saccharin sweetener, bulk",
                      "saccharin_packets" => "saccharin sweetener, packets",
					  "aspartame" => "aspartame sweetener, bulk",
					  "aspartame_packets" => "aspartame sweetener, packets",
					  "sucralose" => "sucralose sweetener, bulk",
					  "sucralose_packets" => "sucralose sweetener, packets",
					  "truvia" => "stevia/erythritol sweetener",
					  "truvia_blend" => "stevia baking blend",
					  "monkfruit_raw" => "monk fruit sweetener",
					  "stevia_raw" => "Stevia in the Raw brand sweetener",
					  "stevia_raw_packets" => "Stevia in the Raw brand packets",
					  "stevia_packets" => "stevia sweetener",
					  "acesulfame k" => "acesulfame k");

$caloric_sweeteners = array("sugar" => new CaloricSweetener("sugar", 1, false, 1),
							"brown_sugar" => new CaloricSweetener("brown sugar", 1),
							"honey" => new CaloricSweetener("honey", 2/3, true),
							"coconut_sugar" => new CaloricSweetener("coconut sugar", 1),
							"coconut_nectar" => new CaloricSweetener("coconut nectar", 1, true, false, array("coconut sap")),
							"date_sugar" => new CaloricSweetener("date sugar", 1),
							"maple_sugar" => new CaloricSweetener("maple sugar", 2/3),
							"maple_syrup" => new CaloricSweetener("maple syrup", 0.75, true),
							"molasses" => new CaloricSweetener("molasses", 1 + 1/3, true),
							"dextrose" => new CaloricSweetener("dextrose", 1.42857142857),
							"turbinado" => new CaloricSweetener("Turbinado sugar", 1, false, 2, array("sugar in the raw")),
							"agave" => new CaloricSweetener("agave nectar", 2/3, true),
							"rice_syrup" => new CaloricSweetener("rice syrup", 1 + 1/3, true),
							"barley_syrup" => new CaloricSweetener("barley malt syrup", 1 + 1/3, true),
							"fruit_sweet" => new CaloricSweetener("fruit juice blend", 2/3, true, false, array("fruit sweet")),
							"fructose" => new CaloricSweetener("fructose powder", 1),
							"sorghum" => new CaloricSweetener("sorghum syrup", 2/3, true),
							"yacon" => new CaloricSweetener("yacon syrup", 2/3, true),
							"cane_juice" => new CaloricSweetener("evaporated cane juice", 3/4),
							"sucanat" => new CaloricSweetener("sucanat", 1),
							"jaggery" => new CaloricSweetener("jaggery", 1),
							"muscovado" => new CaloricSweetener("Muscovado sugar", 1),
							"demerara" => new CaloricSweetener("demerara sugar", 1),
							"light_corn_syrup" => new CaloricSweetener("light corn syrup", 1, true, 0, array("karo", "karo syrup")),
							"dark_corn_syrup" => new CaloricSweetener("dark corn syrup", 1, true, 0, array("karo", "karo syrup")),
							"powdered_sugar" => new CaloricSweetener("powdered sugar", 2),
							"beet_sugar" => new CaloricSweetener("beet sugar", 1));

$artificial_sweeteners = array("saccharin" => new ArtificialSweetener("saccharin sweetener, bulk (Sweet 'N Low brand)", 0.166667, false, false, array("sweet n low", "sweet 'n low", "sweet 'n' low", "sweet n' low")),
							   "saccharin_packets" => new ArtificialSweetener("saccharin sweetener, packets (Sweet 'N Low brand)", 0.1875, false, 3/8, array("sweet n low", "sweet 'n low", "sweet 'n' low", "sweet n' low")),
							   "liquid_saccharin" => new ArtificialSweetener("liquid saccharin", 0.125, true, false, array("sweet n low", "sweet 'n low", "sweet 'n' low", "sweet n' low")),
							   "aspartame" => new ArtificialSweetener("aspartame sweetener, bulk (Equal or Nutrasweet brand)", 1, false, false, array("equal", "nutrasweet")),
							   "aspartame_packets" => new ArtificialSweetener("aspartame sweetener, packets (Equal or Nutrasweet brand)", 0.1302083, false, 1/4, array("equal", "nutrasweet")),
							   "sucralose" => new ArtificialSweetener("sucralose sweetener, bulk (SPLENDA brand)", 1, false, false, array("splenda", "equal sucralose", "njoy")),
							   "sucralose_packets" => new ArtificialSweetener("sucralose sweetener, packets (SPLENDA brand)", 0.1875, false, 3/8, array("splenda", "equal sucralose", "njoy")),
							   "liquid_sucralose" => new ArtificialSweetener("liquid sucralose", 0.0104167, true, false, array("splenda zero")),
							   "acesulfame k" => new ArtificialSweetener("acesulfame k (Sweet One or Sunett brand)", 1, false, false, array("sweet one", "sunnett", "acesulfame potassium", "acesulfame k", "ace k", "ace-k")),
							   "pure_aspartame" => new ArtificialSweetener("pure aspartame powder", 0.02296481647669412),
							   "pure_sucralose" => new ArtificialSweetener("pure sucralose powder", 0.00130208291));

$sweetener_blends = array("truvia" => new SweetenerBlend("Truvía brand sweetener (stevia + erythritol)", 0.428, false, 7/8, array("truvia", "stevia", "erythritol", "splenda naturals", "stevia sweetener")),
						  "truvia_nectar" => new SweetenerBlend("Truvía Nectar", 0.354166667, true, false, array("truvia", "stevia", "honey", "sugar", "truvia nectar")),
						  "truvia_blend" => new SweetenerBlend("Truvía brand baking blend", 0.5, false, false, array("truvia", "stevia", "erythritol", "sugar")),
						  "ezsweetz_smf" => new SweetenerBlend("stevia & monk fruit sweetener", 0.0208333, false, false, array("ez sweetz", "ez-sweetz", "ezsweetz", "whole earth nature sweet")),
						  "splenda_blend" => new SweetenerBlend("SPLENDA brand sugar blend", 0.5, false, false, array("sucralose")),
						  "turbinado_stevia" => new SweetenerBlend("Turbinado and stevia blend", 0.5, false, false, array("turbinado sugar")),
						  "erythritol_xylitol" => new SweetenerBlend("erythritol and xylitol sweetener", 1, false, false, array("lite and sweet")),
						  "monksweet" => new SweetenerBlend("monk fruit, stevia & erythritol blend", 2, false, false, array("monksweet")),
						  "swerve" => new SweetenerBlend("Swerve brand erythritol sweetener", 1, false, false, array("oligosaccharides")),
						  "gentle_sweet" => new SweetenerBlend("xylitol, erythritol & stevia blend", 1, false, false, array("gentle sweet")),
						  "xerosweet_plus" => new SweetenerBlend("xylitol & stevia blend", 0.5, false, false, array("xerosweet plus")),
						  "coconut_blend" => new SweetenerBlend("coconut and cane sugar blend", 1, false, false, array("better baking blend")),
						  "trio_sweet" => new SweetenerBlend("inulin, stevia & monk fruit blend", 0.5, false, false, array("trio sweet", "chicory root fiber")),
						  "fructevia" => new SweetenerBlend("fructose, inulin & stevia blend", 0.5, false, false, array("chicory root fiber")));

$sugar_alcohols = array("erythritol" => new SugarAlcohol("erythritol", 1),
						"xylitol" => new SugarAlcohol("xylitol", 1, false, 1, false, array("xylo sweet", "xylosweet")),
						"isomalt" => new SugarAlcohol("isomalt", 1),
						"maltitol" => new SugarAlcohol("maltitol", 1),
						"matltitol_syrup" => new SugarAlcohol("maltitol syrup", 1, true),
						"sorbitol" => new SugarAlcohol("sorbitol", 100/66),
						"confectioner_swerve" => new SugarAlcohol("confectioners style erythritol", 1));

$other = array("stevia_raw" => new Sweetener("stevia sweetener, bulk (Stevia in the Raw brand)", 1/2),
			   "stevia_raw_packets" => new Sweetener("stevia sweetener, packets (Stevia in the Raw brand)", 0.125, false, 1/4),
			   "stevia_packets" => new Sweetener("stevia sweetener (Zing or SweetLeaf brand)", 0.125, false, 1/4, array("zing")),
			   "monkfruit_raw" => new Sweetener("monk fruit sweetener (Monk Fruit In The Raw brand)", 1.0, false, 2),
			   "monkfruit" => new Sweetener("pure monk fruit extract", 0.013888884397963997),
			   "sweetleaf_liquid" => new SweetenerBlend("SweetLeaf brand liquid stevia", 0.202884, true),
			   "imitation_honey" => new Sweetener("imitation honey", 0.8125, true, false, array("maltitol")),
			   "ideal_brown" => new Sweetener("brown sugar alternative", 1, false, false, array("ideal brown", "sukrin gold")),
			   "sugar_free_syrup" => new Sweetener("sugar free maple flavored syrup", 1, true),
			   "stevia_extract" => new CaloricSweetener("stevia extract (powdered)", 0.0208333),
			   "stevia_liquid" => new CaloricSweetener("stevia extract (liquid concentrate)", 0.0208333, true),
			   "inulin" => new Sweetener("inulin sweetener", 1, false, false, array("just like sugar", "chicory root fiber")));

$categories = array("Caloric Sweeteners" => &$caloric_sweeteners,
					"Artificial Sweeteners" => &$artificial_sweeteners,
					"Sugar Alcohols/Polyols" => &$sugar_alcohols,
					"Sweetener Blends" => &$sweetener_blends,
					"Other Sweeteners" => &$other);
					
if ( $use_short_names )
{
	foreach ( $categories as &$category )
	{
		foreach ( $category as $id => &$sweet )
		{
			if ( array_key_exists($id, $short_names) )
			{
				$sweet->name = $short_names[$id];
			}
		}
	}
	
	unset($category);
	unset($sweet);
}
					
if ( !function_exists("sort_sweeteners") )
{
	function sort_sweeteners($a, $b)
	{
		return $a->name == "sugar" ? -100000 : ($b->name == "sugar" ? 100000 : strcasecmp($a->name, $b->name));
	}
}

?>