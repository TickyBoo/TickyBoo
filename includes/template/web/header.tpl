{include file="$_SHOP_theme/header.tpl" name=$name}
{literal}
<script>
function BasicPopup(a)
{
	var url = a.href;
  if (win = window.open(url, a.target || "_blank", 'width=640,height=200,left=300,top=300,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0'))
	 { win.focus();
	   win.focus();
     return false; }
}
</script>
{/literal}