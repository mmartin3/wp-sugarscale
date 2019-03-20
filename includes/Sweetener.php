<?php
class Sweetener
{
	function __construct($name, $ratio = 1, $liquid = false, $packets = 0, $tags = NULL)
	{
		$this->name = $name;
		$this->ratio = $ratio;
		$this->liquid = $liquid;
		$this->packets = $packets;
		$this->tags = $tags;
	}
}

class CaloricSweetener extends Sweetener {}
class ArtificialSweetener extends Sweetener {}
class SugarAlcohol extends Sweetener {}
class SweetenerBlend extends Sweetener {}

?>