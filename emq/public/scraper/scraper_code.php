<!DOCTYPE HTML>
<html>  
<body>


<?php

//	MODIFY BOTH OF THESE VALUES
const CATEGORY = "PC & Accessories";
const SAVE2FILE = "products.txt";



$results = "";
$data = "";
$i = "";

$t = "";
$c = "";
$b = "";
$p = "";
$desc = "";

if(isset($_POST["url"] )){

// START OF DATA SCRAPING

	$doc = new DOMDocument;

	// We don't want to bother with white spaces
	$doc->preserveWhiteSpace = false;

	// Most HTML Developers are chimps and produce invalid markup...
	$doc->strictErrorChecking = false;
	$doc->recover = true;
	libxml_use_internal_errors(1);
	$doc->formatOutput = True;

	$homepage = file_get_contents( trim($_POST["url"]) );

	$doc->loadHTML( $homepage );
	$xpath = new DOMXPath( $doc );
	// Returns Price

	//$p = trim( $xpath->query( '//span[@id="priceblock_ourprice"]' )[0]->nodeValue );
	foreach( $xpath->query( '//span[@id="priceblock_ourprice"]' ) as $node ){
		$p = addslashes( trim($node->nodeValue) );
	}
	if(!$p){
			foreach( $xpath->query( '//span[@id="priceblock_saleprice"]' ) as $node ){
				$p = addslashes( trim($node->nodeValue) );
			}
	}
	if(!$p){
			foreach( $xpath->query( '//span[@id="priceblock_dealprice"]' ) as $node ){
				$p = addslashes( trim($node->nodeValue) );
			}
	}
	// Returns Brand
	foreach( $xpath->query( '//a[@id="brand"]' ) as $node ){
		$b = addslashes( trim($node->nodeValue) );
	}
	//$b = addslashes( trim( $xpath->query( '//a[@id="brand"]' )[0]->nodeValue ) );

	// Returns Product Title
	foreach( $xpath->query( '//span[@id="productTitle"]' ) as $node ){
		$t = addslashes( trim($node->nodeValue) );
	}
	//$t = addslashes( trim( $xpath->query( '//span[@id="productTitle"]' )[0]->nodeValue ) );

	echo "===============================================<br>";
	$counter = 0;


	$desc = "<ul>";
	foreach( $xpath->query( '//div[@id="feature-bullets"]//li//span[@class="a-list-item"]' ) as $node )
	{
		if ($counter++ == 0) continue; //skip first item which seems to be some default printout item.
	    $desc .= "<li>".addslashes( trim($node->nodeValue) )."</li>";
	    //echo trim( $node->nodeValue ) . '<br>';
	}
	$desc .= "</ul>";









// END OF DATA SCRAPING


	//trim removes trailing whitespaces
	/*
	$t = str_replace('Click to open expanded view', '', $_POST["title"]);
	$t = addslashes (trim($t));
	$c = addslashes (trim($_POST["category"]));
	$b = addslashes (trim($_POST["brand"]));
	$p = addslashes (trim($_POST["price"]));
	*/
	$c = CATEGORY;

	$money = array("$", ",");
	$p = str_replace($money, '', $p); //strip dollar sign 
	//$d = addslashes (trim($_POST["desc"]));

	$i = random_text().".jpg";//generate random image name

	//print results
	$results = "Title: [[".$t . "]]<br>";
	$results .= "Category: [[".$c . "]]<br>";
	$results .= "Brand: [[".$b . "]]<br>";
	$results .= "Price: [[".$p . "]]<br>";

	/*
	$desc = "<ul>";
	foreach(preg_split("/((\r?\n)|(\r\n?))/", $d ) as $line){
    $desc .= "<li>".$line."</li>";// do stuff with $line
	}
	$desc .= "</ul>";
	*/

	$results .= $desc;
	$results .= "=================================<br>";

	$data = "array('quantity' => 10, 'productName' => '".$t."'";
	$data.= ", 'category' => '".$c."'";
	$data.= ", 'brand' => '".$b."'";
	$data.= ", 'price' => '".$p."'";
	$data.= ", 'image' => '".$i."'";
	$data.= ", 'description' => '".$desc."'";
	$data.= ", 'available' => true),";
	$data.= "\r\n"; //add new line for file insertion

	$file = SAVE2FILE;
	file_put_contents($file, $data, FILE_APPEND | LOCK_EX);


	$linecount = 0;
	$handle = fopen($file, "r");
	while(!feof($handle)){
	  $line = fgets($handle);
	  $linecount++;
	}
	fclose($handle);


}
//END OF PHP LOGIC CODE
?>

<?php if($i){ echo "<h1> Image = ".$i."</h1><br>"; } ?>
<?php echo $results ?>

<?php echo htmlspecialchars($data) ?>
<?php echo "<br>=================================<br>" ?>

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" autocomplete="off" method="post">
Amazon Product URL:<br>
<input type="text" size=100 name="url" value=""><br>
Category:<br>
<input type="text" size=40 name="category" value="<?php echo CATEGORY ?>" readonly><br>
Save to File:<br>
<input type="text" size=40 name="file" value="<?php echo SAVE2FILE ?>" readonly><br>
<!--Price:<br>
<input type="text" name="price" value=""><br>

Description:<br>
<textarea rows="20" cols="80" name="desc">
</textarea><br>-->

<input type="submit" value="Generate">
</form>

</body>
</html>


<?php

//https://gist.github.com/raveren/5555297
function random_text( $type = 'alnum', $length = 10 )
{
	switch ( $type ) {
		case 'alnum':
			$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'alpha':
			$pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
			break;
		case 'hexdec':
			$pool = '0123456789abcdef';
			break;
		case 'numeric':
			$pool = '0123456789';
			break;
		case 'nozero':
			$pool = '123456789';
			break;
		case 'distinct':
			$pool = '2345679ACDEFHJKLMNPRSTUVWXYZ';
			break;
		default:
			$pool = (string) $type;
			break;
	}


	$crypto_rand_secure = function ( $min, $max ) {
		$range = $max - $min;
		if ( $range < 0 ) return $min; // not so random...
		$log    = log( $range, 2 );
		$bytes  = (int) ( $log / 8 ) + 1; // length in bytes
		$bits   = (int) $log + 1; // length in bits
		$filter = (int) ( 1 << $bits ) - 1; // set all lower bits to 1
		do {
			$rnd = hexdec( bin2hex( openssl_random_pseudo_bytes( $bytes ) ) );
			$rnd = $rnd & $filter; // discard irrelevant bits
		} while ( $rnd >= $range );
		return $min + $rnd;
	};

	$token = "";
	$max   = strlen( $pool );
	for ( $i = 0; $i < $length; $i++ ) {
		$token .= $pool[$crypto_rand_secure( 0, $max )];
	}
	return $token;
}
?>