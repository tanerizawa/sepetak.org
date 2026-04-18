            Forms\Components\Select::make('action_type')
                ->label('Tipe Aksi')
                ->options([
                    'meeting'     => 'Rapat',
                    'training'    => 'Pelatihan',
                    'campaign'    => 'Kampanye',
                    'field_visit' => 'Kunjungan Lapangan',
                    'legal'       => 'Proses Hukum',
                    'other'       => 'Lainnya',
                ])
                ->required(),