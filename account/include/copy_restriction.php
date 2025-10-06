<?php
if ($menu["level"] == "ce" || $menu["user_id"] == "40" || $menu["user_id"] == "170") {
?>
	{ extend: 'copyHtml5', footer: true },
	{ extend: 'excelHtml5', footer: true },
	{ extend: 'pdfHtml5', footer: true },
<?php
}
?>