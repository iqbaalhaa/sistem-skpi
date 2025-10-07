@extends('layouts.app')

@section('title', 'Generate SKPI')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Generate SKPI</h3>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
            </div>
            <div class="card-body">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="table-responsive">
                    <table class="table table-striped" id="table1">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Generate SKPI</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Email</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($mahasiswa as $index => $mhs)
                            <tr class="text-center">
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <form method="POST" action="{{ route('prodi.generateskpi.generate', $mhs->user->id) }}" target="_blank">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="bi bi-printer"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>{{ $mhs->nim }}</td>
                                <td>{{ $mhs->nama }}</td>
                                <td>{{ $mhs->user->email }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

