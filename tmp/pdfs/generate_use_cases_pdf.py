from reportlab.lib import colors
from reportlab.lib.enums import TA_CENTER, TA_LEFT
from reportlab.lib.pagesizes import A4
from reportlab.lib.styles import ParagraphStyle, getSampleStyleSheet
from reportlab.lib.units import cm
from reportlab.platypus import (
    Flowable,
    KeepTogether,
    ListFlowable,
    ListItem,
    PageBreak,
    Paragraph,
    SimpleDocTemplate,
    Spacer,
    Table,
    TableStyle,
)


OUTPUT = "output/pdf/akhwat-gym-use-cases.pdf"


class SectionBand(Flowable):
    def __init__(self, text, color):
        super().__init__()
        self.text = text
        self.color = color
        self.height = 34

    def wrap(self, avail_width, avail_height):
        self.width = avail_width
        return avail_width, self.height

    def draw(self):
        self.canv.setFillColor(self.color)
        self.canv.roundRect(0, 0, self.width, self.height, 8, fill=1, stroke=0)
        self.canv.setFillColor(colors.white)
        self.canv.setFont("Helvetica-Bold", 13)
        self.canv.drawString(14, 11, self.text)


def styles():
    base = getSampleStyleSheet()
    return {
        "title": ParagraphStyle(
            "Title",
            parent=base["Title"],
            fontName="Helvetica-Bold",
            fontSize=25,
            leading=31,
            textColor=colors.HexColor("#3D145F"),
            alignment=TA_CENTER,
            spaceAfter=10,
        ),
        "subtitle": ParagraphStyle(
            "Subtitle",
            parent=base["BodyText"],
            fontSize=11,
            leading=16,
            textColor=colors.HexColor("#5E4A6E"),
            alignment=TA_CENTER,
            spaceAfter=18,
        ),
        "h1": ParagraphStyle(
            "Heading1",
            parent=base["Heading1"],
            fontName="Helvetica-Bold",
            fontSize=17,
            leading=22,
            textColor=colors.HexColor("#3D145F"),
            spaceBefore=14,
            spaceAfter=8,
        ),
        "h2": ParagraphStyle(
            "Heading2",
            parent=base["Heading2"],
            fontName="Helvetica-Bold",
            fontSize=13,
            leading=17,
            textColor=colors.HexColor("#4F1D75"),
            spaceBefore=10,
            spaceAfter=5,
        ),
        "body": ParagraphStyle(
            "Body",
            parent=base["BodyText"],
            fontName="Helvetica",
            fontSize=9.2,
            leading=13,
            textColor=colors.HexColor("#292233"),
            spaceAfter=6,
        ),
        "small": ParagraphStyle(
            "Small",
            parent=base["BodyText"],
            fontName="Helvetica",
            fontSize=8.2,
            leading=11,
            textColor=colors.HexColor("#5B5264"),
        ),
        "cell": ParagraphStyle(
            "Cell",
            parent=base["BodyText"],
            fontName="Helvetica",
            fontSize=7.9,
            leading=10.5,
            textColor=colors.HexColor("#292233"),
        ),
        "cell_bold": ParagraphStyle(
            "CellBold",
            parent=base["BodyText"],
            fontName="Helvetica-Bold",
            fontSize=8,
            leading=10.5,
            textColor=colors.HexColor("#3D145F"),
        ),
        "cover_meta": ParagraphStyle(
            "CoverMeta",
            parent=base["BodyText"],
            fontName="Helvetica-Bold",
            fontSize=9,
            leading=12,
            textColor=colors.white,
            alignment=TA_CENTER,
        ),
    }


def p(text, style):
    return Paragraph(text, style)


def bullet(items, style):
    return ListFlowable(
        [ListItem(p(item, style), leftIndent=10) for item in items],
        bulletType="bullet",
        leftIndent=14,
        bulletFontName="Helvetica",
        bulletFontSize=7,
        bulletColor=colors.HexColor("#B72CCB"),
        spaceBefore=2,
        spaceAfter=6,
    )


