<html>

	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>FusionTicket: Language editor </title>
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.1.custom.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/pos.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/formatting.css" media="screen" />
		<link rel="stylesheet" href="css/ingrid.css" type="text/css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.ajaxmanager.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.validate.js"></script>
		<script type="text/javascript" src="scripts/jquery/DD_roundies.js"></script>
    <script type="text/javascript" src="scripts/jquery/jquery.ingrid-0.9.2.js"></script>

		<script type="text/javascript">
       $(document).ready(function() {
		$("#table1").ingrid({
			url: 'remote.php',
			height: 350,
			savedStateLoad: true,
			initialLoad: true,
			colWidths: [150,475,475],		// width of each column
			rowClasses: ['grid-row-style1','grid-row-style2'],
										onRowSelect: function(tr, selected){
											var str 		= selected ? 'SELECTED' : 'UNSELECTED';
											var tr_html	= $(tr).html();
											alert( tr.id + '= '+ str + ' : ' + tr_html);
										},

			resizableCols: false,
			paging: false,
			sorting: false
		});
        });

		</script>
  	</head>
	<body>

<?php
    echo "<table id='table1'>\n";
    echo "<thead><tr><th>Key</th><th>EN</th><th >NL</th></tr></thead><tbody>\n";
    echo "</tbody></table>\n";
?>

</body>