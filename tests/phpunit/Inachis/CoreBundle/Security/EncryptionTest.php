<?php

namespace Inachis\Tests\CoreBundle\Security;

use Inachis\Component\CoreBundle\Security\Encryption;
use ParagonIE\Halite\Symmetric\EncryptionKey;

/**
 * @Entity
 * @group unit
 */
class EncryptionTest extends \PHPUnit_Framework_TestCase
{
    public $encryptor;
    public $key = '8b0ffdf9f435daa396016ea99df4136a69309fe68d1d61a32d063ca8425739a56ba97d3dd563dc3eacf84084c31e57e7';

    public function setUp()
    {
        $this->encryptor = new Encryption($this->key);
    }

    public function testEncryptAndDecrypt()
    {
        $this->assertEquals(
            'this is a test',
            $this->encryptor->decrypt($this->encryptor->encrypt('this is a test'))
        );
    }

    public function testDecrypt()
    {
        $this->assertEquals(
            'this is a test',
            $this->encryptor->decrypt(
                '3142010058d3d926e6f610982dcddc8d151c7de1ee4c2aad1a52090bd43e863a52f5b80fb1387be330100a40f3db18' .
                '7f8ac99eba5b200965101573f8bad11fc07a62f022a25ee2c57adeb9c9e1d7d770ed25fa1752d682b29d6e2b10cf53' .
                'd3aca337de4f3637f396e745'
            )
        );
    }
}
