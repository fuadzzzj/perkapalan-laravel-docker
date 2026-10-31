const achievementData = {
    'lomba-kapal': {
        title: 'Lomba Kapal Mini Antar Sekolah',
        rank: 'Juara 1',
        year: '2025',
        image: '/images/workshop/workshop.jpg',
        summary: 'Tim perkapalan kami berhasil menjuarai lomba kapal mini antar sekolah dengan desain yang ringan, stabil, dan efisien. Keberhasilan ini tidak datang dari satu hari, melainkan hasil latihan, evaluasi, dan kerja tim yang konsisten.',
        story: 'Awalnya, kami hanya mencoba membuat prototype kapal mini dari bahan yang sederhana. Banyak bagian harus diperbaiki karena hasilnya belum stabil saat diuji di air. Kami belajar dari setiap kesalahan, mulai dari bentuk lambung hingga keseimbangan saat kapal melaju. Proses ini memakan waktu cukup lama, tetapi membuat kami semakin paham pentingnya ketelitian, eksperimen, dan kerja sama tim.',
        gallery: [
            'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1520637836862-4d197d17c90a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=800&q=80'
        ]
    },
    'lomba-navigasi': {
        title: 'Lomba Navigasi & Keselamatan Laut',
        rank: 'Juara 2',
        year: '2024',
        image: '/images/samudra_biru/samudra biru.png',
        summary: 'Tim kami menempati posisi kedua dalam lomba navigasi dan keselamatan laut berkat kemampuan membaca peta, kerja sama, dan keputusan yang cepat saat simulasi darurat.',
        story: 'Dalam simulasi, setiap anggota tim harus berdiri di bawah tekanan dengan situasi yang berubah cepat. Kami belajar bahwa navigasi bukan hanya soal arah, tetapi juga konsentrasi, komunikasi, dan pengambilan keputusan tepat di saat genting. Kegigihan itu membawa kami ke tahap final dan menorehkan hasil yang membanggakan.',
        gallery: [
            'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=800&q=80'
        ]
    },
    'lomba-las': {
        title: 'Kompetisi Las & Fabrikasi',
        rank: 'Juara 3',
        year: '2024',
        image: '/images/teknik_besi/las.jpg',
        summary: 'Tim kami berhasil masuk top tiga dalam kompetisi las dan fabrikasi karena kualitas sambungan, ketelitian pengerjaan, dan hasil finishing yang rapi.',
        story: 'Pada lomba ini, kami menghadapi tantangan besar karena waktu pengerjaan sangat terbatas. Setiap detik harus dimanfaatkan dengan benar, mulai dari menyiapkan alat, memperhatikan sudut las, hingga memastikan hasil sambungan tidak cacat. Dalam prosesnya, kami belajar bahwa kesabaran dan fokus jauh lebih penting daripada kecepatan.',
        gallery: [
            'https://images.unsplash.com/photo-1520637836862-4d197d17c90a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=800&q=80'
        ]
    },
    'lomba-inovasi': {
        title: 'Inovasi Teknologi Perkapalan',
        rank: 'Finalis',
        year: '2023',
        image: '/images/prestasi/prestasi 1.jpg',
        summary: 'Dalam ajang inovasi teknologi perkapalan, tim kami berhasil masuk final dan mempresentasikan ide solusi yang relevan untuk efisiensi kerja dan keamanan di bidang maritim.',
        story: 'Ide yang kami usulkan lahir dari pengalaman dan pengamatan di lapangan. Kami melihat banyak pekerjaan perkapalan masih dilakukan secara konvensional, sehingga ada peluang untuk membuat proses kerja lebih aman dan efisien. Proses presentasi sangat menegangkan, tetapi kami tetap semangat karena yakin ide ini memiliki manfaat nyata untuk masa depan industri maritim.',
        gallery: [
            'https://images.unsplash.com/photo-1520607162513-77705c0f0d4a?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1500375592092-40eb2168fd21?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493246507139-91e8fad9978e?auto=format&fit=crop&w=800&q=80'
        ]
    }
};

const cards = document.querySelectorAll('.achievement-card');
const titleEl = document.getElementById('detail-title');
const imageEl = document.getElementById('detail-image');
const rankEl = document.getElementById('detail-rank');
const yearEl = document.getElementById('detail-year');
const summaryEl = document.getElementById('detail-summary');
const storyEl = document.getElementById('detail-story');
const galleryEl = document.getElementById('detail-gallery');

function renderDetail(id) {
    const item = achievementData[id];
    if (!item) return;

    titleEl.textContent = item.title;
    imageEl.src = item.image;
    imageEl.alt = item.title;
    rankEl.textContent = item.rank;
    yearEl.textContent = item.year;
    summaryEl.textContent = item.summary;
    storyEl.textContent = item.story;

    galleryEl.innerHTML = item.gallery.map(url => `
        <img src="${url}" alt="${item.title}">
    `).join('');

    cards.forEach((card) => {
        card.classList.toggle('active', card.dataset.id === id);
    });
}

cards.forEach((card) => {
    card.addEventListener('click', () => renderDetail(card.dataset.id));
});

renderDetail('lomba-kapal');
