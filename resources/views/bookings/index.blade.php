@extends('layouts.app')
@section('content')
    <h3>Data Booking</h3>

    <button class="btn btn-primary mb-3" id="btnAdd">+ Booking</button>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Lapangan</th>
                <th>Tanggal</th>
                <th>Jam Mulai</th>
                <th>Jam Selesai</th>
                <th>Total</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody id="bookingTable">
            @foreach ($bookings as $b)
                <tr id="row-{{ $b->id }}">
                    <td class="text-center">{{ $loop->iteration }}</td>
                    <td class="customer">{{ $b->customer_name }}</td>
                    <td class="field" data-id="{{ $b->field_id }}">{{ $b->field->name }}</td>
                    <td class="date">{{ $b->booking_date }}</td>
                    <td class="start">{{ $b->start_time }}</td>
                    <td class="end">{{ $b->end_time }}</td>
                    <td class="total" data-raw="{{ (int) $b->total_price }}">
                        {{ number_format((int) $b->total_price, 0, ',', '.') }}</td>
                    <td>
                        <button class="btn btn-warning btnEdit" data-id="{{ $b->id }}">Edit</button>
                        <button class="btn btn-danger btnDelete" data-id="{{ $b->id }}">Hapus</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- MODAL -->
    <div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="bookingModalLabel">Booking</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">

                    <input type="hidden" id="booking_id">

                    <div class="mb-2">
                        <label class="form-label">Customer</label>
                        <input type="text" id="customer" class="form-control">
                        <div id="err_customer" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Lapangan</label>
                        <select id="field" class="form-control">
                            <option value="">-- pilih --</option>
                            @foreach ($fields as $f)
                                <option value="{{ $f->id }}">{{ $f->name }}</option>
                            @endforeach
                        </select>
                        <div id="err_field" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Tanggal</label>
                        <input type="date" id="date" class="form-control">
                        <div id="err_date" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Jam Mulai</label>
                        <input type="time" id="start" class="form-control">
                        <div id="err_start" class="text-danger small mt-1"></div>
                    </div>

                    <div class="mb-2">
                        <label class="form-label">Jam Selesai</label>
                        <input type="time" id="end" class="form-control">
                        <div id="err_end" class="text-danger small mt-1"></div>
                    </div>

                    <div id="err_time" class="text-danger small"></div>

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

            let modalEl = document.getElementById('bookingModal');
            let modal = new bootstrap.Modal(modalEl);

            modalEl.addEventListener('hidden.bs.modal', function() {
                clearError();
            });

            // Format angka: bulatkan lalu beri separator ribuan (titik)
            function formatRibuan(angka) {
                return Math.round(angka).toLocaleString('id-ID');
            }

            function clearError() {
                $('#err_customer, #err_field, #err_date, #err_start, #err_end, #err_time').text('');
            }

            function buildRow(res) {
                let no = $('#bookingTable tr').length + 1;
                return `
                    <tr id="row-${res.id}">
                        <td class="text-center rownum">${no}</td>
                        <td class="customer">${res.customer_name}</td>
                        <td class="field" data-id="${res.field.id}">${res.field.name}</td>
                        <td class="date">${res.booking_date}</td>
                        <td class="start">${res.start_time}</td>
                        <td class="end">${res.end_time}</td>
                        <td class="total" data-raw="${Math.round(res.total_price)}">${formatRibuan(res.total_price)}</td>
                        <td>
                            <button class="btn btn-warning btnEdit" data-id="${res.id}">Edit</button>
                            <button class="btn btn-danger btnDelete" data-id="${res.id}">Hapus</button>
                        </td>
                    </tr>`;
            }

            // TAMBAH
            $('#btnAdd').click(function() {
                clearError();
                $('#booking_id,#customer,#date,#start,#end').val('');
                $('#field').val('');
                $('#bookingModalLabel').text('Tambah Booking');
                modal.show();
            });

            // EDIT
            $(document).on('click', '.btnEdit', function() {
                clearError();

                let id = $(this).data('id');
                let row = $('#row-' + id);

                $('#booking_id').val(id);
                $('#customer').val(row.find('.customer').text().trim());
                $('#field').val(row.find('.field').data('id'));
                $('#date').val(row.find('.date').text().trim());
                $('#start').val(row.find('.start').text().trim());
                $('#end').val(row.find('.end').text().trim());
                $('#bookingModalLabel').text('Edit Booking');

                modal.show();
            });

            // SIMPAN
            $('#btnSave').click(function() {
                clearError();

                let id = $('#booking_id').val();
                let $btn = $(this);
                $btn.prop('disabled', true).text('Menyimpan...');

                $.ajax({
                    url: id ? '/bookings/' + id : '/bookings',
                    type: id ? 'PUT' : 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        customer_name: $('#customer').val(),
                        field_id: $('#field').val(),
                        booking_date: $('#date').val(),
                        start_time: $('#start').val(),
                        end_time: $('#end').val()
                    },
                    success: function(res) {
                        let html = buildRow(res);

                        if (id) {
                            $('#row-' + id).replaceWith(html);
                        } else {
                            $('#bookingTable').append(html);
                        }

                        modal.hide();
                    },
                    error: function(xhr) {
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            let err = xhr.responseJSON.errors;
                            if (err.customer_name) $('#err_customer').text(err.customer_name[
                            0]);
                            if (err.field_id) $('#err_field').text(err.field_id[0]);
                            if (err.booking_date) $('#err_date').text(err.booking_date[0]);
                            if (err.start_time) $('#err_start').text(err.start_time[0]);
                            if (err.end_time) $('#err_end').text(err.end_time[0]);
                            if (err.time) $('#err_time').text(err.time[0]);
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

                if (!confirm('Yakin ingin menghapus booking ini?')) return;

                $.ajax({
                    url: '/bookings/' + id,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function() {
                        $('#row-' + id).remove();
                        // Update ulang nomor urut
                        $('#bookingTable tr').each(function(i) {
                            $(this).find('.rownum').text(i + 1);
                        });
                    },
                    error: function(xhr) {
                        alert('Gagal menghapus. (HTTP ' + xhr.status + ')');
                    }
                });
            });

        });
    </script>
@endpush
