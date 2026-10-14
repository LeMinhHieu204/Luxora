<?php

namespace App\Support;

final class StorefrontData
{
    public static function image(string $id, int $width = 900): string
    {
        $files = [
            'photo-1525507119028-ed4c629a60a3' => 'hero-neutral.jpg',
            'photo-1483985988355-763728e1935b' => 'p-trench-model.jpg',
            'photo-1516826957135-700dedea698c' => 'cat-men-black.jpg',
            'photo-1553062407-98eeb64c6a62' => 'p-tote-neutral.jpg',
            'photo-1596462502278-27bfdc403348' => 'p-cream-neutral.jpg',
            'photo-1618220179428-22790b461013' => 'cat-home.jpg',
            'photo-1539109136881-3be0616acf4b' => 'p-trench-model.jpg',
            'photo-1548624313-0396c75ce8c5' => 'p-blazer-beige-portrait.jpg',
            'photo-1594223274512-ad4803739b7c' => 'p-tote-black.jpg',
            'photo-1618354691373-d851c5c3a990' => 'p-knit-packshot.jpg',
            'photo-1524805444758-089113d48a6d' => 'p-watch-neutral.jpg',
            'photo-1495555961986-6d4c1ecb7be3' => 'p-sneakers-neutral.jpg',
            'photo-1541643600914-78b084683601' => 'p-perfume-black.jpg',
            'photo-1556228578-8c89e6adf883' => 'p-cream-neutral.jpg',
            'photo-1584917865442-de89df76afd3' => 'p-structured-neutral.jpg',
            'photo-1584735175315-9d5df23860e6' => 'p-scarf-neutral.jpg',
            'photo-1596755389378-c31d21fd1273' => 'p-shirt-tan.jpg',
            'photo-1610701596007-11502861dcfa' => 'p-vase.jpg',
        ];

        return asset('assets/luxora/' . ($files[$id] ?? 'hero.jpg'));
    }

    public static function categories(): array
    {
        return [
            ['name' => 'Women', 'count' => 18, 'image' => self::image('photo-1483985988355-763728e1935b'), 'description' => 'Refined layers and modern essentials.'],
            ['name' => 'Men', 'count' => 12, 'image' => self::image('photo-1516826957135-700dedea698c'), 'description' => 'Quiet confidence, tailored simply.'],
            ['name' => 'Accessories', 'count' => 8, 'image' => self::image('photo-1553062407-98eeb64c6a62'), 'description' => 'The finishing touch to every look.'],
            ['name' => 'Beauty', 'count' => 5, 'image' => self::image('photo-1596462502278-27bfdc403348'), 'description' => 'Considered rituals for every day.'],
            ['name' => 'Home', 'count' => 5, 'image' => self::image('photo-1618220179428-22790b461013'), 'description' => 'Objects that make a space yours.'],
        ];
    }

    public static function products(): array
    {
        return [
            ['name' => 'Classic Trench Coat', 'category' => 'Women', 'price' => 129, 'image' => self::image('photo-1539109136881-3be0616acf4b'), 'alt' => 'Woman wearing a classic neutral trench coat'],
            ['name' => 'Tailored Blazer', 'category' => 'Women', 'price' => 119, 'oldPrice' => 149, 'image' => self::image('photo-1548624313-0396c75ce8c5'), 'alt' => 'Woman wearing a tailored beige blazer'],
            ['name' => 'Leather Tote Bag', 'category' => 'Accessories', 'price' => 89, 'image' => self::image('photo-1594223274512-ad4803739b7c'), 'alt' => 'Black leather tote bag'],
            ['name' => 'Ribbed Knit Sweater', 'category' => 'Women', 'price' => 59, 'image' => self::image('photo-1618354691373-d851c5c3a990'), 'alt' => 'Cream ribbed knit sweater'],
            ['name' => 'Minimal Watch', 'category' => 'Accessories', 'price' => 149, 'image' => self::image('photo-1524805444758-089113d48a6d'), 'alt' => 'Minimal black watch'],
            ['name' => 'Premium Sneakers', 'category' => 'Men', 'price' => 119, 'image' => self::image('photo-1495555961986-6d4c1ecb7be3'), 'alt' => 'Minimal white premium sneakers'],
            ['name' => 'Signature Eau de Parfum', 'category' => 'Beauty', 'price' => 79, 'oldPrice' => 99, 'image' => self::image('photo-1541643600914-78b084683601'), 'alt' => 'Signature perfume bottle'],
            ['name' => 'Hydrating Face Cream', 'category' => 'Beauty', 'price' => 49, 'image' => self::image('photo-1556228578-8c89e6adf883'), 'alt' => 'Hydrating face cream jar'],
            ['name' => 'Structured Handbag', 'category' => 'Accessories', 'price' => 139, 'image' => self::image('photo-1584917865442-de89df76afd3'), 'alt' => 'Structured caramel handbag'],
            ['name' => 'Silk Scarf', 'category' => 'Accessories', 'price' => 39, 'image' => self::image('photo-1584735175315-9d5df23860e6'), 'alt' => 'Silk scarf in neutral tones'],
            ['name' => 'Linen Shirt', 'category' => 'Men', 'price' => 69, 'oldPrice' => 89, 'image' => self::image('photo-1596755389378-c31d21fd1273'), 'alt' => 'Relaxed linen shirt'],
            ['name' => 'Ceramic Vase', 'category' => 'Home', 'price' => 49, 'image' => self::image('photo-1610701596007-11502861dcfa'), 'alt' => 'Neutral ceramic vase with foliage'],
        ];
    }

    public static function reviews(): array
    {
        return [
            ['name' => 'Emily R.', 'initials' => 'ER', 'quote' => 'The quality is outstanding and the pieces are even more beautiful in person. Luxora is now my go-to store.'],
            ['name' => 'James T.', 'initials' => 'JT', 'quote' => 'Fast shipping, elegant packaging, and amazing customer service. Highly recommend!'],
            ['name' => 'Jophie L.', 'initials' => 'JL', 'quote' => 'I love how everything feels so premium and timeless. Worth every penny.'],
        ];
    }
}
