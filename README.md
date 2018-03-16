## SimpleTextDB
A PHP Class that reads texts as database. Use for sample texts.

### Usage
Include the file `<?php include( 'simpleQuery.php' );?>`
#### Initialize
```php
	<?php 
	$db = new simpleQuery();
```

#### Get 
Get back data, just like money in a bank

##### All columns:
```php
	<?php
	$example = $db->select( '*' )
		->from( $text )
		->get();
	print_r( $example );
```

##### Custom Columns:
```php
	<?php 
	$example = $db->select( 'LessonTitle, Lessons'  )
		->from( $text )
		->get();
	print_r( $example );
	
```

##### Where Statement:
This WHERE works as AND Operator at the moment or OR
```php
	<?php 
  // Defaults to Where Statement
	$example = $db->select( '*'  )
		->from( $text )
		->where( [ 'LessonId' => '3' ] )
		->get();
	print_r( $example );
	
	// Defaults to OR Operator
	$example = $db->select( '*'  )
		->from( $text )
		->where( [ 'LessonId' => '3', 'Lesson' => 'Category 2' ] )
		->get();
	print_r( $example );  
	
```

##### Order By:
`simpleQuery::ASC` and `simpleQuery::DESC`
```php
	<?php 
	$db = $db->select( '*'  )
		->from( $text )
		->order_by( 'LessonId', simpleQuery::ASC )
		->get();
	print_r( $example );
```
# Live in the moment! Keep alive for happy codes as days!
