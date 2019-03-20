function Converter(amts)
{
	this.amts = amts;
}

Converter.PARTIAL_CUPS = { "<sup>3</sup>&frasl;<sub>4</sub>": 3/4,
                           "<sup>2</sup>&frasl;<sub>3</sub>": 2/3,
                           "<sup>1</sup>&frasl;<sub>2</sub>": 1/2,
                           "<sup>1</sup>&frasl;<sub>3</sub>": 1/3,
			               "<sup>1</sup>&frasl;<sub>4</sub>": 1/4 };

Converter.PARTIAL_TSP = { "<sup>7</sup>&frasl;<sub>8</sub>": 7/8,
                          "<sup>3</sup>&frasl;<sub>4</sub>": 3/4,
                          "<sup>5</sup>&frasl;<sub>8</sub>": 5/8,
                          "<sup>1</sup>&frasl;<sub>2</sub>": 1/2,
                          "<sup>3</sup>&frasl;<sub>8</sub>": 3/8,
                          "<sup>1</sup>&frasl;<sub>4</sub>": 1/4,
                          "<sup>1</sup>&frasl;<sub>8</sub>": 1/8 };

(function($)
 {
	Converter.prototype.convert = function(event, units, measure1, ratio1, ratio2, isLiquid, usePacketsRatio)
	{
		units = units || $("#measure1_measures").val();
		units = +units;
		measure1 = (measure1 || $("#measure1_value").val()) * units;
		
		if ( !ratio1 && $("#measure1_measures :selected").text() == "packets" && $("#measure1_sweeteners :selected").data("packets_ratio") )
		{
			ratio1 = $("#measure1_sweeteners :selected").data("packets_ratio");
		}
		
		else
		{
			ratio1 = ratio1 || $("#measure1_sweeteners :selected").data("ratio");
		}
		
		if ( !ratio2 && usePacketsRatio === true && $("#measure2_sweeteners :selected").data("packets_ratio") )
		{
			ratio2 = $("#measure2_sweeteners :selected").data("packets_ratio");
		}
		
		else
		{
			ratio2 = ratio2 || $("#measure2_sweeteners :selected").data("ratio");
		}
		
		isLiquid = isLiquid || !!$("#measure2_sweeteners :selected").data("liquid");
		var measure2 = measure1 * ratio2 / ratio1;
		
		return measure2;
	};
	
	Converter.prototype.formatMeasurement = function(value, isLiquid, unitsRatio)
	{
		var startingValue = value;
		var units = $("#measure1_measures :selected").data("units");
		var packetsDef = $("#measure2_sweeteners :selected").data("packets");
		isLiquid = isLiquid || !!$("#measure2_sweeteners :selected").data("liquid");
		unitsRatio = unitsRatio || $("#measure1_measures").val();
		
		if ( units == "metric" && isLiquid )
		{
			return Math.round(value / unitsRatio) + " mL";
		}
		
		else if ( units == "oz" && isLiquid )
		{
			return Math.round(value / unitsRatio) + " fl oz";
		}
		
		else
		{
			return this.toCups(value, startingValue, packetsDef);
		}
	};
	
	Converter.prototype.toCups = function(value, startingValue, packetsDef)
	{
		var cups = 0, tbsp = 0, tsp = 0;
			
		var rnd = function(n)
		{
			return +n.toFixed(4);
		};

		while ( value >= 1 )
		{
			cups++;
			value -= 1;
		}
		
		for ( var str in Converter.PARTIAL_CUPS )
		{
			if ( rnd(value) >= rnd(Converter.PARTIAL_CUPS[str]) )
			{
				cups = ((cups || "") + " " + str).trim();
				value -= Converter.PARTIAL_CUPS[str];
				break;
			}
		}
		
		while ( rnd(value) >= this.amts.tbsp )
		{
			tbsp++;
			value -= this.amts.tbsp;
		}
		
		while ( rnd(value) >= rnd(this.amts.tsp * 2) )
		{
			tsp += 2;
			value -= this.amts.tsp * 2;
		}
		
		if ( rnd(value) >= rnd(this.amts.tbsp / 2) )
		{
			tbsp = ((tbsp || "") + " <sup>1</sup>&frasl;<sub>2</sub>").trim();
			value -= this.amts.tbsp / 2;
		}
		
		while ( rnd(value) >= rnd(this.amts.tsp) )
		{
			tsp++;
			value -= this.amts.tsp;
		}
		
		for ( var str in Converter.PARTIAL_TSP )
		{
			if ( rnd(value) >= rnd(this.amts.tsp * Converter.PARTIAL_TSP[str]) )
			{
				tsp = ((tsp || "") + " " + str).trim();
				value -= this.amts.tsp * Converter.PARTIAL_TSP[str];
				break;
			}
		}
		
		var out = [];
		
		if ( cups )
		{
			out.push(cups + ((cups === 1 || (cups.indexOf && cups.indexOf("<sup>") === 0)) ? " cup" : " cups"));
		}
		
		if ( tbsp )
		{
			out.push(tbsp + " tbsp");
		}
		
		if ( tsp )
		{
			out.push(tsp + " tsp");
		}
		
		if ( !out.length )
		{
			return "<sup>1</sup>&frasl;<sub>16<sub> tsp";
		}
		
		if ( packetsDef )
		{
			return out.join(" + ") + " (" + this.toPackets(packetsDef) + ")";
		}
		
		return out.join(" + ");
	};
	
	Converter.prototype.toPackets = function(packetsDef)
	{
		var value = this.convert(null, null, null, null, null, false, true); //Convert using packets ratio
		var cupAmt = this.amts.tsp * packetsDef;
		var packetNum = value / cupAmt;
		var packetAmt = Math.ceil(packetNum);
		var packetStr = packetAmt + (packetAmt == 1 ? " packet" : " packets");
		
		if ( packetNum !== packetAmt )
		{
			packetStr = "~" + packetStr;
		}
		
		return packetStr;
	};

	Converter.prototype.evalInput = function()
	{
		var output;
		
		try
		{
			var input = ($("#measure1_value").val() || "").trim().replace(/[^0-9\/. ]/g, "").replace(" ", "+");
			var trailingDecimal = input.substring(input.length - 1) == "."; 
			output = eval(input) || 0;
			if ( trailingDecimal ) { output += ".0"; }
			if ( output == 0 ) { output = "1"; }
			this.lastOutput = output;
		}
		
		catch ( err )
		{
			output = this.lastOutput;
		}
		
		$("#measure1_value").val(output);
		return output;
	};
 })(jQuery);