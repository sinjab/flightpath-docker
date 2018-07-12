<?php
/*
	This is very similar to the fp_template, except this is formatted
	to be used with content which is supposed to get printed out.
*/


$theme_location = fp_theme_location();

// Add extra JS files.    
print $page_extra_js_files;

print "<link rel='stylesheet' type='text/css' href='$theme_location/style.css?$page_css_js_query_string' /> \n";
print "<link rel='stylesheet' type='text/css' href='$theme_location/layout.css?$page_css_js_query_string' /> \n";

print $page_extra_css_files; 

// Load our custom.css last, so we can override whatever needs to be overwritten
print "<link rel='stylesheet' type='text/css' href='$theme_location/custom.css?$page_css_js_query_string' />";


print "<title>$page_title</title> ";

?>
<body style='background-color: white;' class='<?php print $page_body_classes; ?>'>
<!-- TEXT LOGO -->
<table width='500' border='0'>
	  <td valign='middle'>
	    <span style='font-size: 10pt;'><i>
	    electronic student advising system
	    </i></span>
	   </td>
	   <td valign='middle'>
	     <span style='font-family: Times New Roman; font-size: 30pt;'><i>flightpath</i>
	     	 <font color='#660000'><?php print $GLOBALS["fp_system_settings"]["school_initials"]; ?></font></span>
	   </td>
  </table>
<!-- PRINT BUTTON -->
<div style='margin-bottom:10px;' class='print-graphic hand' onClick='window.print();'>
&nbsp;
</div>

<table border='0' width='650' cellspacing='0' cellpadding='0'>
<td valign='top'>
<!-- PAGE CONTENT -->
<?php print $page_content; ?>
</td> 
</table>

</body>