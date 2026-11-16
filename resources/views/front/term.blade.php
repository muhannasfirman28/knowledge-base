@extends('layouts.app')

@section('title', 'Syarat & Ketentuan - KostPedia')

@section('content')

<div class="bg-white py-20 border-b border-slate-200/60">
    <div class="max-w-4xl mx-auto px-4 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 text-emerald-600 text-sm font-bold mb-6 tracking-wide">
            <i class="fa-solid fa-scale-balanced"></i> LEGAL & KEBIJAKAN
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-slate-900 mb-6 tracking-tight">Syarat & Ketentuan Sewa</h1>
        <p class="text-lg text-slate-500 leading-relaxed max-w-2xl mx-auto">
            Harap membaca peraturan ini dengan seksama. Menghuni unit kami berarti Anda menyetujui seluruh ketentuan privasi dan keamanan yang berlaku.
        </p>
    </div>
</div>

<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-200/60 p-8 md:p-12 space-y-12">

        <section>
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 font-bold text-xl flex-shrink-0 shadow-sm">
                    1
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">Ketentuan Umum</h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed pl-2 border-l-2 border-slate-100">
                        <p>1.1. Calon penyewa wajib menyerahkan fotokopi/scan KTP (Kartu Tanda Penduduk) dan Kartu Keluarga (untuk pasutri) yang masih berlaku saat pendaftaran.</p>
                        <p>1.2. Satu kamar standar hanya diperbolehkan dihuni oleh maksimal <strong>1 (satu) orang</strong>. Penambahan penghuni wajib lapor dan dikenakan biaya tambahan sesuai kebijakan pengelola.</p>
                        <p>1.3. Penyewa dilarang keras memindahtangankan hak sewa atau menyewakan kembali kamar (sub-letting) kepada pihak ketiga tanpa persetujuan tertulis.</p>
                        <p>1.4. Kunci kamar adalah tanggung jawab penuh penyewa. Segala bentuk duplikasi kunci harus seizin pengelola.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 font-bold text-xl flex-shrink-0 shadow-sm">
                    2
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">Pembayaran & Tagihan</h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed pl-2 border-l-2 border-slate-100">
                        <p class="bg-emerald-50 p-4 rounded-xl border border-emerald-100 text-emerald-900 shadow-sm">
                            <strong class="text-emerald-700"><i class="fa-solid fa-circle-info mr-1"></i> Sistem Pembayaran Sewa (Prepaid):</strong> Pembayaran sewa wajib dilakukan <strong>DI AWAL</strong> sebelum masa sewa bulan berjalan dimulai. Tagihan akan diterbitkan secara otomatis oleh sistem, dan wajib dilunasi tepat pada tanggal jatuh tempo.
                        </p>
                        <p>2.2. Segala transaksi pembayaran dianggap sah hanya jika dilakukan melalui sistem pembayaran digital di website ini (Midtrans) atau transfer ke rekening resmi yang terdaftar di Admin.</p>
                        <p>2.3. Bukti pembayaran fisik (struk/screenshot) wajib disimpan sebagai cadangan jika terjadi gangguan sistem.</p>
                        <p>2.4. Uang sewa, deposit, atau biaya lain yang telah dibayarkan <strong>tidak dapat dikembalikan (Non-Refundable)</strong> dengan alasan apapun, termasuk jika penyewa memutuskan keluar lebih awal.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 font-bold text-xl flex-shrink-0 shadow-sm">
                    3
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">Tata Tertib & Larangan</h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed pl-2 border-l-2 border-slate-100">
                        <p>3.1. <span class="text-rose-600 font-bold bg-rose-50 px-2 py-0.5 rounded border border-rose-100">ZERO TOLERANCE:</span> Dilarang keras membawa, menyimpan, mengedarkan, atau menggunakan Narkoba, Minuman Keras, dan barang ilegal lainnya. Pelanggaran ini akan langsung dilaporkan ke Polisi.</p>
                        <p>3.2. Dilarang melakukan tindakan asusila atau membawa pasangan lawan jenis ke dalam kamar (kecuali kost khusus Pasutri dengan bukti nikah sah).</p>
                        <p>3.3. Wajib menjaga ketenangan (Silent Hours) pada pukul <strong>22.00 - 06.00 WIB</strong>.</p>
                        <p>3.4. Dilarang merokok di dalam kamar. Merokok hanya diperbolehkan di area terbuka yang disediakan untuk menjaga sirkulasi udara dan mencegah kebakaran.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 font-bold text-xl flex-shrink-0 shadow-sm">
                    4
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">Fasilitas & Kebersihan</h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed pl-2 border-l-2 border-slate-100">
                        <p>4.1. Penyewa wajib menjaga kebersihan fasilitas bersama (Dapur, Kulkas, Parkir).</p>
                        <p>4.2. Sampah pribadi wajib dibuang ke tempat pembuangan akhir yang disediakan setiap hari.</p>
                        <p>4.3. Kerusakan fasilitas kamar (AC, Kasur, Lemari, Tembok) akibat kelalaian penyewa (<i>human error</i>) wajib diganti sesuai harga perbaikan/barang baru.</p>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-emerald-600 font-bold text-xl flex-shrink-0 shadow-sm">
                    5
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4 mt-2">Check-out / Pindah</h3>
                    <div class="text-slate-600 space-y-3 leading-relaxed pl-2 border-l-2 border-slate-100">
                        <p>5.1. Pemberitahuan berhenti sewa wajib dilakukan minimal <strong>14 hari (2 minggu)</strong> sebelum tanggal keluar.</p>
                        <p>5.2. Kamar harus dikembalikan dalam kondisi bersih dan kosong. Jika meninggalkan barang sampah/rusak, akan dikenakan biaya kebersihan (cleaning fee).</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="bg-rose-50/50 p-6 md:p-8 rounded-3xl border border-rose-100">
            <div class="flex items-start gap-5 mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center font-bold text-xl flex-shrink-0 shadow-lg shadow-rose-500/20">
                    <i class="fa-solid fa-gavel"></i>
                </div>
                <div class="w-full">
                    <h3 class="text-2xl font-bold text-rose-700 mb-6 mt-2">Sanksi & Konsekuensi</h3>
                    <div class="text-slate-700 space-y-4 leading-relaxed">
                        
                        <div class="bg-white p-5 rounded-2xl border border-rose-100/60 shadow-sm hover:shadow-md transition">
                            <strong class="text-rose-600 block mb-2 text-lg">A. Keterlambatan Pembayaran</strong>
                            <ul class="list-none space-y-2 text-sm">
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span>Terlambat 1-3 hari: Peringatan tertulis via WhatsApp/Email.</span></li>
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span>Terlambat > 3 hari: Denda harian sebesar <strong>Rp 50.000,- / hari</strong>.</span></li>
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span>Terlambat > 7 hari: <strong>Pemutusan fasilitas sementara</strong> (Listrik/Internet/Air dimatikan) dan akses kamar dikunci.</span></li>
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span>Terlambat > 14 hari: Dianggap mengundurkan diri sepihak. Barang akan dikeluarkan dari kamar (dikosongkan paksa).</span></li>
                            </ul>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-rose-100/60 shadow-sm hover:shadow-md transition">
                            <strong class="text-rose-600 block mb-2 text-lg">B. Pelanggaran Tata Tertib</strong>
                            <ul class="list-none space-y-2 text-sm">
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span><strong>Ringan:</strong> (Gaduh, Merokok, Parkir sembarangan) Teguran lisan hingga SP. Akumulasi 3x SP berakibat pengusiran.</span></li>
                                <li class="flex gap-2"><i class="fa-solid fa-angle-right text-rose-400 mt-1"></i> <span><strong>Berat:</strong> (Narkoba, Asusila, Kriminal) Pemutusan hubungan sewa <strong>SAAT ITU JUGA</strong> (No Refund) dan lapor Polisi.</span></li>
                            </ul>
                        </div>

                        <div class="bg-white p-5 rounded-2xl border border-rose-100/60 shadow-sm hover:shadow-md transition">
                            <strong class="text-rose-600 block mb-2 text-lg">C. Kerusakan Properti</strong>
                            <p class="text-sm pl-4 border-l-2 border-rose-200">Penyewa wajib mengganti biaya perbaikan atau pembelian unit baru jika terjadi kerusakan fasilitas akibat kelalaian. Deposit (jika ada) hangus.</p>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>

    <div class="mt-12 text-center bg-slate-900 rounded-[2rem] p-10 max-w-3xl mx-auto shadow-2xl relative overflow-hidden">
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-48 h-48 bg-teal-500/20 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h3 class="text-2xl font-bold text-white mb-3">Setuju dengan ketentuan di atas?</h3>
            <p class="text-slate-400 mb-8 text-sm max-w-md mx-auto">
                Jika ada poin yang kurang jelas, silakan diskusikan dengan admin sebelum Anda melakukan pembayaran atau serah terima kunci.
            </p>
            <a href="https://wa.me/{{ env('PHONE_WA', '6281234567890') }}" target="_blank" 
               class="inline-flex items-center gap-3 bg-emerald-500 text-slate-900 font-bold py-4 px-8 rounded-xl hover:bg-emerald-400 transition-all duration-300 shadow-lg shadow-emerald-500/20 hover:-translate-y-1">
                <i class="fa-brands fa-whatsapp text-2xl"></i> Hubungi Admin
            </a>
        </div>
    </div>
</div>

@endsection