<?php

namespace App\Tests\Editorjs\Twig;

use App\Tests\Traits\DependenciesTrait;
use App\Editorjs\Twig\EditorjsTwigExtension;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;

class EditorjsTwigExtensionTest extends KernelTestCase
{
    use DependenciesTrait;

    public function testEditorjsParse_Success()
    {
        /** @var EditorjsTwigExtension $extension */
        $extension = static::getContainer()->get(EditorjsTwigExtension::class);

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "header",
                    "data": { "text": "Заголовок", "level": 2 }
                },
                {
                    "id": "bdVWuxhI0A",
                    "type": "paragraph",
                    "data": { "text": "Содержимое" }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);
        $this->assertEquals('Заголовок', $crawler->filter('h2')->innerText());
        $this->assertEquals('Содержимое', $crawler->filter('p')->innerText());
    }

    public function testEditorjsParse_UnregisteredTag_Success()
    {
        /** @var EditorjsTwigExtension $extension */
        $extension = static::getContainer()->get(EditorjsTwigExtension::class);

        $fixtureJSON = '{
            "time": 1662366239907,
            "blocks": [
                {
                    "id": "EfdvkMNOto",
                    "type": "unregistered_tag",
                    "data": { "text": "Незарегистрированный тег" }
                },
                {
                    "id": "bdVWuxhI0A",
                    "type": "paragraph",
                    "data": { "text": "Содержимое" }
                }
            ],
            "version": "2.25.0"
        }';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $crawler = new Crawler($parsedHTML);
        $this->assertEquals('Содержимое', $crawler->filter('p')->innerText());
    }

    public function testEditorjsParse_EmptyContent_Success()
    {
        /** @var EditorjsTwigExtension $extension */
        $extension = static::getContainer()->get(EditorjsTwigExtension::class);

        $fixtureJSON = '';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $expectedHTML = '';

        $this->assertEquals(
            $expectedHTML,
            $parsedHTML
        );
    }

    public function testEditorjsParse_ArrayContent_Success()
    {
        /** @var EditorjsTwigExtension $extension */
        $extension = static::getContainer()->get(EditorjsTwigExtension::class);

        $fixtureJSON = '[]';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $expectedHTML = '';

        $this->assertEquals(
            $expectedHTML,
            $parsedHTML
        );
    }

    public function testEditorjsParse_StringContent_Success()
    {
        /** @var EditorjsTwigExtension $extension */
        $extension = static::getContainer()->get(EditorjsTwigExtension::class);

        $fixtureJSON = 'Строка';

        $parsedHTML = $extension->editorjsParse($fixtureJSON);
        $expectedHTML = '';

        $this->assertEquals(
            $expectedHTML,
            $parsedHTML
        );
    }
}
