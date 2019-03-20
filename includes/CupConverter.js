function CupConverter()
{
	Converter.call(this, { cup: 1, tbsp: 0.0625, tsp: 0.0208333333 });
}

CupConverter.prototype = Converter.prototype;