                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'reported' => 'info',
                        'under_review' => 'warning',
                        'mediation' => 'primary',
                        'legal_process' => 'danger',
                        'resolved' => 'success',
                        'closed' => 'gray',
                        default => 'gray',
                    }),