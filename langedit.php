<?php
  include_once('includes\config\init_common.php');

  function load() {
    global $_SHOP;
    $content = array();
    $dir = $_SHOP->includes_dir.DS.'lang';
	  if ($handle = opendir($dir)) {
		   while (false !== ($file = readdir($handle))) {
             if ($file != "." && $file != ".." && !is_dir($dir.$file) && preg_match("/^site_(.*?\w+).inc/", $file, $matches))
                { $content[$matches[1]] = $file ;}
          }
		   closedir($handle);
  	}
    print_r($content );
    return $content;
  }
?>
<html>
	<head>
		<meta http-equiv="Content-Language" content="English" />
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<title>FusionTicket: Language editor </title>
		<link rel="stylesheet" type="text/css" href="css/ui-lightness/jquery-ui-1.7.1.custom.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/style.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/pos.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/formatting.css" media="screen" />
		<link rel="stylesheet" type="text/css" href="css/ingrid.css" media="screen" />
		<script type="text/javascript" src="scripts/jquery/jquery-1.3.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery-ui-1.7.1.custom.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.ajaxmanager.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.form.js"></script>
		<script type="text/javascript" src="scripts/jquery/jquery.validate.min.js"></script>
		<script type="text/javascript" src="scripts/jquery/DD_roundies.js"></script>
    <script type="text/javascript" src="scripts/jquery/jquery.ingrid-0.9.2.js"></script>

		<script type="text/javascript">
       $(document).ready(function() {
          var mycombo = $("#combo");
      		var mygrid1 = $("#table1").ingrid({
      			url: 'remote.php',
      			extraParams: {load: true, lang: 'nl' } ,
      			height: 450,
      			headerHeight: 25,
      			savedStateLoad: true,
      			initialLoad: true,
      			colWidths: [150,475,475],		// width of each column
      			rowClasses: ['grid-row-style1','grid-row-style2'],
      			resizableCols: false,
      			rowSelection: false,
      			paging: false,
      			sorting: false
          });

      		$('#update_1').click(function(){
      			// the 'g' object is ingrid - call methods like so:
      			mygrid1.g.p.setPage(20)
      		});

      		$('#combo').change(function(){
      			// the 'g' object is ingrid - call methods like so:
      			var lang = this.options[this.selectedIndex].value;
            mygrid1.g.load({lang: lang }, function(){
                $('#secLang').text(lang);
                alert( lang);
            }   );
      		});

       });

		</script>
 	</head>
	<body>
  <select id='combo'>
<?Php
  $opt = load();
  $sel['nl'] = " selected='selected' ";
  foreach($opt as $k=>$v) {
    echo "<option value='$k' $sel[$k] >" . $v . "</option>\n";
  }
?>
</select>


    <table id='table1' cellspacing='1' cellpadding='4'>
      <thead>
         <tr>
           <th>Key</th>
           <th>EN</th>
           <th id='secLang'>NL</th>
         </tr>
      </thead>
      <tbody></tbody>
      <tfoot>
        <tr>
          <th>&nbsp;</th>
          <th><button id='update_1'>Update missing</button></th>
          <th><button id='update_2'>Update missing</button></th>
        </tr>
      </tfoot>
    </table>
  </body>
</html>