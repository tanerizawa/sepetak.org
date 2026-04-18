<?php

namespace App\Services;

use App\Services\ArticleGeneration\AcademicQualityRuleSet;
use App\Services\ArticleGeneration\ContentProfile;
use App\Services\ArticleGeneration\MemberPracticalQualityRuleSet;

/**
 * Memilih aturan validasi berdasarkan profil konten — akademik vs materi harian terpisah.
 */
class ArticleQualityValidator
{
    protected array $errors = [];

    protected array $warnings = [];

    public function __construct(
        protected ResponseParser $parser,
        protected AcademicQualityRuleSet $academicRules,
        protected MemberPracticalQualityRuleSet $memberPracticalRules,
    ) {}

    public function validate(string $content, ?string $contentProfile = null): bool
    {
        $profile = ContentProfile::fromNullableString($contentProfile);
        $rules = $profile === ContentProfile::MemberPractical
            ? $this->memberPracticalRules
            : $this->academicRules;

        $ok = $rules->validate($this->parser, $content);
        $this->errors = $rules->errors();
        $this->warnings = $rules->warnings();

        return $ok;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function warnings(): array
    {
        return $this->warnings;
    }

    public function errorsAsString(): string
    {
        return implode(' | ', $this->errors);
    }

    public function allIssuesAsString(): string
    {
        $all = array_merge(
            array_map(fn ($e) => "[ERROR] {$e}", $this->errors),
            array_map(fn ($w) => "[WARN] {$w}", $this->warnings),
        );

        return implode(' | ', $all);
    }
}
