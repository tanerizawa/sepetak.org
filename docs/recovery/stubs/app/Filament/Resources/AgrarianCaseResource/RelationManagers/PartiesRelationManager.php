            Forms\Components\Select::make('party_type')
                ->label('Tipe Pihak')
                ->options([
                    'member'      => 'Anggota',
                    'community'   => 'Komunitas / Warga',
                    'institution' => 'Lembaga / LSM',
                    'company'     => 'Perusahaan',
                    'government'  => 'Pemerintah',
                    'other'       => 'Lainnya',
                ])
                ->required(),