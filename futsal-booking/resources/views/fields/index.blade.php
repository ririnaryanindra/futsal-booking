@extends('layouts.app')

@section('content')
    <h3>Data Lapangan</h3>

    <button class="btn btn-primary mb-3" id="btnAdd">+ Tambah</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Harga per Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="fieldTable">
            @foreach ($fields as $f)
                <tr id="row-{{ $f->id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="name">{{ $f->name }}</td>
                    <td class="price" data-raw="{{ $f->price_per_hour }}">
                        {{ number_format($f->price_per_hour, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btnEdit" data-id="{{ $f->id }}">Edit</button>
                        <button class="btn btn-danger btnDelete" data-id="{{ $f->id }}">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MODAL -->
    <div class="modal fade" id="fieldModal" tabindex="-1" aria-labelledby="fieldModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fieldModalLabel">Lapangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="field_id">

                    <div class="mb-2">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" id="name" class="form-control">
                        <div id="err_name" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-2">
                        <label for="price" class="form-label">Harga per Jam</label>
                        <input type="number" id="price" class="form-control" min="0">
                        <div id="err_price" class="text-danger small mt-1"></div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" id="btnSave">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function() {

            // Inisialisasi modal Bootstrap 5
            let modalEl = document.getElementById('fieldModal');
            let modal = new bootstrap.Modal(modalEl);

            // Bersihkan error dan backdrop saat modal ditutup
            modalEl.addEventListener('hidden.bs.modal', function() {
                clearError();
            });

            function clearError() {
                $('#err_name').text('');
                $('#err_price').text('');
            }

            // Format angka dengan separator ribuan (titik)
            function formatRibuan(angka) {
                return parseInt(angka).toLocaleString('id-ID');
            }

            // Hapus separator agar dapat dikirim sebagai angka murni
            function stripRibuan(str) {
                return str.replace(/\./g, '').trim();
            }

            function buildRow(f) {
                return `
                <tr id="row-${f.id}">
                    <td class="name">${f.name}</td>
                    <td class="price" data-raw="${f.price_per_hour}">${formatRibuan(f.price_per_hour)}</td>
                    <td>
                        <button class="btn btn-warning btnEdit" data-id="${f.id}">Edit</button>
                        <button class="btn btn-danger btnDelete" data-id="${f.id}">Hapus</button>
                    </td>
                </tr>`;
            }

            // TAMBAH
            $('#btnAdd').click(function() {
                $('#field_id').val('');
                $('#name').val('');
                $('#price').val('');
                $('#fieldModalLabel').text('Tambah Lapangan');
                modal.show();
            });

            // EDIT
            $(document).on('click', '.btnEdit', function() {
                let id = $(this).data('id');
                let row = $('#row-' + id);

                $('#field_id').val(id);
                $('#name').val(row.find('.name').text().trim());
                $('#price').val(row.find('.price').data('raw')); // gunakan nilai mentah
                $('#fieldModalLabel').text('Edit Lapangan');

                modal.show();
            });

            // SIMPAN (tambah & edit)
            $('#btnSave').click(function() {
                clearError();

                let id = $('#field_id').val();
                let name = $('#name').val().trim();
                let price = $('#price').val().trim();

                // Validasi sisi client
                let valid = true;
                if (!name) {
                    $('#err_name').text('Nama tidak boleh kosong.');
                    valid = false;
                }
                if (!price || price < 0) {
                    $('#err_price').text('Harga harus diisi dan tidak boleh negatif.');
                    valid = false;
                }
                if (!valid) return;

                let $btn = $(this);
                $btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: id ? '/fields/' + id : '/fields',
                    type: id ? 'PUT' : 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        price_per_hour: price
                    },
                    success: function(res) {
                        let html = buildRow(res);

                        if (id) {
                            $('#row-' + id).replaceWith(html);
                        } else {
                            $('#fieldTable').append(html);
                        }

                        modal.hide();
                    },
                    error: function(xhr) {
                        // Tangani validasi dari Laravel (422)
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let err = xhr.responseJSON.errors;
                            if (err.name) $('#err_name').text(err.name[0]);
                            if (err.price_per_hour) $('#err_price').text(err.price_per_hour[0]);
                        } else {
                            alert('Terjadi kesalahan. Silakan coba lagi. (HTTP ' + xhr.status +
                                ')');
                        }
                    },
                    complete: function() {
                        $btn.prop('disabled', false).text('Simpan');
                    }
                });
            });

            // HAPUS
            $(document).on('click', '.btnDelete', function() {
                let id = $(this).data('id');

                if (!confirm('Yakin ingin menghapus lapangan ini?')) return;

                $.ajax({
                    url: '/fields/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#row-' + id).remove();
                    },
                    error: function(xhr) {
                        alert('Gagal menghapus. (HTTP ' + xhr.status + ')');
                    }
                });
            });

        });
    </script>
@endpush
