<?php

namespace Untek\Component\Web\Test;

use PHPUnit\Framework\ExpectationFailedException;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Untek\Tool\Test\Asserts\BaseAssert;

class WebAssert extends BaseAssert
{

    protected $crawler;
    protected $browser;

    public function __construct($name = null, array $data = [], $dataName = '', AbstractBrowser $browser = null)
    {
        parent::__construct($name, $data, $dataName);
        $this->crawler = $browser->getCrawler();
        $this->browser = $browser;
    }

    public static function assertContainsText($needle, $haystack, string $message = ''): void
    {
        if(mb_strpos($haystack, $needle) === false) {
            throw new ExpectationFailedException(
                "Not contains '$needle'!"
            );
        }
    }

    public function assertContainsContent(string $content)
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertContainsText($content, $html);
        return $this;
    }

    public function assertFormValues(string $buttonValue, array $values)
    {
        $form = $this->crawler->selectButton($buttonValue)->form();
        $this->assertArraySubset($values, $form->getValues());
        return $this;
    }

    public function assertUnauthorized()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertContainsText('Логин', $html);
        return $this;
    }

    public function assertIsFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertContainsText('Has errors!', $html);
        return $this;
    }

    public function assertIsNotFormError()
    {
        $html = html_entity_decode($this->crawler->html());
        $this->assertNotContains('Has errors!', $html);
        return $this;
    }

    /*public function assertFormError($message)
    {
        //$this->assertIsFormError();
        $this->assertContainsText($message, $this->crawler->html());
        return $this;
    }*/
}
