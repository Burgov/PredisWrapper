<?php

namespace Burgov\PredisWrapper\Type;

class AbstractListTypeTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->client = $this->getMockBuilder('Burgov\PredisWrapper\Client')->disableOriginalConstructor()->getMock();
        $this->type = $this->getMockBuilder('Burgov\PredisWrapper\Type\AbstractListType')
            ->setConstructorArgs(array($this->client, 'test_key'))
            ->setMethods(null)
            ->getMock();
    }

    /**
     * @dataProvider sortProvider
     */
    public function testSort(array $expectedArguments, SortCriteria $sort = null)
    {
        $this->client->expects($this->once())->method('__call')->with('sort', array('test_key', $expectedArguments))->will($this->returnValue(array()));
        $this->type->sort($sort);
    }

    /**
     * @dataProvider sortProvider
     */
    public function testCreateFromSort(array $expectedArguments, SortCriteria $sort = null)
    {
        $expectedArguments['STORE'] = 'new_key';
        $this->client->expects($this->once())->method('__call')->with('sort', array('test_key', $expectedArguments))->will($this->returnValue(array()));
        AbstractListType::createFromSort(new Set($this->client, 'new_key'), new Set($this->client, 'test_key'), $sort);
    }

    public function sortProvider()
    {
        $ret = array();

        $ret['simple'] = array(array(), null);

        $sort = new SortCriteria(null, null, null, SortCriteria::DESC);
        $ret['reverse'] = array(array('SORT' => 'DESC'), $sort);

        $sort = new SortCriteria(null, null, null, SortCriteria::ASC, true);
        $ret['alphanumeric'] = array(array('ALPHA' => 'ALPHA'), $sort);

        $sort = new SortCriteria(null, null, null, SortCriteria::DESC, true);
        $ret['reverse alphanumeric'] = array(array('SORT' => 'DESC', 'ALPHA' => 'ALPHA'), $sort);

        $sort = new SortCriteria(null, array(3, 5));
        $ret['limit'] = array(array('LIMIT' => array(3, 5)), $sort);

        $sort = new SortCriteria('weight_*');
        $ret['sort by weight'] = array(array('BY' => 'weight_*'), $sort);

        $sort = new SortCriteria(false);
        $ret['sort by nothing'] = array(array('BY' => 'lets_assume_this_key_does_not_exist'), $sort);

        $sort = new SortCriteria(null, null, array('object_*', SortCriteria::GET_SELF));
        $ret['sort and get self and object'] = array(array('GET' => array('object_*', '#')), $sort);

        $sort = new SortCriteria('weight_*', array(6, 3), array(SortCriteria::GET_SELF, 'object_*->name'), SortCriteria::DESC, true);
        $ret['end boss'] = array(array('BY' => 'weight_*', 'LIMIT' => array(6, 3), 'GET' => array('#', 'object_*->name'), 'SORT' => 'DESC', 'ALPHA' => 'ALPHA'), $sort);

        return $ret;
    }
} 