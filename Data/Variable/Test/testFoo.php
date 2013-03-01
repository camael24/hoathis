<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 01/03/13
 * Time: 17:09
 * To change this template use File | Settings | File Templates.
 */
namespace {
    include 'Foo.php';
}
namespace test\unit {
    class Foo extends \atoum {
        /**
         * @dataProvider sumDataProvider
         */
        public function testCheck($data, $type, $bool) {
            $h = new \Foo();
            $this->boolean($h->check($data, $type))
                ->isEqualTo($bool);

        }

        public function sumDataProvider() {
            return array(
                array('Camael24', 'username', true),
                array('foobar', 'username', true),
                array('a', 'username', false),
                array('ab', 'username', false),
                array('abc', 'username', false),
                array('aabcd', 'username', true),
                array(str_repeat('a', 5), 'password', false),
                array(str_repeat('a', 6), 'password', false),
                array(str_repeat('a', 7), 'password', false),
                array(str_repeat('a', 8), 'password', true),
                array('foo', 'email', false),
                array('foo@foo', 'email', false),
                array('foo@foo.fr', 'email', true),
                array('food+sdqsd@foo.fr', 'email', true),
                array('food+sdqsd@foqsdqsdqsdqdqsdo.fr', 'email', true),
                array('food+sdqsd@f.f', 'email', true),
                array('food+sdqsd@f.fr', 'email', true),
                array('food+sdqsd@f.frr', 'email', true),
                array('a', 'http', false),
                array('a.fr', 'http', false),
                array('a.com', 'http', false),
                array('a.co.uk', 'http', false),
                array('http://a.co.um', 'http', true),
                array('http://a.co.um/', 'http', true),
                array('http://a.co.um/foo', 'http', true),
                array('http://a.co.um/foo.html', 'http', true),
                array('http://a.co.um/foo.pkk', 'http', true),
                array('http://a.co.um/foo/', 'http', true),
                array('http://a.co.um/foo/bar.html', 'http', true),
                array('http://a.co.um/foo/bar.pkk', 'http', true),
                array('http://a.co.um/foo/bar.html?foo', 'http', true),
                array('http://a.co.um/foo/bar.html?foo=h', 'http', true),
                array('http://a.co.um/foo/bar.html?foo=h&bar', 'http', true),
                array('http://a.co.um/foo/bar.html?foo=h&bar=fooo', 'http', true),
                array('http://ww.a.co.um/foo/bar.html?foo=h&bar=fooo', 'http', true),
                array('http://ww.a.co.um/foo/bar.html?foo=h&bar=fooo#4444', 'http', true)
            );
        }
    }
}
