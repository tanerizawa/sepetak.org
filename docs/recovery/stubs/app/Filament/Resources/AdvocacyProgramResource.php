                    Forms\Components\TextInput::make('location_text')
                        ->label('Lokasi')
                        ->maxLength(255),

                    Forms\Components\Select::make('lead_user_id')
                        ->label('Penanggung Jawab')
                        ->relationship('leadUser', 'name')
                        ->searchable()
                        ->preload(),
                ])
                ->columns(2),