<?php

namespace Database\Seeders;

use App\Models\ArticlePool;
use App\Models\ArticleTopic;
use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleTopicSeeder extends Seeder
{
    public function run(): void
    {
        $this->seedCategories();
        $this->seedTags();
        $this->seedTopics();
        $this->seedPools();
    }

    protected function seedCategories(): void
    {
        $categories = [
            ['name' => 'Kajian Marxian', 'slug' => 'kajian-marxian'],
            ['name' => 'Ekonomi Politik Agraria', 'slug' => 'ekonomi-politik-agraria'],
            ['name' => 'Ekologi & Lingkungan', 'slug' => 'ekologi-lingkungan'],
            ['name' => 'Hak Asasi Manusia', 'slug' => 'hak-asasi-manusia'],
            ['name' => 'Pemikiran Kritis', 'slug' => 'pemikiran-kritis'],
            ['name' => 'Analisis Kebijakan', 'slug' => 'analisis-kebijakan'],
            ['name' => 'Sejarah Gerakan', 'slug' => 'sejarah-gerakan'],
            ['name' => 'Opini & Refleksi', 'slug' => 'opini-refleksi'],
        ];

        foreach ($categories as $cat) {
            Category::firstOrCreate(['slug' => $cat['slug']], $cat);
        }
    }

    protected function seedTags(): void
    {
        $tags = [
            'Karl Marx', 'Friedrich Engels', 'Antonio Gramsci', 'David Harvey',
            'Reforma Agraria', 'Konflik Agraria', 'Materialisme Historis',
            'Kapitalisme', 'Neoliberalisme', 'Hegemoni', 'Petani', 'Buruh Tani',
            'Ekologi Politik', 'Hak Atas Tanah', 'Post-Development', 'Subaltern',
            'Akumulasi Primitif', 'Kedaulatan Pangan',
        ];

        foreach ($tags as $tag) {
            Tag::firstOrCreate(['slug' => Str::slug($tag)], [
                'name' => $tag,
                'slug' => Str::slug($tag),
            ]);
        }
    }

    protected function seedTopics(): void
    {
        $topics = [
            // === MARXIST ===
            [
                'title' => 'Teori Nilai-Lebih dan Eksploitasi Buruh Tani di Jawa Barat',
                'description' => 'Analisis relevansi teori surplus value Marx terhadap kondisi buruh tani di Karawang dan Jawa Barat kontemporer, termasuk mekanisme ekstraksi nilai-lebih melalui upah rendah, rent-seeking, dan ketergantungan input korporat.',
                'thinking_framework' => 'marxist',
                'article_type' => 'essay',
                'category_slug' => 'kajian-marxian',
                'tag_slugs' => ['karl-marx', 'buruh-tani', 'kapitalisme'],
                'key_references' => [
                    ['author' => 'Marx, K.', 'year' => 1867, 'title' => 'Das Kapital, Volume I'],
                    ['author' => 'Bernstein, H.', 'year' => 2010, 'title' => 'Class Dynamics of Agrarian Change'],
                    ['author' => 'Konsorsium Pembaruan Agraria', 'year' => 2023, 'title' => 'Catatan Akhir Tahun 2023'],
                    ['author' => 'Wiradi, G.', 'year' => 2000, 'title' => 'Reforma Agraria: Perjalanan yang Belum Berakhir'],
                ],
                'weight' => 70,
            ],
            [
                'title' => 'Alienasi Petani dalam Modernisasi Pertanian Indonesia',
                'description' => 'Menelusuri konsep alienasi (Entfremdung) Marx dalam konteks modernisasi pertanian: bagaimana petani terasing dari tanah, proses produksi, dan hasil kerja mereka sendiri.',
                'thinking_framework' => 'marxist',
                'article_type' => 'essay',
                'category_slug' => 'kajian-marxian',
                'tag_slugs' => ['karl-marx', 'petani', 'materialisme-historis'],
                'key_references' => [
                    ['author' => 'Marx, K.', 'year' => 1844, 'title' => 'Economic and Philosophic Manuscripts of 1844'],
                    ['author' => 'Scott, J. C.', 'year' => 1985, 'title' => 'Weapons of the Weak: Everyday Forms of Peasant Resistance'],
                    ['author' => 'Van der Ploeg, J. D.', 'year' => 2008, 'title' => 'The New Peasantries'],
                ],
                'weight' => 65,
            ],
            [
                'title' => 'Fetisisme Komoditas: Mengapa Petani Miskin di Tanah Subur',
                'description' => 'Membedah fetisisme komoditas (commodity fetishism) untuk menjelaskan paradoks kemiskinan petani di tengah kelimpahan hasil pertanian.',
                'thinking_framework' => 'marxist',
                'article_type' => 'essay',
                'category_slug' => 'kajian-marxian',
                'tag_slugs' => ['karl-marx', 'kapitalisme', 'petani'],
                'key_references' => [
                    ['author' => 'Marx, K.', 'year' => 1867, 'title' => 'Das Kapital, Volume I'],
                    ['author' => 'Harvey, D.', 'year' => 2010, 'title' => 'A Companion to Marx\'s Capital'],
                    ['author' => 'Bernstein, H.', 'year' => 2010, 'title' => 'Class Dynamics of Agrarian Change'],
                ],
                'weight' => 60,
            ],
            // === NEO-MARXIAN ===
            [
                'title' => 'Antonio Gramsci dan Hegemoni Budaya dalam Konteks Petani Indonesia',
                'description' => 'Eksplorasi konsep hegemoni kultural dan intelektual organik Gramsci, serta relevansinya bagi strategi pengorganisasian serikat petani di Indonesia.',
                'thinking_framework' => 'neo_marxian',
                'article_type' => 'thinker_profile',
                'category_slug' => 'pemikiran-kritis',
                'tag_slugs' => ['antonio-gramsci', 'hegemoni', 'petani'],
                'key_references' => [
                    ['author' => 'Gramsci, A.', 'year' => 1971, 'title' => 'Selections from the Prison Notebooks'],
                    ['author' => 'Scott, J. C.', 'year' => 1985, 'title' => 'Weapons of the Weak'],
                    ['author' => 'Spivak, G. C.', 'year' => 1988, 'title' => 'Can the Subaltern Speak?'],
                ],
                'weight' => 70,
            ],
            [
                'title' => 'Akumulasi melalui Perampasan: David Harvey dan Konflik Agraria di Indonesia',
                'description' => 'Menganalisis teori accumulation by dispossession Harvey untuk memahami konflik agraria kontemporer di Indonesia, termasuk land grabbing dan proyek infrastruktur.',
                'thinking_framework' => 'neo_marxian',
                'article_type' => 'essay',
                'category_slug' => 'ekonomi-politik-agraria',
                'tag_slugs' => ['david-harvey', 'konflik-agraria', 'akumulasi-primitif'],
                'key_references' => [
                    ['author' => 'Harvey, D.', 'year' => 2003, 'title' => 'The New Imperialism'],
                    ['author' => 'Harvey, D.', 'year' => 2005, 'title' => 'A Brief History of Neoliberalism'],
                    ['author' => 'Rachman, N. F.', 'year' => 2012, 'title' => 'Land Reform dari Masa ke Masa'],
                    ['author' => 'Konsorsium Pembaruan Agraria', 'year' => 2023, 'title' => 'Catatan Akhir Tahun 2023'],
                ],
                'weight' => 75,
            ],
            // === AGRARIAN POLITICAL ECONOMY ===
            [
                'title' => 'Pertanyaan Agraria Abad ke-21: Dari Kautsky hingga Bernstein',
                'description' => 'Menelusuri evolusi The Agrarian Question dari Kautsky (1899) hingga Bernstein (2010), dan apa maknanya bagi petani Indonesia saat ini.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'scientific_review',
                'category_slug' => 'ekonomi-politik-agraria',
                'tag_slugs' => ['reforma-agraria', 'petani', 'kapitalisme'],
                'key_references' => [
                    ['author' => 'Kautsky, K.', 'year' => 1899, 'title' => 'Die Agrarfrage (The Agrarian Question)'],
                    ['author' => 'Bernstein, H.', 'year' => 2010, 'title' => 'Class Dynamics of Agrarian Change'],
                    ['author' => 'White, B. & Wiradi, G.', 'year' => 2012, 'title' => 'Agrarian and Other Transformations of the Indonesian Countryside'],
                ],
                'weight' => 65,
            ],
            [
                'title' => 'Senjata Kaum Lemah: Perlawanan Sehari-hari Petani ala James C. Scott',
                'description' => 'Mengkaji konsep everyday forms of resistance Scott dan bagaimana petani Karawang melakukan perlawanan harian terhadap struktur ketidakadilan.',
                'thinking_framework' => 'agrarian_political_economy',
                'article_type' => 'thinker_profile',
                'category_slug' => 'pemikiran-kritis',
                'tag_slugs' => ['petani', 'subaltern', 'hegemoni'],
                'key_references' => [
                    ['author' => 'Scott, J. C.', 'year' => 1985, 'title' => 'Weapons of the Weak: Everyday Forms of Peasant Resistance'],
                    ['author' => 'Scott, J. C.', 'year' => 1998, 'title' => 'Seeing Like a State'],
                    ['author' => 'Wolf, E.', 'year' => 1969, 'title' => 'Peasant Wars of the Twentieth Century'],
                ],
                'weight' => 60,
            ],
            // === ECOPOLITICS ===
            [
                'title' => 'Environmentalism of the Poor: Gerakan Lingkungan Rakyat Kecil',
                'description' => 'Mengkaji teori Martinez-Alier tentang environmentalism of the poor dan relevansinya bagi perjuangan petani atas sumber daya alam.',
                'thinking_framework' => 'ecopolitics',
                'article_type' => 'essay',
                'category_slug' => 'ekologi-lingkungan',
                'tag_slugs' => ['ekologi-politik', 'petani', 'kedaulatan-pangan'],
                'key_references' => [
                    ['author' => 'Martinez-Alier, J.', 'year' => 2002, 'title' => 'The Environmentalism of the Poor'],
                    ['author' => 'Shiva, V.', 'year' => 1997, 'title' => 'Biopiracy: The Plunder of Nature and Knowledge'],
                    ['author' => 'Escobar, A.', 'year' => 1995, 'title' => 'Encountering Development'],
                ],
                'weight' => 60,
            ],
            [
                'title' => 'Kedaulatan Pangan vs Ketahanan Pangan: Kritik Struktural',
                'description' => 'Membedah perbedaan paradigmatik antara food sovereignty (La Via Campesina) dan food security (WTO/FAO), serta implikasinya bagi kebijakan pertanian Indonesia.',
                'thinking_framework' => 'ecopolitics',
                'article_type' => 'policy_analysis',
                'category_slug' => 'analisis-kebijakan',
                'tag_slugs' => ['kedaulatan-pangan', 'petani', 'neoliberalisme'],
                'key_references' => [
                    ['author' => 'Van der Ploeg, J. D.', 'year' => 2008, 'title' => 'The New Peasantries'],
                    ['author' => 'Shiva, V.', 'year' => 1991, 'title' => 'The Violence of the Green Revolution'],
                    ['author' => 'Bernstein, H.', 'year' => 2010, 'title' => 'Class Dynamics of Agrarian Change'],
                ],
                'weight' => 65,
            ],
            // === HUMAN RIGHTS ===
            [
                'title' => 'Hak Atas Pangan sebagai Hak Asasi: Perspektif Amartya Sen',
                'description' => 'Menganalisis kerangka kapabilitas (capability approach) Sen untuk memahami kemiskinan petani sebagai perampasan kebebasan fundamental.',
                'thinking_framework' => 'human_rights',
                'article_type' => 'essay',
                'category_slug' => 'hak-asasi-manusia',
                'tag_slugs' => ['petani', 'kedaulatan-pangan', 'hak-atas-tanah'],
                'key_references' => [
                    ['author' => 'Sen, A.', 'year' => 1999, 'title' => 'Development as Freedom'],
                    ['author' => 'Sen, A.', 'year' => 1981, 'title' => 'Poverty and Famines: An Essay on Entitlement and Deprivation'],
                    ['author' => 'Konsorsium Pembaruan Agraria', 'year' => 2023, 'title' => 'Catatan Akhir Tahun 2023'],
                ],
                'weight' => 55,
            ],
            // === POLICY ANALYSIS ===
            [
                'title' => 'UU Cipta Kerja dan Dampaknya terhadap Hak Petani Kecil',
                'description' => 'Dekonstruksi UU Cipta Kerja (Omnibus Law) dari perspektif ekonomi politik agraria, menganalisis bagaimana regulasi ini mempercepat dispossesi petani.',
                'thinking_framework' => 'neo_marxian',
                'article_type' => 'policy_analysis',
                'category_slug' => 'analisis-kebijakan',
                'tag_slugs' => ['reforma-agraria', 'neoliberalisme', 'konflik-agraria'],
                'key_references' => [
                    ['author' => 'Harvey, D.', 'year' => 2005, 'title' => 'A Brief History of Neoliberalism'],
                    ['author' => 'Rachman, N. F.', 'year' => 2012, 'title' => 'Land Reform dari Masa ke Masa'],
                    ['author' => 'Konsorsium Pembaruan Agraria', 'year' => 2023, 'title' => 'Catatan Akhir Tahun 2023'],
                ],
                'weight' => 70,
            ],
            // === HISTORICAL REVIEW ===
            [
                'title' => 'Gerakan Petani Indonesia dari Sarekat Islam hingga Era Reformasi',
                'description' => 'Tinjauan historis gerakan tani Indonesia: dari Sarekat Islam, BTI-PKI, hingga KPA dan serikat petani kontemporer.',
                'thinking_framework' => 'marxist',
                'article_type' => 'historical_review',
                'category_slug' => 'sejarah-gerakan',
                'tag_slugs' => ['petani', 'reforma-agraria', 'materialisme-historis'],
                'key_references' => [
                    ['author' => 'Wolf, E.', 'year' => 1969, 'title' => 'Peasant Wars of the Twentieth Century'],
                    ['author' => 'Wiradi, G.', 'year' => 2000, 'title' => 'Reforma Agraria: Perjalanan yang Belum Berakhir'],
                    ['author' => 'White, B. & Wiradi, G.', 'year' => 2012, 'title' => 'Agrarian and Other Transformations of the Indonesian Countryside'],
                    ['author' => 'Soekarno', 'year' => 1926, 'title' => 'Nasionalisme, Islamisme, dan Marxisme'],
                ],
                'weight' => 65,
            ],
            // === POSTMODERN ===
            [
                'title' => 'Subaltern dan Suara Petani: Membaca Spivak dalam Konteks Agraria Indonesia',
                'description' => 'Mengkaji teori subaltern Spivak dan Chatterjee untuk memahami bagaimana suara petani terpinggirkan dalam wacana pembangunan.',
                'thinking_framework' => 'postmodern',
                'article_type' => 'essay',
                'category_slug' => 'pemikiran-kritis',
                'tag_slugs' => ['subaltern', 'petani', 'post-development'],
                'key_references' => [
                    ['author' => 'Spivak, G. C.', 'year' => 1988, 'title' => 'Can the Subaltern Speak?'],
                    ['author' => 'Chatterjee, P.', 'year' => 2004, 'title' => 'The Politics of the Governed'],
                    ['author' => 'Escobar, A.', 'year' => 1995, 'title' => 'Encountering Development'],
                ],
                'weight' => 55,
            ],
            [
                'title' => 'Kekerasan Simbolik dan Habitus Petani: Perspektif Pierre Bourdieu',
                'description' => 'Menganalisis konsep kekerasan simbolik (symbolic violence), habitus, dan kapital Bourdieu untuk memahami bagaimana ketidaksetaraan agraria direproduksi secara kultural.',
                'thinking_framework' => 'postmodern',
                'article_type' => 'thinker_profile',
                'category_slug' => 'pemikiran-kritis',
                'tag_slugs' => ['hegemoni', 'petani', 'kapitalisme'],
                'key_references' => [
                    ['author' => 'Bourdieu, P.', 'year' => 1977, 'title' => 'Outline of a Theory of Practice'],
                    ['author' => 'Bourdieu, P.', 'year' => 1984, 'title' => 'Distinction: A Social Critique of the Judgement of Taste'],
                    ['author' => 'Scott, J. C.', 'year' => 1985, 'title' => 'Weapons of the Weak'],
                ],
                'weight' => 55,
            ],
            // === CRITICAL THEORY ===
            [
                'title' => 'Industri Budaya dan Kesadaran Petani: Membaca Mazhab Frankfurt',
                'description' => 'Mengkaji teori industri budaya Adorno-Horkheimer dan one-dimensional man Marcuse untuk memahami bagaimana media massa membentuk kesadaran palsu petani.',
                'thinking_framework' => 'critical_theory',
                'article_type' => 'essay',
                'category_slug' => 'pemikiran-kritis',
                'tag_slugs' => ['hegemoni', 'kapitalisme'],
                'key_references' => [
                    ['author' => 'Horkheimer, M. & Adorno, T. W.', 'year' => 1944, 'title' => 'Dialectic of Enlightenment'],
                    ['author' => 'Marcuse, H.', 'year' => 1964, 'title' => 'One-Dimensional Man'],
                    ['author' => 'Gramsci, A.', 'year' => 1971, 'title' => 'Selections from the Prison Notebooks'],
                ],
                'weight' => 50,
            ],
        ];

        foreach ($topics as $data) {
            $catSlug = $data['category_slug'];
            $tagSlugs = $data['tag_slugs'];
            unset($data['category_slug'], $data['tag_slugs']);

            $category = Category::where('slug', $catSlug)->first();
            $data['category_id'] = $category?->id;
            $data['slug'] = Str::slug($data['title']);
            $data['prompt_template'] = ''; // Uses auto-generated prompt from title+description
            $data['is_active'] = true;
            $data['times_used'] = 0;

            $topic = ArticleTopic::firstOrCreate(
                ['slug' => $data['slug']],
                $data
            );

            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id')->toArray();
            $topic->tags()->syncWithoutDetaching($tagIds);
        }
    }

    protected function seedPools(): void
    {
        // Pool 1: Kajian Mingguan Marxian
        $pool1 = ArticlePool::firstOrCreate(['slug' => 'kajian-mingguan-marxian'], [
            'name' => 'Kajian Mingguan Marxian',
            'slug' => 'kajian-mingguan-marxian',
            'description' => 'Essay dan kajian mingguan berbasis pemikiran Marxian dan Neo-Marxian.',
            'schedule_frequency' => 'weekly',
            'schedule_day' => 'monday',
            'schedule_time' => '07:00',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        $marxianTopics = ArticleTopic::whereIn('thinking_framework', ['marxist', 'neo_marxian'])
            ->pluck('id')->toArray();
        $pool1->topics()->syncWithoutDetaching($marxianTopics);

        // Pool 2: Analisis Kebijakan Bulanan
        $pool2 = ArticlePool::firstOrCreate(['slug' => 'analisis-kebijakan-bulanan'], [
            'name' => 'Analisis Kebijakan Bulanan',
            'slug' => 'analisis-kebijakan-bulanan',
            'description' => 'Analisis kebijakan publik bulanan dari perspektif ekonomi politik.',
            'schedule_frequency' => 'monthly',
            'schedule_day' => null,
            'schedule_time' => '09:00',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        $policyTopics = ArticleTopic::where('article_type', 'policy_analysis')
            ->pluck('id')->toArray();
        $pool2->topics()->syncWithoutDetaching($policyTopics);

        // Pool 3: Essay Ekologi & HAM Dua Mingguan
        $pool3 = ArticlePool::firstOrCreate(['slug' => 'essay-ekologi-ham'], [
            'name' => 'Essay Ekologi & HAM Dua Mingguan',
            'slug' => 'essay-ekologi-ham',
            'description' => 'Essay dan kajian tentang ekologi politik, lingkungan, dan hak asasi manusia.',
            'schedule_frequency' => 'biweekly',
            'schedule_day' => 'wednesday',
            'schedule_time' => '08:00',
            'articles_per_run' => 1,
            'is_active' => true,
            'auto_publish' => false,
        ]);

        $ecoHamTopics = ArticleTopic::whereIn('thinking_framework', ['ecopolitics', 'human_rights'])
            ->pluck('id')->toArray();
        $pool3->topics()->syncWithoutDetaching($ecoHamTopics);
    }
}
