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
     * Test the report header
     */
    public function testGenerateCSPReportHeader()
    {
        $this->assertEquals(
            'style-src \'self\' data:',
            ContentSecurityPolicy::getInstance()->getCSPReportHeader($this->csp->report)
        );
    }
    /**
     * Test the enforce header default is not an empty string
     */
    public function testGenerateCSPEnforceHeaderDefault()
    {
        $this->assertNotEmpty(
            ContentSecurityPolicy::getInstance()->getCSPEnforceHeader()
        );
    }
    /**
     * Test the report header default is not an empty string
     */
    public function testGenerateCSPReportHeaderDefault()
    {
        $this->assertNotEmpty(
            ContentSecurityPolicy::getInstance()->getCSPReportHeader()
        );
    }

    public function testGenerateCSPPolicyFail()
    {
        try {
            $csp = json_decode('{
                "foo-src": {
                    "bar": true
                }
            }');
            ContentSecurityPolicy::getInstance()->generateCSP($csp);
        } catch (\Exception $exception) {
            $this->assertContains('policy is not supported', $exception->getMessage());
        }
    }

    public function testGenerateCSPDirectiveFail()
    {
        try {
            $csp = json_decode('{
                "default-src": {
                    "bar": true
                }
            }');
            ContentSecurityPolicy::getInstance()->generateCSP($csp);
        } catch (\Exception $exception) {
            $this->assertContains('Could not understand', $exception->getMessage());
        }
    }
}