def table(data, col_widths, style):
    body = []
    for row_idx, row in enumerate(data):
        row_style = style["cell_bold"] if row_idx == 0 else style["cell"]
        body.append([p(str(cell), row_style) for cell in row])
    t = Table(body, colWidths=col_widths, repeatRows=1, hAlign="LEFT")
    t.setStyle(
        TableStyle(
            [
                ("BACKGROUND", (0, 0), (-1, 0), colors.HexColor("#F2D7F5")),
                ("TEXTCOLOR", (0, 0), (-1, 0), colors.HexColor("#3D145F")),
                ("GRID", (0, 0), (-1, -1), 0.35, colors.HexColor("#D8C9E3")),
                ("VALIGN", (0, 0), (-1, -1), "TOP"),
                ("LEFTPADDING", (0, 0), (-1, -1), 6),
                ("RIGHTPADDING", (0, 0), (-1, -1), 6),
                ("TOPPADDING", (0, 0), (-1, -1), 5),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 5),
                ("ROWBACKGROUNDS", (0, 1), (-1, -1), [colors.white, colors.HexColor("#FCF7FD")]),
            ]
        )
    )
    return t


def use_case(code, title, actor, trigger, goal, main_flow, alternatives, result, style):
    return KeepTogether(
        [
            p(f"{code} - {title}", style["h2"]),
            table(
                [
                    ["Aktor", "Pemicu", "Tujuan", "Hasil Akhir"],
                    [actor, trigger, goal, result],
                ],
                [3.0 * cm, 4.1 * cm, 5.0 * cm, 5.0 * cm],
                style,
            ),
            Spacer(1, 5),
            table(
                [
                    ["Flow Utama", "Kondisi Alternatif / Gagal"],
                    ["<br/>".join([f"{i + 1}. {item}" for i, item in enumerate(main_flow)]), "<br/>".join(alternatives)],
                ],
                [8.6 * cm, 8.5 * cm],
                style,
            ),
            Spacer(1, 8),
        ]
    )


def page_footer(canvas, doc):
    canvas.saveState()
    width, height = A4
    canvas.setStrokeColor(colors.HexColor("#E9DDED"))
    canvas.line(1.6 * cm, 1.35 * cm, width - 1.6 * cm, 1.35 * cm)
    canvas.setFont("Helvetica", 7.5)
    canvas.setFillColor(colors.HexColor("#75667F"))
    canvas.drawString(1.6 * cm, 1.05 * cm, "Akhwat Gym - Dokumen Use Case")
    canvas.drawRightString(width - 1.6 * cm, 1.05 * cm, f"Halaman {doc.page}")
    canvas.restoreState()


