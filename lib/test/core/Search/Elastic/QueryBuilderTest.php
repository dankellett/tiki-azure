<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: QueryBuilderTest.php 46291 2013-06-12 15:10:36Z lphuberdeau $

use Search_Elastic_QueryBuilder as QueryBuilder;
use Search_Expr_Token as Token;
use Search_Expr_And as AndX;
use Search_Expr_Or as OrX;
use Search_Expr_Not as NotX;
use Search_Expr_Range as Range;
use Search_Expr_Initial as Initial;

class Search_Elastic_QueryBuilderTest extends PHPUnit_Framework_TestCase
{
	function testSimpleQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Token('Hello', 'plaintext', 'contents', 1.5));

		$this->assertEquals(
			array(
				"match" => array(
					"contents" => array("query" => "hello", "boost" => 1.5),
				),
			), $query['query']
		);
	}

	function testQueryWithSinglePart()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new AndX(
				array(
					new Token('Hello', 'plaintext', 'contents', 1.5),
				)
			)
		);

		$this->assertEquals(
			array(
				"match" => array(
						"contents" => array("query" => "hello", "boost" => 1.5),
				),
			), $query['query']
		);
	}

	function testBuildOrQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new OrX(
				array(
					new Token('Hello', 'plaintext', 'contents', 1.5),
					new Token('World', 'plaintext', 'contents', 1.0),
				)
			)
		);

		$this->assertEquals(
			array(
				"bool" => array(
					"should" => array(
						array(
							"match" => array(
								"contents" => array("query" => "hello", "boost" => 1.5),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "world", "boost" => 1.0),
							),
						),
					),
					"minimum_number_should_match" => 1,
				),
			), $query['query']
		);
	}

	function testAndQuery()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new AndX(
				array(
					new Token('Hello', 'plaintext', 'contents', 1.5),
					new Token('World', 'plaintext', 'contents', 1.0),
				)
			)
		);

		$this->assertEquals(
			array(
				"bool" => array(
					"must" => array(
						array(
							"match" => array(
								"contents" => array("query" => "hello", "boost" => 1.5),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "world", "boost" => 1.0),
							),
						),
					),
				),
			), $query['query']
		);
	}

	function testNotBuild()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new NotX(
				new Token('Hello', 'plaintext', 'contents', 1.5)
			)
		);

		$this->assertEquals(
			array(
				"bool" => array(
					"must_not" => array(
						array(
							"match" => array(
								"contents" => array("query" => "hello", "boost" => 1.5),
							),
						),
					),
				),
			), $query['query']
		);
	}

	function testFilterWithIdentifier()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Token('Some entry', 'identifier', 'username', 1.5));

		$this->assertEquals(
			array(
				"match" => array(
					"username" => array(
						"query" => "Some entry",
					),
				),
			), $query['query']
		);
	}

	function testRangeFilter()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Range('Hello', 'World', 'plaintext', 'title', 1.5));

		$this->assertEquals(
			array(
				"range" => array(
					"title" => array(
						"from" => "hello",
						"to" => "world",
						"boost" => 1.5,
						"include_upper" => false,
					),
				),
			), $query['query']
		);
	}

	function testInitialMatchFilter()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(new Initial('Hello', 'plaintext', 'title', 1.5));

		$this->assertEquals(
			array(
				"prefix" => array(
					"title" => array(
						"value" => "hello",
						"boost" => 1.5,
					),
				),
			), $query['query']
		);
	}

	function testFlattenOr()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new OrX(
				array(
					new OrX(
						array(
							new Token('Hello', 'plaintext', 'contents', 1.5),
							new Token('World', 'plaintext', 'contents', 1.0),
						)
					),
					new Token('Test', 'plaintext', 'contents', 1.0),
				)
			)
		);

		$this->assertEquals(
			array(
				"bool" => array(
					"should" => array(
						array(
							"match" => array(
								"contents" => array("query" => "hello", "boost" => 1.5),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "world", "boost" => 1.0),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "test", "boost" => 1.0),
							),
						),
					),
					"minimum_number_should_match" => 1,
				),
			), $query['query']
		);
	}

	function testFlattenAnd()
	{
		$builder = new QueryBuilder;

		$query = $builder->build(
			new AndX(
				array(
					new OrX(
						array(
							new Token('Hello', 'plaintext', 'contents', 1.5),
							new Token('World', 'plaintext', 'contents', 1.0),
						)
					),
					new AndX(
						array(
							new Token('Hello', 'plaintext', 'contents', 1.5),
							new Token('World', 'plaintext', 'contents', 1.0),
						)
					),
					new Token('Test', 'plaintext', 'contents', 1.0),
				)
			)
		);

		$this->assertEquals(
			array(
				"bool" => array(
					"must" => array(
						array(
							"bool" => array(
								"should" => array(
									array(
										"match" => array(
											"contents" => array("query" => "hello", "boost" => 1.5),
										),
									),
									array(
										"match" => array(
											"contents" => array("query" => "world", "boost" => 1.0),
										),
									),
								),
								"minimum_number_should_match" => 1,
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "hello", "boost" => 1.5),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "world", "boost" => 1.0),
							),
						),
						array(
							"match" => array(
								"contents" => array("query" => "test", "boost" => 1.0),
							),
						),
					),
				),
			), $query['query']
		);
	}
}

