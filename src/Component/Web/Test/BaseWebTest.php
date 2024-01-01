<?php

namespace Untek\Component\Web\Test;

use PHPUnit\Framework\TestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpKernel\HttpKernelBrowser;
use Untek\Sandbox\Sandbox\WebTest\Domain\Facades\TestHttpFacade;
use Untek\Tool\Test\Traits\BaseUrlTrait;
use Untek\Tool\Test\Traits\FixtureTrait;

abstract class BaseWebTest extends TestCase
{

    use FixtureTrait;
    use BaseUrlTrait;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        //$this->authProvider = new RpcAuthProvider($this->rpcProvider);
    }

    protected function setUp(): void
    {
        $this->setBaseUrl(getenv('WEB_URL'));
        $this->initFixtureProvider(getenv('RPC_URL'));
        parent::setUp();
    }

    protected function sendRequest(AbstractBrowser $browser, string $uri, string $method = 'GET'): Crawler
    {
        $url = $this->getBaseUrl() . '/' . $uri;
//        dump($method);
        return $browser->request($method, $url, [], [], [
            //'HTTP_ENV_NAME' => 'test',
        ]);
    }

    protected function createAssert(AbstractBrowser $browser): WebAssert
    {
        return new WebAssert(null, [], '', $browser);
    }

    protected function sendForm(AbstractBrowser $browser, string $uri, string $buttonValue, array $formValues): Crawler
    {
        $crawler = $this->sendRequest($browser, $uri);
        $form = $crawler->selectButton($buttonValue)->form();
        foreach ($formValues as $name => $value) {
            $form[$name] = $value;
        }
        $crawler = $browser->submit($form);
        return $crawler;
    }

    protected function assertUnauthorized(string $uri, string $method = 'GET')
    {
        $browser = $this->getBrowser();
        $this->sendRequest($browser, $uri, $method);
        $this->createAssert($browser)
            ->assertUnauthorized();
    }

    protected function getBrowser(): AbstractBrowser
    {
        $httpKernel = TestHttpFacade::createHttpKernel();
        $browser = new HttpKernelBrowser($httpKernel);
        $browser->followRedirects();

//        $browser = TestHttpFacade::createHttpKernelBrowser();
//        $browser = new HttpBrowser(HttpClient::create());
        $browser->setServerParameter('HTTP_ENV_NAME', 'test');
        return $browser;
    }

    protected function getBrowserByLogin(string $login = null, string $password = "Wwwqqq111"): AbstractBrowser
    {
        $browser = $this->getBrowser();
        if ($login == null) {
            return $browser;
        }
        $this->authByLogin($browser, $login, $password);
        return $browser;
    }

    private function authByLogin(AbstractBrowser $browser, string $login = null, string $password = "Wwwqqq111")
    {
        $this->sendForm($browser, 'auth', 'Вход', [
            'form[login]' => $login,
            'form[password]' => $password,
        ]);
    }
}
