<?php

/**
 * Class IndexTest
 * @Small
 * @runTestsInSeparateProcesses
 */
class IndexTest extends PHPUnit_Framework_TestCase
{
    private $output;

    public function setUp()
    {
        $_SERVER['HTTP_HOST'] = 'anything.rly';
        $_SERVER['HTTP_USER_AGENT'] = 'PHPUnit FakeAgent';
        $_SERVER['REMOTE_HOST'] = '';
        $_SERVER['HTTP_ACCEPT'] = 'text/html';
        $_SERVER['REQUEST_URI'] = '/';
    }

    /**
     * @test
     * @dataProvider provideUrls
     */
    public function testDomainNames($url, $expectedString)
    {
        list($host, $uri) = explode('/', $url, 2);
        $this->setHost($host)
            ->setUri('/'.$uri);

        $this->runIndex()
            ->assertPageHas($expectedString);
    }

    /**
     * @test
     */
    public function whenNoCookiesAreProvidedItIsTold()
    {
        $this->runIndex()
            ->assertPageHas(
                'As it turns out, your browser didn\'t send any cookies'
            );
    }

    /**
     * @test
     */
    public function whenCookiesAreProvidedTheyArePrinted()
    {
        $_COOKIE = array(
            'TestCookieName' => 'TestCookieValue'
        );

        $this->runIndex()
            ->assertPageHas(
                'In fact here are your cookie names and values for'
            )
            ->assertPageHas(
                '<li>TestCookieName: <span>TestCookieValue</span>'
            )
        ;
    }

    public function provideUrls()
    {
        return array(
            array(
                'www.google.com/',
                '<title>This is not google.com!</title>'
            ),
            array(
                'www.github.com/',
                'Where\'s github.com?'
            ),
        );
    }

    private function setHost($host)
    {
        $_SERVER['HTTP_HOST'] = $host;
        return $this;
    }

    private function setUri($uri)
    {
        $_SERVER['REQUEST_URI'] = $uri;
        return $this;
    }

    private function assertPageHas($expectedString)
    {
        $this->assertTrue(
            stripos($this->output, $expectedString) !== false,
            "Page did not contain '$expectedString'"
        );
        return $this;
    }

    private function runIndex()
    {
        ob_start();
        include __DIR__ . '/../index.php';
        $this->output = ob_get_clean();
        return $this;
    }
}