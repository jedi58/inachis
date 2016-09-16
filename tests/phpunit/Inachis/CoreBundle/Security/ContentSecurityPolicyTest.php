<?php

namespace Inachis\Tests\CoreBundle\Security;

use Inachis\Component\CoreBundle\Security\ContentSecurityPolicy;
use Inachis\Component\CoreBundle\Application;

/**
 * @Entity
 * @group unit
 */
class ContentSecurityPolicyTest extends \PHPUnit_Framework_TestCase
{
    protected $policies;

    public function setUp()
    {
        $this->csp = json_decode('{
            "enforce": {
                "default-src": {
                    "self": true
                },
                "script-src": {
                    "unsafe-eval": true,
                    "self": true
                },
                "upgrade-insecure-requests": true
            },
            "report": {
                "style-src": {
                    "self": true,
                    "data": true
                }
            }
        }');
    }

    public function testGenerateCSPEnforceHeader()
    {
        $this->assertEquals(
            'default-src \'self\'; script-src \'unsafe-eval\' \'self\'; upgrade-insecure-requests',
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader($this->csp->enforce)
        );
    }

    public function testGenerateCSPReportHeader()
    {
        $this->assertEquals(
            'style-src \'self\' data:',
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader($this->csp->report)
        );
    }
}
