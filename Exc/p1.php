<!DOCTYPE html>
<html>
<head>
<title>Trying showing and hiding</title>
<meta charset="utf-8">
<script src="our_scripts.js"></script>
</head>
<body>
<div style="position: absolute; opacity: 0; display: none; width: 400px; height: 200px; left: 100px; top: 100px; padding: 20px; font-family: tahoma; font-size: 12px; color: #fff; background-color: #800;
border-radius: 5px;" id="main_div">Hello there. What is going on?</div>
<input type="button" name="bn1" value="Show and Hide" onclick="show_and_hide_main('main_div', 1000);">
</body>
</html>
