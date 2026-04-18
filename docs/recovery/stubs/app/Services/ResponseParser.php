    public function hasAbstract(string $content): bool
    {
        return (bool) preg_match('/##\s*(Abstrak|Ringkasan(\s+praktis)?)/i', $content);
    }