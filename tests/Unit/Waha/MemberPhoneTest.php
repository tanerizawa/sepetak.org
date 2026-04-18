<?php

namespace Tests\Unit\Waha;

use App\Services\Waha\MemberPhone;
use PHPUnit\Framework\TestCase;

class MemberPhoneTest extends TestCase
{
    public function test_indonesian_leading_zero_to_62(): void
    {
        $this->assertSame('6281234567890@c.us', MemberPhone::toChatId('0812-3456-7890'));
    }

    public function test_eight_prefix_without_zero(): void
    {
        $this->assertSame('6281234567890@c.us', MemberPhone::toChatId('81234567890'));
    }

    public function test_already_62(): void
    {
        $this->assertSame('6281234567890@c.us', MemberPhone::toChatId('+62 812 3456 7890'));
    }

    public function test_empty_returns_null(): void
    {
        $this->assertNull(MemberPhone::toChatId(null));
        $this->assertNull(MemberPhone::toChatId(''));
    }
}
