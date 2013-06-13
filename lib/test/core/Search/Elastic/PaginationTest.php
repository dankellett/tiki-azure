<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: PaginationTest.php 45866 2013-05-06 20:30:16Z lphuberdeau $

class Search_Elastic_PaginationTest extends Search_Index_LucenePaginationTest
{
	function setUp()
	{
		static $count = 0;

		$connection = new Search_Elastic_Connection('http://localhost:9200');
		$connection->startBulk(100);

		$status = $connection->getStatus();
		if (! $status->ok) {
			$this->markTestSkipped('ElasticSearch needs to be available on localhost:9200 for the test to run.');
		}

		$this->index = new Search_Elastic_Index($connection, 'test_index');
		$this->index->destroy();
	}

	function tearDown()
	{
		if ($this->index) {
			$this->index->destroy();
		}
	}
}

