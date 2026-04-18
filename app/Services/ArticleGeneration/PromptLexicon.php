<?php

namespace App\Services\ArticleGeneration;

/**
 * Kata pengantar topik (tipe artikel & kerangka pikir) — dipakai kedua strategi prompt.
 */
final class PromptLexicon
{
    public static function humanizeArticleType(string $type): string
    {
        return match ($type) {
            'essay' => 'essay akademik',
            'opinion' => 'artikel opini mendalam',
            'scientific_review' => 'kajian ilmiah / tinjauan literatur',
            'policy_analysis' => 'analisis kebijakan publik',
            'thinker_profile' => 'profil pemikiran tokoh/aliran',
            'historical_review' => 'tinjauan historis-analitis',
            'member_guide' => 'materi praktis / panduan ringkas untuk anggota',
            default => 'essay akademik',
        };
    }

    public static function humanizeThinkingFramework(string $framework): string
    {
        return match ($framework) {
            'marxist' => 'Marxisme klasik (Marx, Engels, Kautsky)',
            'neo_marxian' => 'Neo-Marxian (Gramsci, Harvey, Wallerstein, Poulantzas)',
            'postmodern' => 'Post-modern / Post-strukturalis (Foucault, Bourdieu, Spivak)',
            'critical_theory' => 'Teori Kritis / Mazhab Frankfurt (Adorno, Horkheimer, Marcuse, Habermas)',
            'agrarian_political_economy' => 'Ekonomi Politik Agraria (Bernstein, Van der Ploeg, Wolf, Scott)',
            'ecopolitics' => 'Ekologi Politik (Martinez-Alier, Shiva, Escobar)',
            'human_rights' => 'Hak Asasi Manusia dan Pembangunan (Sen, Chatterjee, Nussbaum)',
            default => 'Marxisme dan pemikiran kritis',
        };
    }
}
