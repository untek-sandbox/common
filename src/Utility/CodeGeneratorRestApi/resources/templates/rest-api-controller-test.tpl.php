<?php

/**
 * @var string $namespace
 * @var string $className
 * @var string $endpoint
 * @var string $method
 */

?>

namespace <?= $namespace ?>;

use Tests\RestApi\Abstract\AbstractRestApiTestCase;
use Untek\Framework\RestApiTest\Asserts\RestApiResponseAssert;

class <?= $className ?> extends AbstractRestApiTestCase
{

    public function testExample()
    {
        $data = [

        ];
        $response = $this->sendRequest('<?= $endpoint ?>', '<?= $method ?>', $data);

        (new RestApiResponseAssert($response))
            ->assertStatus(200)
            ->assertData([
            ]);
    }
}