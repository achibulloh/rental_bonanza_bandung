@extends('dashboard.layouts.index')
@section('title', 'My Profile')
@section('style')
<style>
    /* ... (CSS STYLE SAMA SEPERTI SEBELUMNYA, TIDAK PERLU DIUBAH) ... */
    :root {
        --primary: #FFC400;
        --primary-hover: #e0ac00;
        --dark-bg: #111111;
        --text-dark: #333333;
        --text-gray: #888888;
        --light-gray: #f4f6f9;
        --white: #ffffff;
        --border-radius: 12px;
        --sidebar-width: 280px;
        --success-bg: #e6fcf5; --success-text: #0ca678;
        --danger-bg: #ffe3e3; --danger-text: #c92a2a;
        --warning-bg: #fff9db; --warning-text: #e67700;
    }
    .main-content { flex: 1; margin-left: var(--sidebar-width); padding: 30px 40px; transition: 0.3s; background-color: var(--light-gray); min-height: 100vh; font-family: 'Poppins', sans-serif; }
    .mobile-header { display: none; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .hamburger { font-size: 1.5rem; cursor: pointer; color: var(--text-dark); }
    .page-title { font-size: 1.8rem; margin-bottom: 25px; color: var(--text-dark); font-weight: 700; }
    .profile-grid-top { display: grid; grid-template-columns: 2fr 1fr; gap: 25px; margin-bottom: 25px; }
    .card { background: var(--white); border-radius: 12px; padding: 25px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); border: 1px solid #eef1f6; }
    .card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 20px; color: var(--text-dark); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 0.85rem; color: var(--text-gray); margin-bottom: 8px; font-weight: 500; }
    .form-control { width: 100%; padding: 12px 15px; border: 1px solid #e0e0e0; border-radius: 8px; font-size: 0.95rem; color: var(--text-dark); outline: none; transition: 0.3s; }
    .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 3px rgba(255, 196, 0, 0.1); }
    textarea.form-control { resize: vertical; min-height: 100px; }
    .btn-save { background: var(--primary); color: #000; padding: 12px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; font-size: 0.9rem; transition: 0.3s; display: inline-block; }
    .btn-save:hover { background: var(--primary-hover); }
    .photo-card { display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; height: 100%; }
    .profile-pic-large { width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--primary); padding: 3px; margin-bottom: 15px; display: flex; align-items: center; justify-content: center; overflow: hidden; background: #fff; }
    .profile-pic-large img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .img-placeholder { width: 100%; height: 100%; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #999; font-size: 30px;}
    .profile-name-large { font-size: 1.2rem; font-weight: 600; margin-bottom: 5px; }
    .profile-email-large { font-size: 0.9rem; color: var(--text-gray); margin-bottom: 20px; }
    .btn-outline { background: transparent; border: 1px solid var(--primary); color: var(--text-dark); padding: 8px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; width: auto; transition: 0.3s; display: inline-flex; align-items: center; justify-content: center; gap: 5px; text-decoration: none; font-size: 0.9rem; }
    .btn-outline:hover { background: #fffcf0; }
    .btn-reupload { background-color: var(--primary); color: #000; border: none; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; cursor: pointer; margin-left: 10px; display: inline-flex; align-items: center; gap: 5px; text-decoration: none; transition: 0.2s; }
    .btn-reupload:hover { background-color: var(--primary-hover); transform: translateY(-1px); }
    .verification-list { list-style: none; padding: 0; margin: 0; }
    .verify-item { display: block; padding: 15px 0; border-bottom: 1px solid #f0f0f0; }
    .verify-item:last-child { border-bottom: none; }
    .verify-info h4 { font-size: 0.95rem; margin-bottom: 3px; margin-top: 0; font-weight: 600; }
    .verify-info p { font-size: 0.8rem; color: var(--text-gray); margin: 0; }
    .badge-verified { background: var(--success-bg); color: var(--success-text); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .badge-pending { background: var(--warning-bg); color: var(--warning-text); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .badge-rejected { background: var(--danger-bg); color: var(--danger-text); padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 5px; }
    .alert { padding: 15px; margin-bottom: 20px; border-radius: 8px; font-size: 0.9rem; }
    .alert-success { background-color: var(--success-bg); color: var(--success-text); border: 1px solid var(--success-text); }
    .alert-danger { background-color: var(--danger-bg); color: var(--danger-text); border: 1px solid var(--danger-text); }
    .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(4px); }
    .modal-content { background-color: #fff; margin: 5% auto; padding: 30px; border-radius: 16px; width: 90%; max-width: 500px; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.2); animation: slideDown 0.3s ease-out; }
    @keyframes slideDown { from { transform: translateY(-50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .close-modal { position: absolute; top: 20px; right: 25px; font-size: 28px; font-weight: bold; color: #ccc; cursor: pointer; }
    .close-modal:hover { color: #333; }
    #avatarInput { display: none; }
    .d-flex-between { display: flex; justify-content: space-between; align-items: center; }
    @media (max-width: 992px) { .main-content { margin-left: 0; padding: 20px; } .mobile-header { display: flex; } .profile-grid-top { grid-template-columns: 1fr; } .form-row { grid-template-columns: 1fr; gap: 0; } }
</style>
@endsection

@section('content')

    <h1 class="page-title">Profil Saya</h1>

    @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="alert alert-danger"><ul>@foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach</ul></div> @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="profile-grid-top">
            {{-- DATA DIRI --}}
            <div class="card">
                <h3 class="card-title">Informasi Pribadi</h3>

                {{-- Nama & Email --}}
                <div class="form-row">
                    <div class="form-group"><label>Nama Lengkap</label><input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"></div>
                    <div class="form-group"><label>Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}"></div>
                </div>

                {{-- Telepon & Tanggal Lahir --}}
                <div class="form-row">
                    <div class="form-group"><label>Nomor Telepon</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" placeholder="08..."></div>
                    <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="birth_date" class="form-control" value="{{ old('birth_date', $user->birth_date) }}"></div>
                </div>

                {{-- Gender (NEW) --}}
                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="gender" class="form-control">
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>

                {{-- Alamat --}}
                <div class="form-group"><label>Alamat</label><textarea name="address" class="form-control">{{ old('address', $user->address) }}</textarea></div>

                <button type="submit" class="btn-save">Simpan Perubahan</button>
            </div>

            {{-- FOTO PROFIL --}}
            <div class="card photo-card">
                <div class="profile-pic-large">
                    @if($user->avatar) <img src="{{ asset('storage/' . $user->avatar) }}" id="avatarPreview">
                    @else <div class="img-placeholder" id="avatarPlaceholder"><i class="fas fa-user"></i></div> @endif
                    <img src="" id="jsAvatarPreview" style="display:none; width:100%; height:100%; object-fit:cover; border-radius:50%;">
                </div>
                <h3 class="profile-name-large">{{ $user->name }}</h3>
                <p class="profile-email-large">{{ $user->email }}</p>
                <label for="avatarInput" class="btn-outline" style="cursor: pointer;">Ubah Foto</label>
                <input type="file" name="avatar" id="avatarInput" accept="image/*" onchange="previewImage(this)">
            </div>
        </div>
    </form>

    {{-- BAGIAN DOKUMEN VERIFIKASI --}}
    <div class="card" style="margin-bottom: 25px;">
        <div class="d-flex-between" style="margin-bottom: 20px;">
            <h3 class="card-title" style="margin-bottom: 0;">Dokumen Verifikasi</h3>
            <button onclick="openModal()" class="btn-outline" style="width: auto; padding: 8px 15px; font-size: 0.85rem;">
                <i class="fas fa-plus"></i> Upload Dokumen
            </button>
        </div>

        <ul class="verification-list">
            @forelse($documents as $doc)
                <li class="verify-item">
                    <div class="d-flex-between">
                        <div class="verify-info">
                            <h4>
                                @if($doc->document_type == 'SELFIE_KTP_SIM') Foto Selfie (dengan KTP/SIM)
                                @else {{ $doc->document_type }} @endif
                            </h4>
                            <p>
                                @if($doc->document_number) No: {{ $doc->document_number }} | @endif
                                Diupload: {{ $doc->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            @if($doc->status == 'verified')
                                <span class="badge-verified"><i class="fas fa-check-circle"></i> Terverifikasi</span>
                            @elseif($doc->status == 'rejected')
                                <span class="badge-rejected"><i class="fas fa-times-circle"></i> Ditolak</span>
                            @else
                                <span class="badge-pending"><i class="fas fa-clock"></i> Menunggu</span>
                            @endif
                        </div>
                    </div>

                    {{-- ALASAN PENOLAKAN --}}
                    @if($doc->status == 'rejected')
                        <div style="background-color: #fff5f5; border-left: 4px solid #c92a2a; padding: 15px; margin-top: 15px; border-radius: 4px;">
                            <p style="color: #c92a2a; font-size: 0.9rem; margin-bottom: 8px; font-weight: 600;">
                                <i class="fas fa-exclamation-triangle"></i> Dokumen ini ditolak!
                            </p>
                            <p style="font-size: 0.85rem; color: #555; margin-bottom: 12px; line-height: 1.5;">
                                <strong>Alasan Penolakan:</strong> {{ $doc->rejection_reason ?? 'Tidak ada alasan spesifik.' }}
                            </p>
                            <button type="button" class="btn-reupload" style="margin-left: 0;" onclick="reuploadDoc('{{ $doc->document_type }}', '{{ $doc->document_number }}')">
                                <i class="fas fa-sync-alt"></i> Perbaiki Dokumen Ini
                            </button>
                        </div>
                    @endif

                    @if($doc->status == 'verified' && $doc->verifier)
                        <div style="margin-top: 5px;">
                            <p style="font-size: 0.75rem; color: #28a745;"><i class="fas fa-user-check"></i> Diverifikasi oleh: {{ $doc->verifier->name }}</p>
                        </div>
                    @endif
                </li>
            @empty
                <li class="verify-item" style="justify-content: center; text-align: center;">
                    <p style="color: var(--text-gray); margin-top: 10px;">Belum ada dokumen yang diupload.</p>
                </li>
            @endforelse
        </ul>
    </div>

    {{-- BAGIAN PASSWORD --}}
    <div class="card">
        <h3 class="card-title">Ubah Password</h3>
        <form action="{{ route('profile.password') }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group"><label>Password Lama</label><input type="password" name="current_password" class="form-control" required></div>
            <div class="form-row">
                <div class="form-group"><label>Password Baru</label><input type="password" name="new_password" class="form-control" required></div>
                <div class="form-group"><label>Konfirmasi Password</label><input type="password" name="new_password_confirmation" class="form-control" required></div>
            </div>
            <button type="submit" class="btn-save">Update Password</button>
        </form>
    </div>

    {{-- MODAL UPLOAD --}}
    <div id="uploadModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeModal()">&times;</span>
            <h3 class="card-title" id="modalTitle">Upload Dokumen Baru</h3>
            <form action="{{ route('profile.upload_document') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label>Jenis Dokumen</label>
                    <select name="document_type" id="docTypeSelect" class="form-control" required onchange="checkDocType(this)">
                        <option value="">Pilih Jenis...</option>
                        <option value="KTP">KTP (Kartu Tanda Penduduk)</option>
                        <option value="SIM">SIM (Surat Izin Mengemudi)</option>
                        <option value="NPWP">NPWP</option>
                        <option value="KTM">KTM (Kartu Tanda Mahasiswa)</option>
                        <option value="KTA">KTA (Kartu Tanda Anggota)</option>
                        <option value="SELFIE_KTP_SIM">Foto Selfie menggunakan SIM/KTP</option>
                    </select>
                </div>
                <div class="form-group" id="docNumberGroup">
                    <label>Nomor Dokumen (NIK / No SIM / dll)</label>
                    <input type="text" name="document_number" id="docNumberInput" class="form-control" placeholder="Contoh: 3174xxxx">
                </div>
                <div class="form-group">
                    <label>File Foto</label>
                    <input type="file" name="document_file" class="form-control" accept="image/*" required>
                    <small style="color: #888;">Format: JPG, PNG. Max: 2MB.</small>
                </div>
                <button type="submit" class="btn-save" style="width: 100%;">Upload / Kirim Ulang</button>
            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPlaceholder').style.display = 'none';
                if(document.getElementById('avatarPreview')) document.getElementById('avatarPreview').style.display = 'none';
                var preview = document.getElementById('jsAvatarPreview');
                preview.src = e.target.result;
                preview.style.display = 'block';
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    var modal = document.getElementById("uploadModal");
    var docTypeSelect = document.getElementById("docTypeSelect");
    var docNumberInput = document.getElementById("docNumberInput");
    var modalTitle = document.getElementById("modalTitle");

    function openModal() {
        modal.style.display = "block";
        modalTitle.innerText = "Upload Dokumen Baru";
        docTypeSelect.value = "";
        docTypeSelect.disabled = false;
        docNumberInput.value = "";
        checkDocType(docTypeSelect);
    }

    function reuploadDoc(type, number) {
        modal.style.display = "block";
        modalTitle.innerText = "Perbaiki Dokumen " + type;
        docTypeSelect.value = type;
        docNumberInput.value = number;
        checkDocType(docTypeSelect);
    }

    function closeModal() { modal.style.display = "none"; }
    window.onclick = function(event) { if (event.target == modal) modal.style.display = "none"; }

    function checkDocType(select) {
        var docNum = document.getElementById('docNumberGroup');
        if(select.value === 'SELFIE_KTP_SIM') {
            docNum.style.display = 'none';
        } else {
            docNum.style.display = 'block';
        }
    }
</script>
@endsection
