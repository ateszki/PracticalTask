<?php
ob_start();
session_start();
define("APPLICATION_ROOT","./");
include_once(APPLICATION_ROOT."config.php");
$contactManager = new contactList();
if (!isset($_GET["page"])){
	$page = 1;
} else {
	$page = $_GET["page"];
}
if (!isset($_GET["rows"])){
	$rows = 10;
} else {
	$rows = $_GET["rows"];
}
if (!isset($_GET["orderby"])){
	$orderby = 'Id';
} else {
	$orderby = $_GET["orderby"];
}

?>
<html>
<head>
	<meta content="text/html; charset=UTF-8" />
	<link href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="stylesheet" />
	<script src="http://code.jquery.com/jquery-1.9.1.js" type="text/javascript"></script>
	<script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js" type="text/javascript"></script>
	<style>
		th{margin:0;padding:5px 20px;border-bottom:solid 1px black; }
		td{margin:0;padding:5px 20px;border-bottom:solid 1px black; }
		tr{background-color:#dedede;}
		thead tr, tfoot tr{background-color:#ccc;}
		tbody tr:hover {background-color:#ccc;}
		tbody tr td {cursor:pointer}
		#contact-dialog{display:none;}
		label{display:block;}
	</style>
</head>
<body>
	<button id='new-contact'>New Contact</button>
	<table border="0" cellpadding="0" cellspacing="0" id="contacts-list">
		<thead>
			<tr>
				<?php
				foreach($contactManager->fields as $f){
					
					if (strpos($orderby,$f) === false){
						$css = '';
					} else {
						$css = 'asc';
						if(strpos($orderby,'DESC')){
							$css='desc';
						}
					}
					echo "<th class='$css' id='$f'>$f</th>";
				}
				?>
			</tr>
		</thead>
		<tbody>
		</tbody>
		<tfoot>
			<tr><td colspan="7" align="center"><button id='bFirst'>|&lt;</button><button id='bPrev'>&lt;&lt;</button> Page: <select id='pages' onchange="loadPage($(this).val(),<?php echo $rows;?>,'<?php echo $orderby; ?>')">
				<?php
				for ($i=1;$i<=$contactManager->countPages($rows);$i++){
					echo "<option>$i</option>";
				}
				?>
				</select><button id='bNext'>&gt;&gt;</button><button id='bLast'>&gt;|</button></td></tr>
				<tr><td  colspan="7"  align='right'>Rows per page: <a href='void(0)' class='rpp'>10</a>, <a href='void(0)' class='rpp'>25</a>, <a href='void(0)' class='rpp'>50</a></td></tr>
		</tfoot>
	</table>
	<div id='contact-dialog' title='Contact Form'>
		<form id='contactForm'>
			<input type='hidden' id='action' name='action' value='save'>
				<?php
				foreach($contactManager->fields as $f){
					
					if ($f == $contactManager->pk){
						echo "<input type='hidden' name='F_$f' id='F_$f'>";
					} else {
						echo "<label for='F_$f'>$f</label><input type='text' name='F_$f' id='F_$f'>";
					}
				}
				?>
				</form>
	</div>
<script>
	$(function(){
		//load page
		loadPage(<?php echo $page?>,<?php echo $rows?>,'<?php echo $orderby?>');
		$("#bLast").click(function(){
			if(pagesCount==1){return false;}
			loadPage(pagesCount,<?php echo $rows?>,'<?php echo $orderby?>');
		});
		$("#bFirst").click(function(){
			if(pagesCount==1){return false;}
			loadPage(1,<?php echo $rows?>,'<?php echo $orderby?>');
		});
		$("#bNext").click(function(){
			if(pagesCount==1){return false;}
			p=parseInt($("#pages").val())+1;
			if(p>pagesCount){
				return false;
			}
			loadPage(p,<?php echo $rows?>,'<?php echo $orderby?>');
		});
		$("#bPrev").click(function(){
			if(pagesCount==1){return false;}
			p=parseInt($("#pages").val())-1;
			if(p<1){
				return false;
			}
			loadPage(p,<?php echo $rows?>,'<?php echo $orderby?>');
		});

		$("a.rpp").click(function(e){
			e.preventDefault();
			changeRPP($(this).text());
		});
		
		$("thead tr th").click(function(){
			if ($(this).hasClass('desc')){
				orderBy($(this).text());
			} else if($(this).hasClass('asc')) {
				orderBy($(this).text()+' DESC ');
			} else {
				orderBy($(this).text());
			}
		});
		
		$("#contact-dialog").dialog({
			autoOpen: false,
			height: 500,
			width: 350,
			modal: true,
			buttons: {
				"Save": function() {
						$("#action").val('save');
						$.ajax({
						type: "POST",
						url: "ajax.crudcontacts.php",
						data: $("#contactForm").serialize(),
						}).done(function( data ) {
							//window.location.reload();
						})
						$( this ).dialog( "close" );
						
				},
				"Delete": function() {
					if(confirm('Are you sure?')){
						$("#action").val('delete');
						$.ajax({
						type: "POST",
						url: "ajax.crudcontacts.php",
						data: $("#contactForm").serialize(),
						}).done(function( data ) {
							window.location.reload();
						});
					}
					$( this ).dialog( "close" );
				},
				Cancel: function() {
					$( this ).dialog( "close" );
				}
			},
			close: function() {
			}
		});
		
		$("#new-contact").click(function(e){
			$("#contactForm input").val('');
			$("#contact-dialog").dialog("open");
		});

		$("tbody").on('click','tr',function(e){
			$("td",$(this)).each(function(){
				var field = $(this).attr('class');
				$("#"+field).val($(this).text());
			});
			$("#contact-dialog").dialog("open");
		});

	});
	var pagesCount = <?php echo $contactManager->countPages($rows); ?>;

	function loadPage(p,r,o){
		$.ajax({
		type: "GET",
		url: "ajax.contactsList.php",
		data: { page: p, rows: r, orderby: o },
		dataType: 'json'
		}).done(function( contacts ) {
			$("#contacts-list tbody").empty();
			$("#pages").val(p);	
			for(contact in contacts){
				c = contacts[contact];
				$("#contacts-list tbody").append('<tr id="'+c.Id+'"><td class="F_Id">'+c.Id+'</td><td class="F_FirstName">'+c.FirstName+'</td><td class="F_LastName">'+c.LastName+'</td><td class="F_Country">'+c.Country+'</td><td class="F_City">'+c.City+'</td><td class="F_Address">'+c.Address+'</td><td class="F_Email">'+c.Email+'</td></tr>');
			}
		});
	}
		
	function orderBy(o){
		var qs = getQueryStrings();
		if (typeof(qs['orderby'])=='undefined'){
			if (JSON.stringify(qs) == '{}'){
				sign='?';
			} else {
				sign = '&';
			}
			window.location.href=location.search+sign+'orderby='+o;
		} else {
			qs['orderby'] = o;
			newurl = '?';
			for(i in qs){
				newurl +=i+'='+qs[i]+'&';
			}
			window.location.href=newurl.substring(0,newurl.length-1);
		}
	}	
		
	function changeRPP(n){
		var qs = getQueryStrings();
		if (typeof(qs['rows'])=='undefined'){
			if (JSON.stringify(qs) == '{}'){
				sign='?';
			} else {
				sign = '&';
			}
			window.location.href=location.search+sign+'rows='+n;
		} else {
			qs['rows'] = n;
			newurl = '?';
			for(i in qs){
				newurl +=i+'='+qs[i]+'&';
			}
			window.location.href=newurl.substring(0,newurl.length-1);
		}
	}	
	
	function getQueryStrings() { 
		//taken from http://stackoverflow.com/questions/2907482/how-to-get-the-query-string-by-javascript
		//usage
		//var qs = getQueryStrings();
		//var myParam = qs["myParam"];
	  
	  var assoc  = {};
	  var decode = function (s) { return decodeURIComponent(s.replace(/\+/g, " ")); };
	  var queryString = location.search.substring(1); 
	  var keyValues = queryString.split('&'); 
	
	  for(var i in keyValues) { 
	    var key = keyValues[i].split('=');
	    if (key.length > 1) {
	      assoc[decode(key[0])] = decode(key[1]);
	    }
	  } 
	
	  return assoc; 
	} 
	

</script>
</body>	
</html>