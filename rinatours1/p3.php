<html>
<head>
<title>Jquery Animation</title>
<style>
div { width: 500px; height: 400px; background-color: #800; padding: 20px; font-family: tahoma; font-size: 12px; color: #fff; }
</style>
<script src="jquery-1.11.0.min.js"></script>
<script>
$("document").ready(function()
{
	$("#our_button1").click(function()
	{
		$("#our_div").animate({height:'100px'}, 500);
	});
	$("#our_button2").click(function()
	{
		$("#our_div").animate({height:'400px'}, 1000);
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

