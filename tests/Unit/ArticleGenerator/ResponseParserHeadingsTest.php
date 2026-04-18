<?php

namespace Tests\Unit\ArticleGenerator;

use App\Services\ResponseParser;
use PHPUnit\Framework\TestCase;

class ResponseParserHeadingsTest extends TestCase
{
    public function test_has_abstract_accepts_ringkasan_praktis_heading(): void
    {
        $parser = new ResponseParser;
        $md = "## Ringkasan praktis\n\nIsi.\n\n## Penutup\n\nTutup.\n";

        $this->assertTrue($parser->hasAbstract($md));
    }
}
