        'default' => [
            'HTML.Doctype'             => 'HTML 4.01 Transitional',
            'HTML.Allowed'             => 'div,b,strong,i,em,u,a[href|title],ul,ol,li,p[style],br,span[style],img[width|height|alt|src]',
            'CSS.AllowedProperties'    => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align',
            'AutoFormat.AutoParagraph' => true,
            'AutoFormat.RemoveEmpty'   => true,
        ],
        /**
         * HTML dari Filament RichEditor (TipTap): heading, daftar, tautan, kutipan, blok kode.
         * Dipakai PageObserver / PostObserver. Tanpa heading di HTML.Allowed, Purifier
         * mengubah <h2> menjadi <p> sehingga hanya tersisa satu "judul" visual di halaman.
         */
        'filament_rich_html' => [
            'HTML.Doctype' => 'HTML 4.01 Transitional',
            'HTML.Allowed' => implode(',', [
                'h1[style]', 'h2[style]', 'h3[style]', 'h4[style]', 'h5[style]', 'h6[style]',
                'p[style]', 'br',
                'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'del',
                'a[href|title|target|rel]',
                'ul', 'ol', 'li',
                'blockquote',
                'pre', 'code[class]',
                'hr',
                'span[style]', 'div[style]',
                'sub', 'sup',
                'img[width|height|alt|src]',
            ]),
            'CSS.AllowedProperties' => 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align,margin,margin-left',
            'AutoFormat.AutoParagraph' => false,
            'AutoFormat.RemoveEmpty' => false,
            'HTML.TargetBlank' => true,
        ],