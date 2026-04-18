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

    public function test_has_abstract_accepts_ringkasan_singkat_heading(): void
    {
        $parser = new ResponseParser;
        $md = "## Ringkasan singkat\n\nParagraf.\n";

        $this->assertTrue($parser->hasAbstract($md));
    }

    public function test_count_inline_citations_accepts_lowercase_lembaga(): void
    {
        $parser = new ResponseParser;
        $md = 'Rujukan (kemenkumham, 2024) dan (bps, 2023).';

        $this->assertSame(2, $parser->countInlineCitations($md));
    }

    public function test_has_conclusion_accepts_simpulan(): void
    {
        $parser = new ResponseParser;
        $md = "## Simpulan\n\nSelesai.\n";

        $this->assertTrue($parser->hasConclusion($md));
    }
}
