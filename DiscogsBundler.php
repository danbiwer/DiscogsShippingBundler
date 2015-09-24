<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="CSS/DiscogsBundlerStylesheet.css">
<title>Discogs Bundler</title>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="handlersimple.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-67737602-1', 'auto');
  ga('send', 'pageview');

</script>

</head>
<body>

<div id="main">
<div id="inner">
<div style="float:left">
<!-- <h1>Discogs Bundler</h1> -->
	<h1><img src=/Images/dblogo2.png style="width:50%"></h1>

    <p>Input the release or master ID and click add</p>

    Master or Release ID:
    <input type="text" id="disc_id">
   
    <button id="add_list">Add</button>



<h2>List of Items:</h2>
	<div id = "sbox">
	<p id = "empty_message"><i>no items</i></p>
	
    <table id = "listing">
    	<tbody>
    	
    	</tbody>

    </table>
    </div>
    <br>
    	Ships From:
  	<label><input type="radio" name="shipping" value="US" checked="checked"/>US</label>
  	<label><input type="radio" name="shipping" value="UK"/>UK</label>
  	<label><input type="radio" name="shipping" value="ALL" />Anywhere</label>
  	<br><br>
    Email Address:
    <input type="text" id="email">
    <br><br><button id="submit">Submit</button>
    </div>
    <div id="errors">
    	<ul>
    	</ul>
    </div>
    </div>
    </div>
</body>
</html>