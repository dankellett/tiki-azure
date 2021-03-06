<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: IndexTest.php 45842 2013-05-02 15:28:39Z lphuberdeau $

class Search_Elastic_IndexTest extends Search_Index_LuceneTest
{
	function setUp()
	{
		static $count = 0;

		$connection = new Search_Elastic_Connection('http://localhost:9200');

		$status = $connection->getStatus();
		if (! $status->ok) {
			$this->markTestSkipped('ElasticSearch needs to be available on localhost:9200 for the test to run.');
		}

		$this->index = new Search_Elastic_Index($connection, 'test_index');
		$this->index->destroy();

		$this->populate($this->index);
	}

	function tearDown()
	{
		if ($this->index) {
			$this->index->destroy();
		}
	}

	function testIndexProvidesHighlightHelper()
	{
		$query = new Search_Query('foobar or hello');
		$resultSet = $query->search($this->index);

		$plugin = new Search_Formatter_Plugin_WikiTemplate('{display name=highlight}');
		$formatter = new Search_Formatter($plugin);
		$output = $formatter->format($resultSet);

		$this->assertContains('<em>Hello</em>', $output);
		$this->assertNotContains('<body>', $output);
	}
}

