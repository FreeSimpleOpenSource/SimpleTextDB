<?php
class simpleQuery{
	public $file, $content = [];
	private $where, $select, $merge, $replaceSpace, $AvailableTextFormat;
	private $order_by = [];
	const ASC = 1;
	const DESC = 0;
	
	public	function replaceSpace($string)
		{
		$string = trim($string);
		return $string;
		}
		
	public  function AvailableTextFormat($text){
		$FormatText = explode("<br>", $text);
		$FormatArray = array();
		$Lessons = array();
		$Lesson = array();
		foreach($FormatText as $Value){
		$FormatArray[] = explode(":", $Value);
		}
		foreach($FormatArray as $Values){
		if(count($Values)>1){
		$Lessons[] = array(
		"LessonId" => simpleQuery::replaceSpace(count($Lessons)+1),
		"Lesson" => simpleQuery::replaceSpace($Values[0]),
		"LessonTitle" => simpleQuery::replaceSpace($Values[1]),
		"Lessons" => array()
		);
		}else{
			if(!empty(simpleQuery::replaceSpace($Values[0]))){
				$Lessons[count($Lessons)-1]['Lessons'][] = simpleQuery::replaceSpace($Values[0]); 
			}
		}

		}
		 return json_encode($Lessons);
		}
	public function select($args = '*') {
		
		$this->select = explode(',', $args);
		
		$this->select = array_map('trim', $this->select);
		
		$this->select = array_filter($this->select);
		return $this;
	}
	public function from($file) {
		/**
		 * Loads the jSON file
		 *
		 * @param type $file. Accepts file path to jSON file
		 * @return type object
		*/
		$this->file = simpleQuery::AvailableTextFormat($file);
		// Reset where
		$this->where( [] );
		// Reset order by
		$this->order_by = [];
		
		$this->content = ( array ) json_decode($this->file);
		return $this;
	}
	public function where( array $columns, $merge = 'OR' ) {
		$this->where = $columns;
		$this->merge = $merge;
		return $this;
	}
		private function where_result() {
		/*
			Validates the where statement values
		*/
		if( $this->merge == 'AND' ) {
			return $this->where_and_result();
		}
		else {
			$r = [];
			// Loop through the existing values. Ge the index and row
			foreach( $this->content as $index => $row ) {
				// Make sure its array data type
				$row = ( array ) $row;
				// Loop again through each row,  get columns and values
				foreach( $row as $column => $value ) {
					// If each of the column is provided in the where statement
					if( in_array( $column, array_keys( $this->where ) ) ) {
						// To be sure the where column value and existing row column value matches
						if( $this->where[ $column ] == $row[ $column ] ) {
							// Append all to be modified row into a array variable
							$r[] = $row;
							// Append also each row array key
							$this->last_indexes[] = $index;
						}
						else 
							continue;
					}
				}
			}
			return $r;
		}
	}
	
	private function where_and_result() {
		/*
			Validates the where statement values
		*/
		$r = [];
		// Loop through the db rows. Ge the index and row
		foreach( $this->content as $index => $row ) {
			// Make sure its array data type
			$row = ( array ) $row;
			
			//check if the row = where['col'=>'val', 'col2'=>'val2']
			if(!array_diff($this->where,$row)) {
				$r[] = $row;
				// Append also each row array key
				$this->last_indexes[] = $index;			
				
			}
			else continue ;
			
		}
		return $r;
	}
	
	public function order_by( $column, $order = self::ASC ) {
		$this->order_by = [ $column, $order ];
		return $this;
	}
	
	
	private function _process_order_by( $content ) {
		if( $this->order_by && $content && in_array( $this->order_by[ 0 ], array_keys( ( array ) $content[ 0 ] ) ) ) {
			/*
				* Check if order by was specified
				* Check if there's actually a result of the query
				* Makes sure the column  actually exists in the list of columns
			*/
			list( $sort_column, $order_by ) = $this->order_by;
			$sort_keys = [];
			$sorted = [];
			foreach( $content as $index => $value ) {
				$value = ( array ) $value;
				// Save the index and value so we can use them to sort
				$sort_keys[ $index ] = $value[ $sort_column ];
			}
			
			// Let's sort!
			if( $order_by == self::ASC ) {
				asort( $sort_keys );
			}
			elseif( $order_by == self::DESC ) {
				arsort( $sort_keys );
			}
			// We are done with sorting, lets use the sorted array indexes to pull back the original content and return new content
			foreach( $sort_keys as $index => $value ) {
				$sorted[ $index ] = ( array ) $content[ $index ];
			}
			$content = $sorted;
		}
		return $content;
	}
	
	public function get() {
		if($this->where != null) {
			$content = $this->where_result();
		}
		else 
			$content = $this->content; 
		
		if( $this->select && !in_array( '*', $this->select ) ) {
			$r = [];
			foreach( $content as $id => $row ) {
				$row = ( array ) $row;
				foreach( $row as $key => $val ) {
					if( in_array( $key, $this->select ) ) {
						$r[ $id ][ $key ] = $val;
					} 
					else 
						continue;
				}
			}
			$content = $r;
		}
		// Finally, lets do sorting :)
		$content = $this->_process_order_by( $content );
		
		return $content;
	}

}
