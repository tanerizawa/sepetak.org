                    Forms\Components\TimePicker::make('schedule_time')
                        ->label('Waktu (satu slot, WIB)')
                        ->required()
                        ->default('07:00')
                        ->seconds(false)
                        ->helperText('Dipakai bila slot harian kosong.'),

                    Forms\Components\TagsInput::make('schedule_times')
                        ->label('Slot jam harian (WIB)')
                        ->placeholder('04:45')
                        ->helperText('Satu tag = satu HH:MM. Contoh lima kali sehari: 04:45, 12:10, 15:20, 18:05, 19:25.')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('articles_per_run')
                        ->label('Artikel per Jadwal')
                        ->numeric()
                        ->minValue(1)
                        ->maxValue(10)
                        ->default(1),
                ])->columns(2),

            Forms\Components\Section::make('Konfigurasi')
                ->description('Ringkas praktis = prompt & validasi artikel ringan anggota. Pillar = esai/kajian panjang. Pool lama jangan dihapus sembarangan: nonaktifkan atau ubah profil; hindari banyak pool aktif yang overlap jam.')
                ->schema([
                    Forms\Components\Select::make('content_profile')
                        ->label('Profil konten AI')
                        ->options([
                            'pillar' => 'Pillar / akademik panjang',
                            'member_practical' => 'Ringkas praktis untuk anggota (tips, hukum ringkas, organisasi)',
                        ])
                        ->default('pillar')
                        ->required()
                        ->live(),

                    Forms\Components\Toggle::make('is_active')
                        ->label('Aktif')
                        ->default(true),

                    Forms\Components\Toggle::make('auto_publish')
                        ->label('Auto Publish')
                        ->helperText('⚠️ Jika aktif, artikel langsung dipublikasikan tanpa review.')
                        ->default(false),