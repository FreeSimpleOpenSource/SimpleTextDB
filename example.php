<?php
incule 'simpleQuery.php';
$db = new simpleQuery();

//Example Text
$text = 'Category 1 : Category Title<br>
Content 1 (1)<br>
Content 2 (1)<br>
Content 3 (1)<br>
Content 4 (1)<br>
Content 5 (1)<br>
Category 2 : Category Title<br>
Content 1 (2)<br>
Content 2 (2)<br>
Content 3 (2)<br>
Category 3 : Category Title<br>
Content 1 (3)<br>
Content 2 (3)<br>
Content 3 (3)<br>';

echo "<pre>";

$example = $db->select('*')
		->from($text)
		->where( [ 'LessonId' => '3' ]
		->order_by( 'LessonId', simpleQuery::DESC )
		->get();
	print_r( $example );

echo "</pre>";
