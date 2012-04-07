<?php try { header('HTTP/1.1 500 Internal Server Error'); } catch(\Exception $e) { } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<title>Drawrawr Fatal Error</title>

<style type="text/css">
	/* MuzoEngine In-Line Exception Page CSS. */
	body { margin: 0; padding: 20px; margin-top: 20px; background-color: #eee }
	body, td, th { font: 11px Verdana, Arial, sans-serif; color: #333 }
	a { color: #333 }
	h1 { margin: 0 0 0 10px; padding: 10px 0 10px 0; font-weight: bold; font-size: 120% }
	h2 { margin: 0; padding: 5px 0; font-size: 110% }
	ul { padding-left: 20px; list-style: decimal }
	ul li { padding-bottom: 5px; margin: 0 }
	ol { font-family: monospace; white-space: pre; list-style-position: inside; margin: 0; padding: 10px 0 }
	ol li { margin: -5px; padding: 0 }
	ol .selected { font-weight: bold; background-color: #ddd; padding: 2px 0 }
	table.vars { padding: 0; margin: 0; border: 1px solid #999; background-color: #fff; }
	table.vars th { padding: 2px; background-color: #ddd; font-weight: bold }
	table.vars td  { padding: 2px; font-family: monospace; white-space: pre }
	p.error { padding: 10px; background-color: #f00; font-weight: bold; text-align: center; -moz-border-radius: 10px; }
	p.error a { color: #fff }
	#main { padding: 30px; border: 1px solid #ddd; background-color: #fff; text-align:left; -moz-border-radius: 10px; min-width: 13em; max-width: 52em; z-index: 10; }
	#message { padding: 10px; margin-bottom: 10px; background-color: #eee; -moz-border-radius: 10px }
	pre { padding: 10px; margin-bottom: 10px; background-color: #eee; -moz-border-radius: 10px; overflow: auto; }
</style>
</head>
<body>

<center><div id="main">
	<div style="float: right; padding-top:12px;">
		<img alt="[!]" title="Error" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABAAAAAQCAYAAAAf8/9hAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAgpJREFUeNqUUz1vE0EQnd3LOb6TrRAr+GRiB6EkhQsKAqKgMHRU/A86SgQVFRI/AugA8Q+gI0Uq5IIoBIkgBLZjwMEH8vl8n7vsW3/AGTeZ0/Ptzbz3dub2zOS7Ks1H0gzoxA6vRF68h+dcwby25i+/xXppJ5/hcloQE9KdYCg4gDVy82IEW9RB75NXctv+j42dVQPPX5tuulq1y2c3C/157v8dXGzRaJA+LZYtI180CcAauUXdagOZyBk6L844/ePgVumcReHvRJMq9SIh1zkYOP9ytYGM1W8ESA3fTZ6XHIuh+ODhN7p3t62JyKE25UEDLadYEIXKMZD0Zd/b7HXCG866TTkzOx1yqIEDLjTQcgqVUzA2GfyMnzkVi5mqJkOZMUAONXDGG0KDEdQCONr3Ln8/jq7W1Ox6hyBrgBxq4IA71XFNVE6/3PjJRtUiI5nMiB2m4n6qc6iBAy400LLkZZkOD73G59bozc3GGhlqdNUgsRwnXjZmJmk7JqauVPm+2j2hCzXrer1e2FWnIMjtJ4+3a/aYqAhCML3j/UddDQRy6aQpcKGBln848hvdXrS1dd4iLuQM6d8JSAxFpgYuNNCy17fNVq2Sq25fKhDLqyZtNcOyupuqC0wgcN6T9zKSJPBufEkf3w+p1Y3aS56frjQPhgScNuw8X8cXt6KAv5l9Sr2Pc/gjwACypDzlf5WOvwAAAABJRU5ErkJggg%3D%3D" />
	</div>

	<h1><?php echo (string) get_class($exception); ?> occured ):</h1>
	<h2 id="message"><?php echo $exception->getMessage(); ?></h2>

	<h2>Affected File</h2>
	<pre><?php echo $exception->getFile(); ?> on line <?php echo $exception->getLine(); ?></pre>

	<h2>Stack Trace</h2>
	<pre><?php // echo $exception->getTraceAsString(); ?>[DISABLED]</pre>

	<p id="footer">
		Drawrawr - Development
	</p>
</div></center>
</body>
</html>
