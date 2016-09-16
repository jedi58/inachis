<?php

namespace Inachis\Tests\CoreBundle\Security;

use Inachis\Component\CoreBundle\Application;
use Inachis\Component\CoreBundle\Security\ContentSecurityPolicy;

/**
 * @Entity
 * @group unit
 */
class ContentSecurityPolicyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var string[] Policies to use for CSP header tests
     */
    protected $csp;

    public function setUp()
    {
        $this->csp = json_decode('{
            "enforce": {
                "default-src": {
                    "self": true
                },
                "script-src": {
                    "unsafe-eval": true,
                    "self": true,
                    "sources": [
                        "analytics.google.com"
                    ]
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
    /**
     * Test the enforce header
     */
    public function testGenerateCSPEnforceHeader()
    {
        $this->assertEquals(
            'default-src \'self\'; script-src \'unsafe-eval\' \'self\' analytics.google.com; upgrade-insecure-requests',
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader($this->csp->enforce)
        );
    }
    /**
     * Test the enforce header
     */
    public function testGenerateCSPReportHeader()
    {
        $this->assertEquals(
            'style-src \'self\' data:',
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader($this->csp->report)
        );
    }
}
