<?php
class articleModel{
		public $table ='article';

		function count(){
			$sql = 'select count(*) from '.$this->table;
			$res = DB::query($sql);
			
			return DB::findResult($res,0,0);
		}
	}