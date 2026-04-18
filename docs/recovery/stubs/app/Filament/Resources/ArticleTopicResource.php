                            Forms\Components\Select::make('article_type')
                                ->label('Tipe Artikel')
                                ->options([
                                    'essay' => 'Essay Akademik',
                                    'opinion' => 'Opini Mendalam',
                                    'scientific_review' => 'Kajian Ilmiah',
                                    'policy_analysis' => 'Analisis Kebijakan',
                                    'thinker_profile' => 'Profil Pemikiran',
                                    'historical_review' => 'Tinjauan Historis',
                                    'member_guide' => 'Panduan ringkas anggota (AI praktis)',
                                ])