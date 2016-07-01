<?php


class SampleTest extends PHPUnit_Framework_TestCase
{
    public function testAdd()
    {
        $post = array();

        $username = Arr::get($post, 'username');

        $this->assertEquals($username, null);
    }

    public function testDelete()
    {
        $name = 'ce';
        $this->assertEquals($name, 'ce');
    }

    public function testPushAndPop()
    {
        $stack = [];
        $this->assertEquals(0, count($stack));

        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertEquals(1, count($stack));

        $this->assertEquals('foo', array_pop($stack));
        $this->assertEquals(0, count($stack));
    }

    public function testEmpty()
    {
        $stack = [];
        $this->assertEmpty($stack);

        return $stack;
    }

    /**
     * @depends testEmpty
     */
    public function testPush(array $stack)
    {
        array_push($stack, 'foo');
        $this->assertEquals('foo', $stack[count($stack)-1]);
        $this->assertNotEmpty($stack);

        return $stack;
    }

    /**
     * @depends testPush
     */
    public function testPop(array $stack)
    {
        $this->assertEquals('foo', array_pop($stack));
        $this->assertEmpty($stack);
    }

    public function testFailure()
    {
        $this->assertContains(4, [1, 2, 3, 4]);
    }

    public function testFailures()
    {
        $this->assertContainsOnly('string', ['1', '2', '3']);
        $this->assertEquals(['a', 'c', 'd'], ['a', 'c', 'd']);
    }

}