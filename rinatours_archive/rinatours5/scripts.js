var our_div, general_counter, general_flag, change_flag, opacity_counter, general_element, element_how_long;
var i_width, i_height, g_width, g_height, width_diff, height_diff, width_unit, height_unit, check_width, check_height;
change_flag = false;
function load_div()
{
	our_div = document.getElementById("id_contacts_div");
}

function fadein_contacts(session_index, customer_code)
{
	var our_ic;
	general_counter = 0;
	our_ic = Math.random();
	document.name_contacts_iframe.location = "contacts.php?session_index=" + session_index + "&customer_code=" + customer_code + "&ic=" + our_ic;
	our_div.style.display = "block";
	actual_fadein();
}

function fadein_assignments(session_index, customer_code)
{
	var our_ic;
	general_counter = 0;
	our_ic = Math.random();
	document.name_contacts_iframe.location = "assignments.php?session_index=" + session_index + "&customer_code=" + customer_code + "&ic=" + our_ic;
	our_div.style.display = "block";
	actual_fadein();
}

function fadein_last_tasks(session_index, customer_code)
{
	var our_ic;
	general_counter = 0;
	our_ic = Math.random();
	document.name_contacts_iframe.location = "last_assignments.php?session_index=" + session_index + "&customer_code=" + customer_code + "&ic=" + our_ic;
	our_div.style.display = "block";
	actual_fadein();
}

function actual_fadein()
{
	var real_counter;
	general_counter += 10;;
	real_counter = general_counter / 100;
	our_div.style.opacity = real_counter;
	if (general_counter < 100)
	{
		setTimeout("actual_fadein()", 5);
	}
	else
	{
		return true;
	}
}

function fadeout_contacts()
{
	general_counter = 100;
	our_div.style.display = "block";
	actual_fadeout();
}

function actual_fadeout()
{
	var real_counter;
	general_counter -= 10;
	real_counter = general_counter / 100;
	our_div.style.opacity = real_counter;
	if (general_counter > 0)
	{
		setTimeout("actual_fadeout()", 5);
	}
	else
	{
		our_div.style.display = "none";
		return true;
	}
}

function mark_change_flag()
{
	change_flag = true;
}

function goto_main_page(session_index)
{
	var our_ic, our_addr;
	our_ic = Math.random();
	our_addr = "main_page.php?session_index=" + session_index + "&ic=" + our_ic;
	if(change_flag == true)
	{
		if(confirm('Leave without saving?')) { window.location = our_addr; return true; }
	}

	window.location = our_addr;
}

function remove_comment(our_field)
{
	// alert("should remove now...");
	our_field.value = "";
}

function show_and_hide_main(our_element, how_long)
{
	// alert("starting...");
	opacity_counter = 0;
	our_element = document.getElementById(our_element);
	element_how_long = how_long;
	general_element = our_element;
	our_element.style.display = "block";
	actual_show();
}

function actual_show()
{
	var our_opacity
	opacity_counter += 2;
	our_opacity = opacity_counter / 100;
	// alert("our opacity is now: " + our_opacity);
	general_element.style.opacity = our_opacity;
	if(opacity_counter < 100)
	{
		setTimeout("actual_show()", 5);
	}
	else
	{
		show_and_hide_wait();
	}
}

function show_and_hide_wait()
{
	setTimeout("show_and_hide_hide()", element_how_long);
}

function show_and_hide_hide()
{
	actual_hide();
}

function actual_hide()
{
	var our_opacity
	opacity_counter -= 2;
	our_opacity = opacity_counter / 100;
	general_element.style.opacity = our_opacity;
	if(opacity_counter > 0)
	{
		setTimeout("actual_hide()", 5);
	}
	else
	{
		general_element.style.display = "none";
	}	
}

function change_dimentions_main(our_element, initial_width, initial_height, goal_width, goal_height)
{
	our_element = document.getElementById(our_element); general_element = our_element;
	i_width = initial_width; i_height = initial_height; g_width = goal_width; g_height = goal_height;
	width_diff = g_width - i_width; // if (width_diff < 0) { width_diff *= -1; }
	height_diff = g_height - i_height; // if (height_diff < 0) { height_diff *= -1; }
	if (g_width > i_width) { check_width = 1; } else { check_width = 0; }
	if (g_height > i_height) { check_height = 1; } else { check_height = 0; }
	width_unit = width_diff / 100; height_unit = height_diff / 100;
	change_dimentions_actual();
}

function change_dimentions_actual()
{
	var width_px, height_px, width_flag, height_flag;
	i_width += width_unit; width_px = i_width + "px";
	i_height += height_unit; height_px = i_height + "px";
	general_element.style.width = width_px;
	general_element.style.height = height_px;
	if (check_width == 1)
	{
		if (i_width >= g_width)
		{
			width_flag = 1;
		}
		else
		{
			width_flag = 0;
		}
	}
	else
	{
		if (g_width >= i_width)
		{
			width_flag = 1;
		}
		else
		{
			width_flag = 0;
		}
	}
	
	if (check_height == 1)
	{
		if(i_height >= g_height)
		{
			height_flag = 1;
		}
		else
		{
			height_flag = 0;
		}
	}
	else
	{
		if(g_height >= i_height)
		{
			height_flag = 1;
		}
		else
		{
			height_flag = 0;
		}
	}
	if (height_flag == 1 && width_flag == 1)
	{
		return true;
	}
	else
	{
		setTimeout("change_dimentions_actual();", 5);
	}
}
