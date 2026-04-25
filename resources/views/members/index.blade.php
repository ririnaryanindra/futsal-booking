@extends('layouts.app')

@section('content')
<h3>Data Member</h3>

<button class="btn btn-primary mb-3" id="btnAdd">+ Tambah</button>

<table class="table table-bordered">
    <thead>
        <tr>
            <th>Nama</th>
            <th>No HP</th>
            <th>Email</th>
            <th>Tgl Lahir</th>
            <th>Status</th>
            <th width="180">Aksi</th>
        </tr>
    </thead>

    <tbody id="memberTable">
        @foreach ($members as $m)
        <tr id="row-{{ $m->id }}">
            <td class="name">{{ $m->name }}</td>
            <td class="phone">{{ $m->phone }}</td>
            <td class="email">{{ $m->email }}</td>
            <td class="birth_date">{{ $m->birth_date }}</td>
            <td class="is_active" data-raw="{{ $m->is_active }}">
                @if($m->is_active)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Nonaktif</span>
                @endif
            </td>
            <td>
                <button class="btn btn-warning btn-sm btnEdit" data-id="{{ $m->id }}">Edit</button>
                <button class="btn btn-danger btn-sm btnDelete" data-id="{{ $m->id }}">Hapus</button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>


<!-- MODAL -->
<div class="modal fade" id="memberModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="memberModalLabel">Member</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <input type="hidden" id="member_id">

                <div class="mb-2">
                    <label>Nama</label>
                    <input type="text" id="name" class="form-control">
                </div>

                <div class="mb-2">
                    <label>No HP</label>
                    <input type="text" id="phone" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Email</label>
                    <input type="email" id="email" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Alamat</label>
                    <textarea id="address" class="form-control"></textarea>
                </div>

                <div class="mb-2">
                    <label>Tanggal Lahir</label>
                    <input type="date" id="birth_date" class="form-control">
                </div>

                <div class="mb-2">
                    <label>Status</label>
                    <select id="is_active" class="form-control">
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSave">Simpan</button>
            </div>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function(){

    let modal = new bootstrap.Modal(document.getElementById('memberModal'));

    function statusBadge(val){
        if(val == 1){
            return '<span class="badge bg-success">Aktif</span>';
        }else{
            return '<span class="badge bg-danger">Nonaktif</span>';
        }
    }

    function buildRow(m){

        let phone = m.phone ? m.phone : '';
        let email = m.email ? m.email : '';
        let birth = m.birth_date ? m.birth_date : '';

        return `
        <tr id="row-${m.id}">
            <td class="name">${m.name}</td>
            <td class="phone">${phone}</td>
            <td class="email">${email}</td>
            <td class="birth_date">${birth}</td>
            <td class="is_active" data-raw="${m.is_active}">
                ${statusBadge(m.is_active)}
            </td>
            <td>
                <button class="btn btn-warning btn-sm btnEdit" data-id="${m.id}">Edit</button>
                <button class="btn btn-danger btn-sm btnDelete" data-id="${m.id}">Hapus</button>
            </td>
        </tr>`;
    }

    // TAMBAH
    $('#btnAdd').click(function(){

        $('#member_id').val('');
        $('#name').val('');
        $('#phone').val('');
        $('#email').val('');
        $('#address').val('');
        $('#birth_date').val('');
        $('#is_active').val('1');

        $('#memberModalLabel').text('Tambah Member');

        modal.show();
    });

    // EDIT
    $(document).on('click','.btnEdit',function(){

        let id = $(this).data('id');

        $.get('/members/' + id + '/edit', function(res){

            $('#member_id').val(res.id);
            $('#name').val(res.name);
            $('#phone').val(res.phone);
            $('#email').val(res.email);
            $('#address').val(res.address);
            $('#birth_date').val(res.birth_date);
            $('#is_active').val(res.is_active);

            $('#memberModalLabel').text('Edit Member');

            modal.show();
        });

    });

    // SIMPAN
    $('#btnSave').click(function(){

        let id = $('#member_id').val();

        $.ajax({
            url : id ? '/members/' + id : '/members',
            type: 'POST',
            data:{
                _token : '{{ csrf_token() }}',
                _method: id ? 'PUT' : 'POST',

                name       : $('#name').val(),
                phone      : $('#phone').val(),
                email      : $('#email').val(),
                address    : $('#address').val(),
                birth_date : $('#birth_date').val(),
                is_active  : $('#is_active').val()
            },

            success:function(res){

                let row = buildRow(res);

                if(id){
                    $('#row-' + id).replaceWith(row);
                }else{
                    $('#memberTable').prepend(row);
                }

                modal.hide();
            },

            error:function(xhr){

                console.log(xhr.responseText);

                if(xhr.status == 422){
                    alert('Validasi gagal');
                }else{
                    alert('Terjadi error');
                }
            }
        });

    });

    // HAPUS
    $(document).on('click','.btnDelete',function(){

        let id = $(this).data('id');

        if(!confirm('Yakin hapus data ini?')) return;

        $.ajax({
            url:'/members/' + id,
            type:'POST',
            data:{
                _token:'{{ csrf_token() }}',
                _method:'DELETE'
            },

            success:function(){
                $('#row-' + id).remove();
            }
        });

    });

});
</script>
@endpush