<?php
// (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: Interface.php 46273 2013-06-10 19:26:34Z lphuberdeau $

interface Search_Index_Interface
{
	function addDocument(array $document);

	function invalidateMultiple(array $query);

	function endUpdate();

	function find(Search_Query_Interface $query, $resultStart, $resultCount);

	function getTypeFactory();

	function optimize();
}

