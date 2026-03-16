<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RecipeSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // ── Banners ──
        $banners = [
            ['offer_text' => 'Dapatkan Resep Menarik Setiap Hari'],
            ['offer_text' => 'Kumpulan Masakan Nusantara'],
            ['offer_text' => 'Tips & Trik Memasak Chef Profesional'],
        ];

        foreach ($banners as $banner) {
            Banner::create([
                'image_url'  => '',
                'offer_text' => $banner['offer_text'],
            ]);
        }

        // ── Categories ──
        $categories = [
            'Masakan Lokal',
            'Barat',
            'Cepat Saji',
            'Diet Sehat',
            'Pencuci Mulut',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name'     => $name,
                'icon_url' => '',
            ]);
        }

        // ── Recipes ──
        $recipes = [
            [
                'name'         => 'Nasi Goreng Spesial',
                'category'     => 'Masakan Lokal',
                'is_exclusive' => true,
                'is_trending'  => false,
                'description'  => 'Nasi goreng dengan bumbu rahasia warisan keluarga yang disajikan dengan telur mata sapi dan kerupuk renyah.',
                'ingredients'  => ['Nasi putih semalam', 'Bawang merah & putih', 'Cabai merah', 'Kecap manis', 'Telur', 'Garam & Merica'],
                'instructions' => ['Haluskan bumbu.', 'Tumis bumbu hingga harum.', 'Masukkan nasi dan kecap.', 'Aduk rata dan sajikan dengan telur.'],
            ],
            [
                'name'         => 'Burger Sapi Premium',
                'category'     => 'Barat',
                'is_exclusive' => false,
                'is_trending'  => true,
                'description'  => 'Daging sapi pilihan dengan roti burger yang lembut dan sayuran segar langsung dari kebun.',
                'ingredients'  => ['Daging sapi giling', 'Roti Bun', 'Selada', 'Tomat', 'Keju Cheddar', 'Mayones'],
                'instructions' => ['Bentuk daging menjadi patty.', 'Panggang daging hingga matang.', 'Susun roti, selada, daging, dan keju.', 'Sajikan.'],
            ],
            [
                'name'         => 'Sate Ayam Madura',
                'category'     => 'Masakan Lokal',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Sate ayam dengan bumbu kacang yang kental dan gurih, khas dari daerah Madura.',
                'ingredients'  => ['Daging ayam', 'Kacang tanah sangrai', 'Kecap manis', 'Bawang merah', 'Lontong'],
                'instructions' => ['Tusuk daging ayam.', 'Bakar di atas arang.', 'Haluskan kacang untuk bumbu.', 'Sajikan sate dengan bumbu kacang.'],
            ],
            [
                'name'         => 'Pizza Margherita',
                'category'     => 'Barat',
                'is_exclusive' => true,
                'is_trending'  => false,
                'description'  => 'Pizza klasik Italia dengan saus tomat segar, keju mozzarella, dan daun basil.',
                'ingredients'  => ['Adonan pizza', 'Saus tomat', 'Keju Mozzarella', 'Basil segar', 'Minyak zaitun'],
                'instructions' => ['Pipihkan adonan.', 'Oleskan saus dan taburkan keju.', 'Panggang dalam oven panas.', 'Tambahkan basil.'],
            ],
            [
                'name'         => 'Gado-Gado Betawi',
                'category'     => 'Masakan Lokal',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Salad khas Indonesia dengan bumbu kacang yang kaya rasa, disajikan dengan kerupuk emping.',
                'ingredients'  => ['Tahu & Tempe', 'Sayuran rebus', 'Telur rebus', 'Kacang tanah', 'Gula merah'],
                'instructions' => ['Ulek bumbu kacang.', 'Rebus semua sayuran.', 'Campur sayuran dengan bumbu.', 'Sajikan dengan emping.'],
            ],
            [
                'name'         => 'Pasta Carbonara',
                'category'     => 'Barat',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Pasta krimi dengan telur, keju parmesan, dan potongan daging asap yang lezat.',
                'ingredients'  => ['Spaghetti', 'Telur', 'Keju Parmesan', 'Daging asap', 'Lada hitam'],
                'instructions' => ['Rebus spaghetti.', 'Tumis daging asap.', 'Campur telur dan keju.', 'Aduk pasta panas dengan campuran telur.'],
            ],
            [
                'name'         => 'Soto Ayam Lamongan',
                'category'     => 'Masakan Lokal',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Soto ayam dengan kuah kuning bening dan taburan koya yang gurih.',
                'ingredients'  => ['Ayam', 'Kunyit & Jahe', 'Bawang putih', 'Krupuk udang (untuk koya)', 'Soun'],
                'instructions' => ['Rebus ayam dengan bumbu.', 'Suwir daging ayam.', 'Buat bubuk koya.', 'Sajikan dengan kuah panas.'],
            ],
            [
                'name'         => 'Steak Sirloin',
                'category'     => 'Barat',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Daging sapi bagian sirloin yang dipanggang sempurna dengan saus jamur.',
                'ingredients'  => ['Daging Sirloin', 'Mentega', 'Bawang putih', 'Rosmari', 'Kentang'],
                'instructions' => ['Bumbui daging.', 'Panggang di wajan panas dengan mentega.', 'Diamkan sejenak (resting).', 'Sajikan dengan kentang.'],
            ],
            [
                'name'         => 'Rendang Daging Sapi',
                'category'     => 'Masakan Lokal',
                'is_exclusive' => false,
                'is_trending'  => true,
                'description'  => 'Masakan terlezat di dunia. Daging sapi yang dimasak lama dengan santan dan rempah hingga kering.',
                'ingredients'  => ['Daging sapi', 'Santan kelapa', 'Bumbu rendang', 'Serai', 'Daun jeruk'],
                'instructions' => ['Masak bumbu dan santan.', 'Masukkan daging.', 'Aduk terus hingga santan mengering.', 'Sajikan.'],
            ],
            [
                'name'         => 'Salad Buah Segar',
                'category'     => 'Diet Sehat',
                'is_exclusive' => false,
                'is_trending'  => false,
                'description'  => 'Potongan buah musiman yang segar disajikan dengan dressing yogurt madu.',
                'ingredients'  => ['Melon', 'Semangka', 'Apel', 'Yogurt', 'Madu'],
                'instructions' => ['Potong semua buah.', 'Campur yogurt dan madu.', 'Tuangkan di atas buah.', 'Sajikan dingin.'],
            ],
            ['name' => 'Ayam Bakar Taliwang', 'category' => 'Masakan Lokal', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Ayam pedas khas Lombok.', 'ingredients' => ['Ayam', 'Cabai'], 'instructions' => ['Bakar ayam.']],
            ['name' => 'Hotdog Spesial', 'category' => 'Cepat Saji', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Hotdog dengan sosis jumbo.', 'ingredients' => ['Roti hotdog', 'Sosis'], 'instructions' => ['Panggang sosis.']],
            ['name' => 'Mie Bakso', 'category' => 'Masakan Lokal', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Mie dengan bakso sapi kenyal.', 'ingredients' => ['Mie', 'Bakso'], 'instructions' => ['Rebus mie.']],
            ['name' => 'Pancake Blueberry', 'category' => 'Pencuci Mulut', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Pancake lembut blueberry.', 'ingredients' => ['Tepung', 'Blueberry'], 'instructions' => ['Masak pancake.']],
            ['name' => 'Nasi Uduk Gurih', 'category' => 'Masakan Lokal', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Nasi uduk santan gurih.', 'ingredients' => ['Beras', 'Santan'], 'instructions' => ['Masak nasi.']],
            ['name' => 'Waffle Madu', 'category' => 'Pencuci Mulut', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Waffle renyah dengan madu.', 'ingredients' => ['Adonan waffle', 'Madu'], 'instructions' => ['Cetak waffle.']],
            ['name' => 'Bubur Ayam Cirebon', 'category' => 'Masakan Lokal', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Bubur ayam lengkap.', 'ingredients' => ['Beras', 'Ayam'], 'instructions' => ['Masak bubur.']],
            ['name' => 'Smoothie Bowl', 'category' => 'Diet Sehat', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Sarapan sehat kaya vitamin.', 'ingredients' => ['Pisang', 'Berry'], 'instructions' => ['Blender buah.']],
            ['name' => 'Martabak Cokelat', 'category' => 'Pencuci Mulut', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Martabak manis cokelat lumer.', 'ingredients' => ['Tepung', 'Cokelat'], 'instructions' => ['Masak adonan.']],
            ['name' => 'Lasagna Keju', 'category' => 'Barat', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Lasagna lapis keju melimpah.', 'ingredients' => ['Pasta lasagna', 'Keju'], 'instructions' => ['Panggang lasagna.']],
            ['name' => 'Tahu Gejrot', 'category' => 'Masakan Lokal', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Tahu khas Cirebon pedas.', 'ingredients' => ['Tahu pong', 'Cabai'], 'instructions' => ['Ulek bumbu.']],
            ['name' => 'Mac n Cheese', 'category' => 'Barat', 'is_exclusive' => false, 'is_trending' => false, 'description' => 'Makaroni keju krimi.', 'ingredients' => ['Makaroni', 'Keju'], 'instructions' => ['Rebus makaroni.']],
        ];

        foreach ($recipes as $data) {
            $category = Category::where('name', $data['category'])->first();

            $recipe = Recipe::create([
                'name'         => $data['name'],
                'image_url'    => '',
                'is_exclusive' => $data['is_exclusive'] ?? false,
                'is_trending'  => $data['is_trending'] ?? false,
                'description'  => $data['description'],
                'ingredients'  => $data['ingredients'],
                'instructions' => $data['instructions'],
            ]);

            $recipe->categories()->attach($category->id);
        }
    }
}