def build():
    style = styles()
    doc = SimpleDocTemplate(
        OUTPUT,
        pagesize=A4,
        rightMargin=1.6 * cm,
        leftMargin=1.6 * cm,
        topMargin=1.5 * cm,
        bottomMargin=1.8 * cm,
        title="Akhwat Gym - Use Case",
        author="Akhwat Gym",
    )

    story = []
    story.append(Spacer(1, 2.2 * cm))
    story.append(p("Akhwat Gym", style["title"]))
    story.append(p("Dokumen Use Case Platform Manajemen Fitness", style["subtitle"]))
    story.append(Spacer(1, 0.5 * cm))
    intro = Table(
        [
            [
                p("Cakupan: aplikasi member mobile, admin panel, membership, jadwal kelas, personal trainer, pembayaran manual, toko, absensi, notifikasi, dan laporan operasional.", style["cover_meta"])
            ]
        ],
        colWidths=[17.1 * cm],
    )
    intro.setStyle(
        TableStyle(
            [
                ("BACKGROUND", (0, 0), (-1, -1), colors.HexColor("#6F2188")),
                ("BOX", (0, 0), (-1, -1), 0, colors.HexColor("#6F2188")),
                ("LEFTPADDING", (0, 0), (-1, -1), 18),
                ("RIGHTPADDING", (0, 0), (-1, -1), 18),
                ("TOPPADDING", (0, 0), (-1, -1), 14),
                ("BOTTOMPADDING", (0, 0), (-1, -1), 14),
            ]
        )
    )
    story.append(intro)
    story.append(Spacer(1, 1.0 * cm))
    story.append(p("Versi: 1.0 - Disusun untuk menyelaraskan flow bisnis, admin, dan mobile app.", style["subtitle"]))
    story.append(PageBreak())

    story.append(SectionBand("1. Ringkasan Sistem", colors.HexColor("#6F2188")))
    story.append(p("Akhwat Gym adalah platform operasional untuk membantu member mendaftar, membeli paket, melihat jadwal, booking kelas, check-in, membeli produk, dan mengirim konfirmasi pembayaran. Admin menggunakan panel web untuk mengelola data operasional dan memverifikasi pembayaran.", style["body"]))
    story.append(p("Prinsip bisnis utama", style["h2"]))
    story.append(bullet([
        "Pembayaran tidak memakai Midtrans. Member melihat rekening/QRIS, lalu menekan tombol konfirmasi pembayaran.",
        "Member dapat upload bukti pembayaran atau mengirim bukti melalui WhatsApp.",
        "Manfaat membership, order, kelas sekali datang, dan personal trainer sekali datang baru aktif setelah pembayaran disetujui admin.",
        "Owner mengatur rekening bank dan QRIS. Admin lokasi fokus pada operasional harian.",
        "Kapasitas kelas dan kuota kunjungan membership harus selalu dijaga agar tidak melebihi aturan paket.",
    ], style["body"]))

    story.append(p("Aktor dan hak akses", style["h2"]))
    story.append(table(
        [
            ["Aktor", "Channel", "Peran Utama"],
            ["Member", "Mobile app", "Registrasi, login, beli membership, booking kelas, check-in, beli produk, lihat riwayat, konfirmasi pembayaran."],
            ["Owner", "Admin panel", "Mengatur bisnis, melihat laporan, mengelola rekening/QRIS, mengelola user dan role."],
            ["Super admin", "Admin panel", "Mengelola seluruh data operasional, user, role, transaksi, produk, jadwal, dan laporan."],
            ["Admin di lokasi", "Admin panel", "Mengelola operasional harian: member, trainer, jadwal, produk, transaksi, absensi, dan konfirmasi pembayaran."],
            ["Trainer", "Data internal", "Terkait jadwal kelas dan personal trainer. Dashboard trainer khusus dapat dikembangkan pada fase berikutnya."],
        ],
        [3.0 * cm, 3.1 * cm, 11.0 * cm],
        style,
    ))

    story.append(PageBreak())
    story.append(SectionBand("2. Use Case Mobile Member", colors.HexColor("#6F2188")))
    cases = [
        ("UC-01", "Registrasi Member", "Calon member", "Calon member membuka halaman daftar.", "Membuat akun member baru.", [
            "Member mengisi nama, email, nomor HP, dan kata sandi.",
            "Sistem memvalidasi email unik dan kata sandi minimal 8 karakter.",
            "Sistem membuat user, profil member, role Member, dan token login.",
            "Member masuk ke dashboard mobile.",
        ], ["Email sudah terdaftar: sistem menampilkan pesan validasi.", "Data tidak lengkap: sistem meminta member melengkapi field wajib."], "Akun member aktif dan dapat digunakan."),
        ("UC-02", "Login Member", "Member", "Member memasukkan email dan kata sandi.", "Masuk ke mobile app.", [
            "Sistem memvalidasi email dan kata sandi.",
            "Sistem membuat token Sanctum.",
            "Mobile menyimpan token dan mengambil profil member.",
            "Member diarahkan ke dashboard.",
        ], ["Credential salah: sistem menampilkan pesan login gagal.", "Token tidak valid pada request berikutnya: sistem meminta login ulang."], "Member berhasil masuk ke aplikasi."),
        ("UC-03", "Kelola Profil", "Member", "Member membuka menu profil.", "Memperbarui data pribadi.", [
            "Member melihat data profil.",
            "Member mengubah nama, email, nomor HP, atau kata sandi.",
            "Sistem memvalidasi input.",
            "Sistem menyimpan perubahan.",
        ], ["Email dipakai akun lain: perubahan ditolak.", "Kata sandi baru tidak valid: sistem menampilkan pesan validasi."], "Profil member terbarui."),
        ("UC-04", "Lihat Paket Membership", "Member", "Member membuka menu membership.", "Membandingkan pilihan paket.", [
            "Sistem menampilkan paket aktif dari admin.",
            "Member melihat harga, durasi, kuota kunjungan, dan apakah termasuk personal trainer.",
            "Member memilih paket untuk melihat detail.",
        ], ["Tidak ada paket aktif: sistem menampilkan empty state."], "Member memahami paket yang tersedia."),
        ("UC-05", "Beli Membership", "Member", "Member memilih paket.", "Membuat transaksi pembelian membership.", [
            "Member menekan beli paket.",
            "Sistem membuat membership purchase dengan status pending payment.",
            "Mobile membuka halaman pembayaran manual.",
            "Member memilih QRIS atau rekening bank.",
            "Member menekan konfirmasi pembayaran dan dapat upload bukti.",
        ], ["Bukti belum ada: member tetap dapat kirim lewat WhatsApp bila diperlukan.", "Pembayaran ditolak admin: status menjadi rejected dan member dapat mengulang pembayaran."], "Membership menunggu verifikasi admin."),
        ("UC-06", "Booking Kelas dengan Membership", "Member", "Member membuka jadwal kelas.", "Mengamankan slot kelas menggunakan membership aktif.", [
            "Member memilih tanggal.",
            "Sistem menampilkan sesi kelas sesuai jadwal mingguan dan tanggal.",
            "Member memilih kelas dan metode akses membership.",
            "Sistem mengecek membership aktif, sisa kuota, dan kapasitas kelas.",
            "Sistem membuat booking confirmed.",
        ], ["Membership tidak aktif: opsi membership dikunci.", "Kuota kunjungan habis: member diarahkan memakai kunjungan sekali datang.", "Kapasitas penuh: sistem menolak booking."], "Booking kelas tercatat."),
        ("UC-07", "Booking Kelas Sekali Datang", "Member", "Member memilih kelas tanpa memakai membership.", "Membuat booking kelas berbayar sekali datang.", [
            "Member memilih metode akses sekali datang.",
            "Sistem membuat booking pending payment.",
            "Mobile membuka pembayaran manual.",
            "Member mengirim konfirmasi pembayaran.",
            "Admin memverifikasi pembayaran.",
        ], ["Pembayaran belum disetujui: booking belum confirmed.", "Admin menolak bukti: member perlu mengirim ulang pembayaran."], "Booking aktif setelah pembayaran disetujui."),
        ("UC-08", "Batalkan Booking Kelas", "Member", "Member membuka booking saya.", "Membatalkan jadwal yang tidak bisa dihadiri.", [
            "Member memilih booking aktif.",
            "Member menekan batal.",
            "Sistem mengubah status booking menjadi cancelled.",
            "Slot kelas berkurang dari jumlah booking aktif.",
        ], ["Booking sudah selesai: pembatalan tidak tersedia.", "Booking milik member lain: sistem menolak."], "Booking dibatalkan."),
        ("UC-09", "Personal Trainer dengan Membership", "Member", "Member membuka menu Personal Trainer.", "Menjadwalkan sesi PT dari paket yang mencakup PT.", [
            "Sistem mengecek membership member termasuk personal trainer.",
            "Member memilih trainer, tanggal, jam, dan catatan.",
            "Sistem membuat sesi PT scheduled.",
            "Member melihat sesi dalam daftar personal trainer.",
        ], ["Membership tidak termasuk PT: opsi membership dikunci.", "Trainer tidak aktif: trainer tidak muncul di pilihan."], "Sesi PT terjadwal."),
        ("UC-10", "Personal Trainer Sekali Datang", "Member", "Member ingin sesi PT tanpa paket PT.", "Membuat sesi PT berbayar manual.", [
            "Member memilih trainer dan jadwal.",
            "Sistem membuat sesi pending payment.",
            "Mobile membuka pembayaran manual.",
            "Admin menyetujui pembayaran.",
            "Sistem mengubah sesi menjadi scheduled.",
        ], ["Pembayaran ditolak: sesi tidak aktif.", "Jadwal tidak valid: sistem meminta tanggal/jam lain."], "Sesi PT aktif setelah pembayaran disetujui."),
        ("UC-11", "QR Check-In", "Member", "Member datang ke gym.", "Mencatat kunjungan member.", [
            "Member membuka QR member card.",
            "Admin/staf melakukan scan atau check-in.",
            "Sistem mencatat attendance.",
            "Jika membership memiliki batas kunjungan, sistem menambah pemakaian kuota.",
        ], ["Membership tidak aktif: check-in membership ditolak.", "Kuota habis: sistem menolak atau meminta pembayaran sekali datang."], "Kehadiran tercatat."),
        ("UC-12", "Lihat Riwayat Kehadiran", "Member", "Member membuka riwayat absensi.", "Melihat catatan kunjungan.", [
            "Sistem mengambil daftar attendance member.",
            "Member melihat tanggal, tipe aktivitas, kelas/PT bila ada, dan sumber check-in.",
        ], ["Belum ada data: sistem menampilkan empty state."], "Member mengetahui histori kunjungan."),
    ]
    for case in cases:
        story.append(use_case(*case, style))

    story.append(PageBreak())
    story.append(SectionBand("3. Use Case Store dan Pembayaran", colors.HexColor("#6F2188")))
    for case in [
        ("UC-13", "Belanja Produk", "Member", "Member membuka toko.", "Memilih produk Akhwat Gym.", [
            "Sistem menampilkan kategori dan produk dari admin panel.",
            "Member membuka detail produk.",
            "Member menambahkan produk ke cart.",
            "Member mengubah jumlah produk sesuai stok.",
        ], ["Produk stok habis: tombol beli tidak tersedia.", "Produk nonaktif: produk tidak tampil di katalog."], "Cart berisi produk yang dipilih."),
        ("UC-14", "Checkout Produk", "Member", "Member membuka cart.", "Membuat order toko.", [
            "Member mengecek item dan jumlah.",
            "Member mengisi catatan/alamat bila diperlukan.",
            "Sistem membuat order pending payment.",
            "Mobile membuka pembayaran manual.",
        ], ["Stok berubah sebelum checkout: sistem menolak jumlah berlebih.", "Cart kosong: checkout tidak tersedia."], "Order menunggu pembayaran."),
        ("UC-15", "Konfirmasi Pembayaran Manual", "Member", "Member memiliki transaksi pending.", "Mengirim bukti pembayaran kepada admin.", [
            "Member memilih transaksi yang akan dibayar.",
            "Sistem menampilkan rekening aktif dan QRIS aktif.",
            "Member dapat download gambar QRIS.",
            "Member upload bukti pembayaran atau kirim via WhatsApp.",
            "Member menekan tombol sudah/konfirmasi pembayaran.",
        ], ["Metode pembayaran belum tersedia: sistem memberi pesan bahwa admin perlu setup rekening/QRIS.", "File bukti gagal upload: member dapat mengulang atau memakai WhatsApp."], "Payment confirmation tercatat dengan status pending."),
        ("UC-16", "Lihat Riwayat Transaksi", "Member", "Member membuka riwayat.", "Melihat membership, order, dan pembayaran.", [
            "Sistem menampilkan histori membership.",
            "Sistem menampilkan histori order.",
            "Member membuka detail transaksi.",
        ], ["Transaksi belum ada: sistem menampilkan empty state."], "Member dapat memantau transaksi sendiri."),
        ("UC-17", "Notifikasi Member", "Member", "Ada event penting.", "Member mendapat informasi tepat waktu.", [
            "Mobile mengirim FCM token ke backend.",
            "Sistem membuat notifikasi inbox.",
            "Jika Firebase aktif, sistem mengirim push notification.",
            "Member membuka notifikasi dan menuju halaman terkait.",
        ], ["Firebase belum aktif: notifikasi tetap tersimpan di inbox.", "Token perangkat tidak valid: sistem dapat melewati token tersebut."], "Member menerima info booking/pembayaran/membership."),
    ]:
        story.append(use_case(*case, style))

    story.append(PageBreak())
    story.append(SectionBand("4. Use Case Admin Panel", colors.HexColor("#6F2188")))
    for case in [
        ("UC-18", "Login Admin Panel", "Owner, Super admin, Admin di lokasi", "Admin membuka /admin.", "Masuk ke panel sesuai role.", [
            "Admin memasukkan email dan kata sandi.",
            "Sistem memvalidasi role admin.",
            "Admin masuk ke dashboard admin.",
        ], ["User bukan role admin: akses panel ditolak.", "Credential salah: sistem menampilkan error login."], "Admin masuk sesuai hak akses."),
        ("UC-19", "Kelola User dan Role", "Owner, Super admin", "Perlu mengatur akses staf.", "Mengelola akun dan role.", [
            "Owner/Super admin membuka menu users atau roles.",
            "Admin membuat/mengubah user.",
            "Admin memberi role Owner, Super admin, atau Admin di lokasi.",
            "Sistem menyimpan permission sesuai role.",
        ], ["Admin lokasi tidak boleh mengelola user/role.", "Role penting tidak boleh diberikan sembarangan sesuai policy."], "Akses staf terkontrol."),
        ("UC-20", "Kelola Member", "Admin di lokasi, Super admin, Owner", "Ada member baru/perubahan data.", "Menjaga data member akurat.", [
            "Admin membuka data member.",
            "Admin membuat atau mengubah profil member.",
            "Sistem menyimpan data dan relasi user/member.",
        ], ["Email duplikat: perubahan ditolak.", "Data wajib kosong: sistem menampilkan validasi."], "Data member terbarui."),
        ("UC-21", "Kelola Trainer", "Admin di lokasi, Super admin, Owner", "Ada trainer atau perubahan jadwal.", "Mengatur data trainer.", [
            "Admin membuka data trainer.",
            "Admin membuat/mengubah trainer dan status aktif.",
            "Trainer aktif dapat dipilih di kelas dan sesi PT.",
        ], ["Trainer nonaktif tidak muncul untuk booking baru."], "Data trainer siap dipakai jadwal."),
        ("UC-22", "Kelola Paket Membership", "Owner, Super admin", "Bisnis ingin mengubah penawaran.", "Mengatur paket membership.", [
            "Admin membuat paket gym, kelas, PT, bulanan, tahunan, atau kunjungan terbatas.",
            "Admin menentukan harga, durasi, diskon, kuota kunjungan, dan apakah termasuk PT.",
            "Sistem menampilkan paket aktif di mobile.",
        ], ["Paket nonaktif tidak tampil di mobile.", "Paket lama tetap tersimpan untuk histori transaksi."], "Paket membership tersedia untuk dibeli."),
        ("UC-23", "Kelola Jadwal Kelas Mingguan", "Admin di lokasi, Super admin, Owner", "Ada kelas seperti Zumba/Yoga/Circuit pada hari tertentu.", "Membuat jadwal kelas konsisten.", [
            "Admin membuat template kelas dengan hari, jam, trainer, kapasitas, harga visit/member.",
            "Sistem menghasilkan class sessions berdasarkan tanggal.",
            "Mobile menampilkan sesi sesuai tanggal yang dipilih.",
        ], ["Trainer belum dipilih: sistem meminta data lengkap.", "Kapasitas tidak valid: sistem menolak."], "Jadwal kelas muncul di mobile."),
        ("UC-24", "Verifikasi Pembayaran", "Admin di lokasi, Super admin, Owner", "Ada payment confirmation pending.", "Menentukan pembayaran diterima atau ditolak.", [
            "Admin membuka menu konfirmasi pembayaran.",
            "Admin memeriksa nominal, transaksi, catatan, dan bukti pembayaran.",
            "Admin approve atau reject.",
            "Sistem mengaktifkan benefit sesuai tipe transaksi.",
        ], ["Nominal/bukti tidak sesuai: admin reject dengan catatan.", "Transaksi sudah diproses: sistem mencegah approval ganda."], "Status pembayaran final."),
        ("UC-25", "Kelola Rekening dan QRIS", "Owner, Super admin", "Bisnis ingin mengubah metode pembayaran.", "Mengatur metode pembayaran manual.", [
            "Owner membuka menu rekening bank atau QRIS.",
            "Owner menambah/mengubah nomor rekening dan gambar QRIS.",
            "Owner mengaktifkan metode yang boleh dipakai member.",
        ], ["Admin lokasi tidak boleh mengubah rekening/QRIS.", "QRIS tanpa gambar tidak dapat dipakai dengan baik."], "Metode pembayaran tersedia di mobile."),
        ("UC-26", "Kelola Produk dan Kategori", "Admin di lokasi, Super admin, Owner", "Ada produk toko baru.", "Menjaga katalog dan stok.", [
            "Admin membuat kategori produk.",
            "Admin membuat produk dengan harga, stok, deskripsi, dan gambar.",
            "Produk aktif tampil di mobile.",
        ], ["Stok habis: produk tetap dapat tampil tapi tidak bisa dibeli.", "Produk nonaktif disembunyikan."], "Katalog toko siap dijual."),
        ("UC-27", "Kelola Order", "Admin di lokasi, Super admin, Owner", "Ada order masuk.", "Memantau transaksi toko.", [
            "Admin membuka data order.",
            "Admin melihat item dan status pembayaran.",
            "Setelah pembayaran approve, order menjadi paid dan stok berkurang.",
        ], ["Pembayaran ditolak: order tidak mengurangi stok.", "Stok tidak cukup: sistem mencegah order valid."], "Order tercatat dan bisa diproses."),
        ("UC-28", "Kelola Absensi", "Admin di lokasi, Super admin, Owner", "Member datang ke gym atau kelas.", "Mencatat kehadiran.", [
            "Admin melakukan check-in QR/manual.",
            "Sistem mencatat tipe attendance.",
            "Sistem menghubungkan attendance dengan member, kelas, atau sesi PT.",
        ], ["Membership tidak aktif/kuota habis: check-in membership ditolak.", "QR tidak valid: sistem menolak check-in."], "Attendance tersimpan."),
        ("UC-29", "Lihat Dashboard dan Laporan", "Owner, Super admin, Admin di lokasi", "Admin ingin memantau operasional.", "Melihat ringkasan non-rahasia sesuai role.", [
            "Admin membuka dashboard.",
            "Sistem menampilkan informasi operasional yang aman untuk admin/owner.",
            "Admin membuka laporan transaksi, booking, attendance, dan produk dari menu terkait.",
        ], ["Data sensitif tidak ditampilkan di landing page publik.", "Hak akses role membatasi menu tertentu."], "Admin mendapat insight operasional."),
    ]:
        story.append(use_case(*case, style))

    story.append(PageBreak())
    story.append(SectionBand("5. Aturan Bisnis Penting", colors.HexColor("#6F2188")))
    story.append(table(
        [
            ["Area", "Aturan"],
            ["Membership", "Paket dapat bulanan atau tahunan. Paket dapat unlimited visit atau limit visit. Paket dapat termasuk personal trainer atau tidak."],
            ["Kelas", "Kelas unik seperti Zumba, Yoga, Aerobic, Fitdance, Bomiya, Poundfit, Circuit Training dapat berjalan pada satu atau beberapa hari setiap minggu."],
            ["Booking", "Booking membership membutuhkan membership aktif dan kuota kunjungan tersedia. Booking sekali datang membutuhkan pembayaran manual yang disetujui."],
            ["Attendance", "Check-in memakai membership menambah visits_used bila paket memiliki batas kunjungan. Pembatalan booking tidak mengurangi visits_used."],
            ["Pembayaran", "Tidak ada Midtrans pada fase ini. Sistem memakai transfer bank, QRIS, upload bukti, link WhatsApp, dan approval admin."],
            ["Toko", "Stok produk berkurang setelah order dibayar/disetujui, bukan saat checkout pending."],
            ["Role", "Owner dan Super admin memiliki akses penuh. Admin di lokasi tidak mengelola rekening, QRIS, users, dan roles."],
            ["Notifikasi", "Database notification tetap dibuat walau Firebase push belum aktif."],
        ],
        [3.2 * cm, 13.9 * cm],
        style,
    ))

    story.append(p("Status implementasi yang sudah diselaraskan", style["h2"]))
    story.append(bullet([
        "API menggunakan Laravel Sanctum dan endpoint mobile berada di /api/v1.",
        "Mobile app menggunakan domain production https://fitness.dbaik.com/api/v1.",
        "Seeder demo menyediakan user testing dan data paket/jadwal dari gambar Akhwat Gym.",
        "Admin panel memiliki role Owner, Super admin, dan Admin di lokasi.",
        "Dokumentasi OpenAPI berada di docs/openapi.yaml.",
    ], style["body"]))

    doc.build(story, onFirstPage=page_footer, onLaterPages=page_footer)


if __name__ == "__main__":
    build()
