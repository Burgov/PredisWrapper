<?php

namespace Burgov\PredisWrapper\Type;


class SortCriteriaTest extends \PHPUnit_Framework_TestCase
{
    public function testByFalse()
    {
        $sort = new SortCriteria(false);
        $parts = $sort->getParts();
        $this->assertEquals('lets_assume_this_key_does_not_exist', $parts['BY']);
    }

    public function testStringLimit()
    {
        $sort = new SortCriteria(null, "1 2");
        $parts = $sort->getParts();
        $this->assertEquals(array(1, 2), $parts['LIMIT']);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidStringLimit()
    {
        new SortCriteria(null, "1a 2");
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIntLimit()
    {
        new SortCriteria(null, 4);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidArrayLimit()
    {
        new SortCriteria(null, array(1, 2, 3));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testIntegerGet()
    {
        new SortCriteria(null, null, 3);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidDirection()
    {
        new SortCriteria(null, null, null, 'hi');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testInvalidAlpha()
    {
        new SortCriteria(null, null, null, SortCriteria::ASC, 'true');
    }
}
