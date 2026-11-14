<?php
session_start();

$katalog_cemilan = [
    [
        'nama' => 'Edamame Rebus',
        'kategori' => 'Protein Nabati',
        'porsi' => '100 gram',
        'kalori' => '121 kkal',
        'gula' => '2.2 g',
        'protein' => '11.0 g',
        'lemak' => '5.0 g',
        'karbohidrat' => '8.9 g',
        'serat' => '5.2 g',
        'kalsium' => '63 mg',
        'natrium' => '6 mg',
        'kalium' => '436 mg',
        'vitamin_c' => '6.1 mg',
        'zat_besi' => '2.3 mg',
        'gambar' => 'https://img-global.cpcdn.com/recipes/f603d9e0c6273775/1200x630cq70/photo.jpg',
        'deskripsi' => 'Kacang kedelai muda kaya protein nabati lengkap, membantu pemulihan otot dan menjaga rasa kenyang lebih lama.'
    ],
    [
        'nama' => 'Tempe Kukus / Bakar',
        'kategori' => 'Protein Nabati',
        'porsi' => '100 gram',
        'kalori' => '190 kkal',
        'gula' => '1.4 g',
        'protein' => '18.5 g',
        'lemak' => '10.0 g',
        'karbohidrat' => '7.6 g',
        'serat' => '3.0 g',
        'kalsium' => '111 mg',
        'natrium' => '9 mg',
        'kalium' => '234 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '2.7 mg',
        'gambar' => 'https://i.ytimg.com/vi/nRmf_igSlZs/maxresdefault.jpg',
        'deskripsi' => 'Superfood fermentasi lokal tinggi protein dan isoflavon, sangat baik untuk pencernaan dan imunitas tubuh.'
    ],
    [
        'nama' => 'Tahu Putih Kukus',
        'kategori' => 'Protein Nabati',
        'porsi' => '100 gram',
        'kalori' => '70 kkal',
        'gula' => '0.6 g',
        'protein' => '8.0 g',
        'lemak' => '4.0 g',
        'karbohidrat' => '1.9 g',
        'serat' => '0.4 g',
        'kalsium' => '123 mg',
        'natrium' => '7 mg',
        'kalium' => '121 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '0.8 mg',
        'gambar' => 'https://image.idntimes.com/post/20231120/reseptahu-c7c8a956c08d5240d23a9803c984bd4e-d883d6b1ee05094ea728eec945583324.png',
        'deskripsi' => 'Sumber protein nabati rendah kalori dan tinggi kalsium yang ramah lambung serta mudah diserap tubuh.'
    ],
    [
        'nama' => 'Telur Ayam Kampung Rebus',
        'kategori' => 'Protein Hewani',
        'porsi' => '1 butir (50 gram)',
        'kalori' => '68 kkal',
        'gula' => '0.3 g',
        'protein' => '6.0 g',
        'lemak' => '4.8 g',
        'karbohidrat' => '0.6 g',
        'serat' => '0.0 g',
        'kalsium' => '25 mg',
        'natrium' => '70 mg',
        'kalium' => '65 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '0.9 mg',
        'gambar' => 'https://www.tagar.id/Asset/uploads2019/1639580036590-telur-rebus.jpg',
        'deskripsi' => 'Mengandung protein hayati bernilai biologis tinggi serta kolin alami untuk mendukung konsentrasi belajar.'
    ],
    [
        'nama' => 'Ikan Pindang Suwir Kukus',
        'kategori' => 'Protein Hewani',
        'porsi' => '100 gram',
        'kalori' => '115 kkal',
        'gula' => '0.0 g',
        'protein' => '18.0 g',
        'lemak' => '4.2 g',
        'karbohidrat' => '0.0 g',
        'serat' => '0.0 g',
        'kalsium' => '80 mg',
        'natrium' => '320 mg',
        'kalium' => '210 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '1.1 mg',
        'gambar' => 'https://img-global.cpcdn.com/recipes/33b6e404c4c4fceb/1200x630cq70/photo.jpg',
        'deskripsi' => 'Sumber protein hewani lokal kaya asam lemak esensial yang baik untuk kesehatan jantung dan fungsi otak.'
    ],
    [
        'nama' => 'Kacang Hijau Rebus (Tanpa Santan)',
        'kategori' => 'Protein & Serat',
        'porsi' => '100 gram',
        'kalori' => '105 kkal',
        'gula' => '2.0 g',
        'protein' => '7.0 g',
        'lemak' => '0.4 g',
        'karbohidrat' => '19.0 g',
        'serat' => '4.0 g',
        'kalsium' => '27 mg',
        'natrium' => '2 mg',
        'kalium' => '266 mg',
        'vitamin_c' => '1.0 mg',
        'zat_besi' => '1.4 mg',
        'gambar' => 'https://cdn.stkipmb.ac.id/image/manfaat-kacang-hijau-rebus/public',
        'deskripsi' => 'Kacang-kacangan kaya serat pangan larut dan antioksidan vitexin untuk menjaga vitalitas tubuh.'
    ],
    [
        'nama' => 'Ubi Jalar Ungu Kukus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '114 kkal',
        'gula' => '4.2 g',
        'protein' => '1.6 g',
        'lemak' => '0.4 g',
        'karbohidrat' => '27.0 g',
        'serat' => '3.0 g',
        'kalsium' => '30 mg',
        'natrium' => '27 mg',
        'kalium' => '337 mg',
        'vitamin_c' => '19.6 mg',
        'zat_besi' => '0.6 mg',
        'gambar' => 'https://ahligizi.id/blog/wp-content/uploads/2021/08/sweet-potatoes-6348396_1920.jpg',
        'deskripsi' => 'Karbohidrat kompleks kaya antosianin (antioksidan kuat) yang memberikan energi stabil tanpa lonjakan gula darah.'
    ],
    [
        'nama' => 'Ubi Jalar Kuning Rebus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '86 kkal',
        'gula' => '4.1 g',
        'protein' => '1.6 g',
        'lemak' => '0.1 g',
        'karbohidrat' => '20.1 g',
        'serat' => '3.0 g',
        'kalsium' => '38 mg',
        'natrium' => '55 mg',
        'kalium' => '337 mg',
        'vitamin_c' => '2.4 mg',
        'zat_besi' => '0.6 mg',
        'gambar' => 'https://asset.kompas.com/crops/PkRtvRJeT6xzAtNKq6H2HCA-o6k=/0x0:1000x667/1200x800/data/photo/2022/02/05/61fe25192121f.jpg',
        'deskripsi' => 'Sumber beta-karoten (provitamin A) tinggi yang sangat baik untuk kesehatan penglihatan dan sistem imun.'
    ],
    [
        'nama' => 'Singkong Kukus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '155 kkal',
        'gula' => '1.7 g',
        'protein' => '1.2 g',
        'lemak' => '0.3 g',
        'karbohidrat' => '38.1 g',
        'serat' => '1.8 g',
        'kalsium' => '33 mg',
        'natrium' => '14 mg',
        'kalium' => '271 mg',
        'vitamin_c' => '20.6 mg',
        'zat_besi' => '0.3 mg',
        'gambar' => 'https://img-global.cpcdn.com/recipes/05c5b504775ec2a1/680x482cq70/singkong-kukus-foto-resep-utama.jpg',
        'deskripsi' => 'Umbi Nusantara bebas gluten yang menyediakan pasokan energi harian secara berkelanjutan.'
    ],
    [
        'nama' => 'Jagung Manis Rebus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '96 kkal',
        'gula' => '6.0 g',
        'protein' => '3.4 g',
        'lemak' => '1.5 g',
        'karbohidrat' => '21.0 g',
        'serat' => '2.4 g',
        'kalsium' => '2 mg',
        'natrium' => '15 mg',
        'kalium' => '270 mg',
        'vitamin_c' => '6.8 mg',
        'zat_besi' => '0.5 mg',
        'gambar' => 'https://cdn.kibrispdr.org/data/674/jagung-manis-rebus-7.jpg',
        'deskripsi' => 'Mengandung vitamin B kompleks dan lutein alami yang mendukung metabolisme energi serta kesehatan mata.'
    ],
    [
        'nama' => 'Pisang Kepok Rebus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '110 kkal',
        'gula' => '12.0 g',
        'protein' => '1.2 g',
        'lemak' => '0.3 g',
        'karbohidrat' => '28.0 g',
        'serat' => '2.6 g',
        'kalsium' => '8 mg',
        'natrium' => '1 mg',
        'kalium' => '358 mg',
        'vitamin_c' => '8.7 mg',
        'zat_besi' => '0.4 mg',
        'gambar' => 'https://asset.tribunnews.com/jkNIGL4iSCNDlvhBHGvdyhfsU_s=/1200x675/filters:upscale():quality(30):format(webp):focal(0.5x0.5:0.5x0.5)/aceh/foto/bank/originals/Ilustrasi-Manfaat-Pisang-Rebus-Bagi-Kesehatan.jpg',
        'deskripsi' => 'Kandungan kalium dan karbohidrat alaminya efektif mencegah kelelahan fisik dan menjaga keseimbangan cairan.'
    ],
    [
        'nama' => 'Kacang Tanah Sangrai',
        'kategori' => 'Lemak Sehat & Protein',
        'porsi' => '30 gram',
        'kalori' => '170 kkal',
        'gula' => '1.5 g',
        'protein' => '7.5 g',
        'lemak' => '14.0 g',
        'karbohidrat' => '4.8 g',
        'serat' => '2.4 g',
        'kalsium' => '28 mg',
        'natrium' => '5 mg',
        'kalium' => '200 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '1.3 mg',
        'gambar' => 'https://blog.indonetwork.co.id/wp-content/uploads/2021/12/manfaat-kacang-tanah-sangrai.jpg',
        'deskripsi' => 'Sumber lemak tak jenuh tunggal yang baik untuk kesehatan jantung dan cadangan energi tahan lama.'
    ],
    [
        'nama' => 'Pepaya California Potong',
        'kategori' => 'Buah Segar',
        'porsi' => '150 gram',
        'kalori' => '60 kkal',
        'gula' => '11.0 g',
        'protein' => '0.9 g',
        'lemak' => '0.4 g',
        'karbohidrat' => '15.0 g',
        'serat' => '2.5 g',
        'kalsium' => '30 mg',
        'natrium' => '12 mg',
        'kalium' => '275 mg',
        'vitamin_c' => '91.8 mg',
        'zat_besi' => '0.4 mg',
        'gambar' => 'https://d1vbn70lmn1nqe.cloudfront.net/prod/wp-content/uploads/2022/01/19025202/Ragam-Manfaat-Pepaya-California-yang-Jarang-Diketahui-02.jpg',
        'deskripsi' => 'Buah kaya enzim papain dan vitamin C yang melancarkan sistem pencernaan serta menyegarkan tubuh.'
    ],
    [
        'nama' => 'Jambu Biji Merah Segar',
        'kategori' => 'Buah Segar',
        'porsi' => '100 gram',
        'kalori' => '68 kkal',
        'gula' => '8.9 g',
        'protein' => '2.6 g',
        'lemak' => '0.9 g',
        'karbohidrat' => '14.3 g',
        'serat' => '5.4 g',
        'kalsium' => '18 mg',
        'natrium' => '2 mg',
        'kalium' => '417 mg',
        'vitamin_c' => '228.3 mg',
        'zat_besi' => '0.3 mg',
        'gambar' => 'https://i0.wp.com/umsu.ac.id/berita/wp-content/uploads/2023/01/manfaat-jambu-biji-merah.webp?fit=600%2C350&ssl=1',
        'deskripsi' => 'Juara bertahan vitamin C tinggi dan serat pangan untuk memperkuat daya tahan tubuh secara optimal.'
    ],
    [
        'nama' => 'Timun Segar Lalapan',
        'kategori' => 'Sayur Segar',
        'porsi' => '100 gram',
        'kalori' => '15 kkal',
        'gula' => '1.7 g',
        'protein' => '0.6 g',
        'lemak' => '0.1 g',
        'karbohidrat' => '3.6 g',
        'serat' => '0.5 g',
        'kalsium' => '16 mg',
        'natrium' => '2 mg',
        'kalium' => '147 mg',
        'vitamin_c' => '2.8 mg',
        'zat_besi' => '0.3 mg',
        'gambar' => 'https://s3-id-jkt-1.kilatstorage.id/cobisnis/2026/01/Mentimun.jpg',
        'deskripsi' => 'Kandungan air tinggi (sekitar 95%) yang membantu menjaga hidrasi tubuh agar tetap bugar sepanjang hari.'
    ],
    [
        'nama' => 'Talas Korina Rebus',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '112 kkal',
        'gula' => '0.4 g',
        'protein' => '1.5 g',
        'lemak' => '0.2 g',
        'karbohidrat' => '26.5 g',
        'serat' => '4.1 g',
        'kalsium' => '18 mg',
        'natrium' => '10 mg',
        'kalium' => '391 mg',
        'vitamin_c' => '4.8 mg',
        'zat_besi' => '0.6 mg',
        'gambar' => 'https://asset-a.grid.id/crop/0x0:0x0/700x465/photo/2022/07/20/5fe0650372553jpeg-20220720050248.jpeg',
        'deskripsi' => 'Umbi lokal kaya serat pangan yang ramah pencernaan dan memberikan rasa kenyang lebih lama.'
    ],
    [
        'nama' => 'Kentang Kukus Kulit',
        'kategori' => 'Karbohidrat Kompleks',
        'porsi' => '100 gram',
        'kalori' => '87 kkal',
        'gula' => '1.2 g',
        'protein' => '1.9 g',
        'lemak' => '0.1 g',
        'karbohidrat' => '20.1 g',
        'serat' => '2.1 g',
        'kalsium' => '10 mg',
        'natrium' => '6 mg',
        'kalium' => '421 mg',
        'vitamin_c' => '12.6 mg',
        'zat_besi' => '0.3 mg',
        'gambar' => 'https://cdn.grid.id/crop/0x0:0x0/700x465/photo/2024/02/24/bwdjpg-20240224115723.jpg',
        'deskripsi' => 'Sumber kalium tinggi dan karbohidrat bersih yang sangat ideal untuk pemulihan energi setelah beraktivitas.'
    ],
    [
        'nama' => 'Apel Malang Potong',
        'kategori' => 'Buah Segar',
        'porsi' => '100 gram',
        'kalori' => '52 kkal',
        'gula' => '10.4 g',
        'protein' => '0.3 g',
        'lemak' => '0.2 g',
        'karbohidrat' => '13.8 g',
        'serat' => '2.4 g',
        'kalsium' => '6 mg',
        'natrium' => '1 mg',
        'kalium' => '107 mg',
        'vitamin_c' => '4.6 mg',
        'zat_besi' => '0.1 mg',
        'gambar' => 'https://down-id.img.susercontent.com/file/id-11134207-7r98s-ly7d8tmol2xs76',
        'deskripsi' => 'Buah lokal segar kaya pektin yang membantu mengontrol kadar kolesterol serta menyegarkan rongga mulut.'
    ],
    [
        'nama' => 'Salak Pondoh Segar',
        'kategori' => 'Buah Segar',
        'porsi' => '100 gram',
        'kalori' => '77 kkal',
        'gula' => '8.0 g',
        'protein' => '0.5 g',
        'lemak' => '0.4 g',
        'karbohidrat' => '20.9 g',
        'serat' => '0.3 g',
        'kalsium' => '28 mg',
        'natrium' => '3 mg',
        'kalium' => '145 mg',
        'vitamin_c' => '8.4 mg',
        'zat_besi' => '4.2 mg',
        'gambar' => 'https://media.istockphoto.com/id/1538876457/id/foto/tampilan-close-up-pondoh-salak-manis-tropis-segar-dari-indonesia-di-pasar-buah-ular-atau-salak.jpg?s=170667a&w=0&k=20&c=cMk7aVD8aZehv1D0ACUJhH-Tx6oxHPcfJDI9Yji5AOo=',
        'deskripsi' => 'Buah eksotis kaya antioksidan dan zat besi yang unik serta lezat dinikmati sebagai cemilan siang hari.'
    ],
    [
        'nama' => 'Belimbing Madu Segar',
        'kategori' => 'Buah Segar',
        'porsi' => '100 gram',
        'kalori' => '31 kkal',
        'gula' => '3.9 g',
        'protein' => '1.0 g',
        'lemak' => '0.3 g',
        'karbohidrat' => '6.7 g',
        'serat' => '2.8 g',
        'kalsium' => '3 mg',
        'natrium' => '2 mg',
        'kalium' => '133 mg',
        'vitamin_c' => '34.4 mg',
        'zat_besi' => '0.1 mg',
        'gambar' => 'https://img.lazcdn.com/g/p/2877951ba852a32dad1feb11ba619058.jpg_720x720q80.jpg',
        'deskripsi' => 'Buah kaya air dan vitamin C dengan kalori sangat rendah, cocok untuk menjaga hidrasi dan kesegaran.'
    ],
    [
        'nama' => 'Semangka Merah Potong',
        'kategori' => 'Buah Segar',
        'porsi' => '150 gram',
        'kalori' => '46 kkal',
        'gula' => '9.4 g',
        'protein' => '0.9 g',
        'lemak' => '0.2 g',
        'karbohidrat' => '11.5 g',
        'serat' => '0.6 g',
        'kalsium' => '11 mg',
        'natrium' => '2 mg',
        'kalium' => '170 mg',
        'vitamin_c' => '12.3 mg',
        'zat_besi' => '0.4 mg',
        'gambar' => 'https://api.omela.com/storage/1113/conversions/a0e4aa832f34970a005a10ab4ce659d8-large.jpg',
        'deskripsi' => 'Sumber likopen tinggi dan elektrolit alami yang menyegarkan dahaga serta melindungi kesehatan jantung.'
    ],
    [
        'nama' => 'Melon Madu Potong',
        'kategori' => 'Buah Segar',
        'porsi' => '150 gram',
        'kalori' => '54 kkal',
        'gula' => '12.0 g',
        'protein' => '0.8 g',
        'lemak' => '0.2 g',
        'karbohidrat' => '13.4 g',
        'serat' => '1.4 g',
        'kalsium' => '14 mg',
        'natrium' => '24 mg',
        'kalium' => '398 mg',
        'vitamin_c' => '29.3 mg',
        'zat_besi' => '0.3 mg',
        'gambar' => 'https://yentit.com/wp-content/uploads/2020/01/honeydew-1296x728-feature.jpg',
        'deskripsi' => 'Buah manis kaya vitamin C dan potasium untuk membantu mengontrol tekanan darah agar tetap stabil.'
    ],
    [
        'nama' => 'Kacang Kedelai Sangrai',
        'kategori' => 'Protein Nabati',
        'porsi' => '30 gram',
        'kalori' => '135 kkal',
        'gula' => '2.0 g',
        'protein' => '11.2 g',
        'lemak' => '6.0 g',
        'karbohidrat' => '9.0 g',
        'serat' => '3.5 g',
        'kalsium' => '80 mg',
        'natrium' => '2 mg',
        'kalium' => '450 mg',
        'vitamin_c' => '2.0 mg',
        'zat_besi' => '2.5 mg',
        'gambar' => 'https://img-global.cpcdn.com/recipes/84c3f0de2705a46b/1200x630cq70/photo.jpg',
        'deskripsi' => 'Cemilan gurih kaya protein nabati dan isoflavon yang praktis dinikmati kapan saja.'
    ],
    [
        'nama' => 'Putih Telur Rebus',
        'kategori' => 'Protein Hewani',
        'porsi' => '2 butir (60 gram)',
        'kalori' => '31 kkal',
        'gula' => '0.2 g',
        'protein' => '6.4 g',
        'lemak' => '0.1 g',
        'karbohidrat' => '0.5 g',
        'serat' => '0.0 g',
        'kalsium' => '4 mg',
        'natrium' => '100 mg',
        'kalium' => '84 mg',
        'vitamin_c' => '0.0 mg',
        'zat_besi' => '0.1 mg',
        'gambar' => 'https://nutrinusa.id/images/makanan/putih-telur-rebus.jpg',
        'deskripsi' => 'Sumber protein murni tanpa lemak dan tanpa kolesterol, sangat pas untuk mendukung massa otot.'
    ],
    [
        'nama' => 'Jeruk Manis Lokal Segar',
        'kategori' => 'Buah Segar',
        'porsi' => '100 gram',
        'kalori' => '45 kkal',
        'gula' => '9.0 g',
        'protein' => '0.9 g',
        'lemak' => '0.1 g',
        'karbohidrat' => '11.8 g',
        'serat' => '2.4 g',
        'kalsium' => '40 mg',
        'natrium' => '0 mg',
        'kalium' => '181 mg',
        'vitamin_c' => '53.2 mg',
        'zat_besi' => '0.1 mg',
        'gambar' => 'https://nusantaranews.co/assets/uploads/2017/09/Jeruk-Manis-foto-via-raja-makan.jpg',
        'deskripsi' => 'Buah kaya vitamin C esensial untuk meningkatkan daya tahan tubuh dan menangkal radikal bebas.'
    ]
];
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Lengkap Takaran Gizi Cemilan Sehat - GiziLokal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root { --primary-green: #059669; --emerald-light: #34d399; --dark-green: #064e3b; --light-green: #ecfdf5; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background: #f8fafc; color: #334155; line-height: 1.7; }
        
        .navbar { backdrop-filter: blur(16px); background: rgba(255, 255, 255, 0.95); box-shadow: 0 4px 20px rgba(0,0,0,0.03); padding: 1rem 0; border-bottom: 1px solid #e2e8f0; }
        .header-section { padding: 45px 0 25px; margin-top: 50px; background: linear-gradient(180deg, #ecfdf5 0%, #f8fafc 100%); border-bottom: 1px solid #e2e8f0; } 
        
        .content-card { background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.02); transition: 0.3s; }
        .content-card:hover { transform: translateY(-3px); box-shadow: 0 8px 20px rgba(0,0,0,0.05); }
        .card-img-top { height: 180px; object-fit: cover; }
        
        .card-footer-info {
            background-color: #f8fafc;
            padding: 8px 16px;
            border-top: 1px solid #edf2f7;
            font-size: 0.8rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            color: #64748b;
        }
        
        .footer-extra { padding: 30px 0; text-align: center; color: #94a3b8; font-size: 0.85rem; border-top: 1px solid #e2e8f0; background: #ffffff; margin-top: 40px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand fw-extrabold fs-3 text-success d-flex align-items-center text-decoration-none" href="index.php">
                <i class="fa-solid fa-seedling me-2 text-success"></i>Gizi<span class="text-dark">Lokal</span>
            </a>
            <div class="collapse navbar-collapse justify-content-end">
                <ul class="navbar-nav align-items-center">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-success rounded-pill px-4 py-2 fw-bold shadow-sm" style="background-color: var(--primary-green); border: none;">Kembali ke Beranda</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="header-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8 mx-auto text-center">
                    <span class="badge px-3 py-2 rounded-pill mb-2 fw-bold shadow-sm" style="background-color: #d1fae5; color: var(--dark-green);">
                        <i class="fa-solid fa-calculator me-1"></i> Takaran Gizi Valid & Teruji Lengkap
                    </span>
                    <h1 class="fw-extrabold text-dark mb-2">Cemilan Sehat Nusantara & Informasi Makro-Mikronutrisi</h1>
                    <p class="text-muted">Database lengkap takaran saji, kandungan kalori, gula, protein, lemak, serat, vitamin, hingga mineral spesifik per sajian untuk setiap jenis cemilan.</p>
                </div>
            </div>
        </div>
    </header>

    <section class="pb-4 mb-4 mt-4">
        <div class="container">
            <div class="row g-3">
                <?php foreach ($katalog_cemilan as $index => $cemilan): ?>
                <div class="col-md-4">
                    <div class="content-card h-100 d-flex flex-column">
                        <img src="<?php echo $cemilan['gambar']; ?>" class="card-img-top" alt="<?php echo $cemilan['nama']; ?>">
                        
                        <div class="p-3 d-flex flex-column flex-grow-1">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1 small fw-bold" style="font-size: 0.75rem;">
                                    <?php echo $cemilan['kategori']; ?>
                                </span>
                                <span class="text-muted small fw-semibold" style="font-size: 0.75rem;"><i class="fa-solid fa-scale-balanced me-1 text-success"></i> <?php echo $cemilan['porsi']; ?></span>
                            </div>

                            <h6 class="fw-bold text-dark mb-2"><?php echo $cemilan['nama']; ?></h6>
                            <p class="text-muted small mb-3 flex-grow-1" style="font-size: 0.8rem; line-height: 1.5;"><?php echo $cemilan['deskripsi']; ?></p>

                            <div class="bg-light p-2 rounded-3 mb-2 border">
                                <div class="row text-center g-1">
                                    <div class="col-4 border-end">
                                        <span class="d-block text-muted" style="font-size: 10px;">Kalori</span>
                                        <strong class="text-dark small" style="font-size: 0.75rem;"><?php echo $cemilan['kalori']; ?></strong>
                                    </div>
                                    <div class="col-4 border-end">
                                        <span class="d-block text-muted" style="font-size: 10px;">Gula</span>
                                        <strong class="text-danger small" style="font-size: 0.75rem;"><?php echo $cemilan['gula']; ?></strong>
                                    </div>
                                    <div class="col-4">
                                        <span class="d-block text-muted" style="font-size: 10px;">Lemak</span>
                                        <strong class="text-success small" style="font-size: 0.75rem;"><?php echo $cemilan['lemak']; ?></strong>
                                    </div>
                                </div>
                            </div>

                            <button type="button" class="btn btn-outline-success btn-sm w-100 fw-semibold mt-auto py-1.5" style="font-size: 0.8rem;" data-bs-toggle="modal" data-bs-target="#modalDetail<?php echo $index; ?>">
                                <i class="fa-solid fa-file-lines me-1"></i> Lihat Takaran Gizi Lengkap
                            </button>
                        </div>
                        
                        <div class="card-footer-info">
                            <span><i class="fa-solid fa-bolt me-1 text-warning"></i> Protein: <strong><?php echo $cemilan['protein']; ?></strong></span>
                            <span>Serat: <strong><?php echo $cemilan['serat']; ?></strong></span>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDetail<?php echo $index; ?>" tabindex="-1" aria-labelledby="modalLabel<?php echo $index; ?>" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-success text-white py-2">
                                <h5 class="modal-title fw-bold fs-6" id="modalLabel<?php echo $index; ?>">Takaran Gizi: <?php echo $cemilan['nama']; ?></h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-3">
                                <div class="text-center mb-3">
                                    <img src="<?php echo $cemilan['gambar']; ?>" class="img-fluid rounded shadow-sm" style="height: 140px; width: 100%; object-fit: cover;" alt="<?php echo $cemilan['nama']; ?>">
                                    <span class="badge bg-light text-dark border mt-2 px-3 py-1"><i class="fa-solid fa-scale-balanced me-1 text-success"></i> Ukuran Porsi Saji: <strong><?php echo $cemilan['porsi']; ?></strong></span>
                                </div>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Energi / Kalori</span>
                                        <span class="badge bg-success rounded-pill"><?php echo $cemilan['kalori']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Karbohidrat Total</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['karbohidrat']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Gula Sederhana</span>
                                        <span class="fw-bold text-danger"><?php echo $cemilan['gula']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Protein Murni</span>
                                        <span class="fw-bold text-success"><?php echo $cemilan['protein']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Lemak Total</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['lemak']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Serat Pangan (Fiber)</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['serat']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Kalsium (Ca)</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['kalsium']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Zat Besi (Fe)</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['zat_besi']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Kalium (K)</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['kalium']; ?></span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <span>Vitamin C</span>
                                        <span class="fw-bold text-dark"><?php echo $cemilan['vitamin_c']; ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <footer class="footer-extra">
        <div class="container">
            <p class="mb-0">&copy; 2026 GiziLokal Indonesia. Rujukan Data Gizi Pangan Indonesia yang Valid.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>