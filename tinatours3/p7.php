<html>
<head>
<title>Jquery Animation</title>
<style>
div { width: 500px; height: 400px; background-color: #800; padding: 20px; font-family: tahoma; font-size: 12px; color: #fff; display: none; position: relative; left: 20px; top: 50px; }
</style>
<script src="jquery-1.11.0.min.js"></script>
<script>
$("document").ready(function()
{
	$("#our_button1").click(function()
	{
		$("#our_div").slideDown(800, function()
		{
			$("#our_div").animate({width: '250px', height: '200px'}, 800, function()
			{
				$("#our_div").animate({left: '500px', top: '500px'}, 800);
			});
		});
	});
	$("#our_button2").click(function()
	{
		$("#our_div").slideUp(800);
	});
});
</script>
</head>

<body>
<h2>Hello there!</h2>
<p><div id="our_div">Hello there!</div></p>
<p>
<input type="button" name="bn1" value="Click me" id="our_button1">
<input type="button" name="bn1" value="Click me" id="our_button2">
</p>
</body></html>

