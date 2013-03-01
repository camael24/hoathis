<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Julien
 * Date: 01/03/13
 * Time: 17:09
 * To change this template use File | Settings | File Templates.
 */
namespace {
    class Foo {
        public function check($value, $type) {
            switch ($type) {
                case 'username':
                    return (strlen($value) > 3);
                    break;
                case 'password':
                    return (strlen($value) > 7);
                    break;
                case 'email':
                    return ((filter_var($value, FILTER_VALIDATE_EMAIL) !== false)
                        ? true
                        : false);
                    break;
                case 'http':
                    return ((filter_var($value, FILTER_VALIDATE_URL) === false)
                        ? false
                        : true);
                    break;
                default:
                    return false;
            }
        }
    }
}
