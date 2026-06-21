@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav')
@endsection

@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Manage User</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Manage</h6>
</nav>
@endsection

@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<div class="card mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
            <h6 class="mb-0">Manage User</h6>
            <div class="d-flex gap-2">
                <!-- Kolom Pencarian dengan input-group -->
                <div class="input-group input-group-sm">
                    <span class="input-group-text text-body"><i class="fas fa-search" aria-hidden="true"></i></span>
                    <input type="text" id="search" class="form-control" placeholder="Search"
                        onfocus="focused(this)" onfocusout="defocused(this)">
                </div>
            </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2 h-500">
            @if($users->isEmpty())
            <div class="table-responsive margin-right: 15px; position: relative;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <a href="{{ route('user.create') }}" class="btn btn-primary position-absolute top-50 start-50 translate-middle">Create New User</a>
            </div>
            @else
            <div class="table-responsive margin-right: 15px;" style="height: 400px; max-height: 400px; overflow-y: auto;">
                <table class="table align-items-center mb-0" id="UserTable">
                    <thead>
                        <tr>
                            <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Nama</th>
                            <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Email</th>
                            <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Role</th>
                            <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr class="align-middle text-sm border border-light" 
                            data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}"
                            data-role="{{ $user->role }}"
                            data-created-at="{{ $user->created_at->timestamp ?? 0 }}">
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->name }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->email }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <span class="text-secondary text-xs font-weight-bold">{{ $user->role }}</span>
                            </td>
                            <td class="align-middle text-center text-sm" style="padding: 10px;">
                                <div class="dropdown">
                                    <a class="btn text-primary dropdown-toggle" href="#" role="button" id="dropdownMenuLink{{ $user->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class=""></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink{{ $user->id }}">
                                        <li>
                                            <a class="dropdown-item text-info" href="{{ route('admin.EditUser', $user->id) }}">
                                                <i class="fa fa-edit pe-2 text-info"></i>Edit
                                            </a>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('user.destroy', $user->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Are you sure you want to deaktivate this user?')">
                                                    <i class="fa fa-trash text-danger pe-2"></i>Deaktivasi
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination and Sorting Controls -->
            <div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; background-color: #ffffff;"
                class="d-flex flex-column flex-md-row justify-content-between align-items-center flex-wrap">

                <!-- Left side: Filters / Dropdowns -->
                <div style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
                    <!-- Create User Button -->
                    <a href="{{ route('user.create') }}" class="btn btn-primary btn-sm">Create User</a>
                    
                    <!-- Sort Dropdown -->
                    <div class="dropdown" style="position: relative;">
                        <button class="btn btn-sm btn-outline-secondary"
                            style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="paginationDisplay">1-10 dari {{ $users->count() }}</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc"><i
                                        class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru</a>
                            </li>
                            <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc"><i
                                        class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama</a>
                            </li>
                        </ul>
                    </div>

                    <!-- Filter Role Dropdown -->
                    <div class="dropdown" style="position: relative; display: inline-block;">
                        <button class="btn btn-sm btn-outline-secondary"
                            style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;"
                            type="button" id="filterRoleBtn" data-bs-toggle="dropdown" aria-expanded="false">
                            <span id="filterRoleDisplay">Role</span>
                            <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="filterRoleBtn"
                            style="font-size: 13px; min-width: 150px;">
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="role" data-filter-value="" style="padding: 8px 16px;">Semua</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="role" data-filter-value="admin" style="padding: 8px 16px;">Admin</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="role" data-filter-value="teknisi" style="padding: 8px 16px;">Teknisi</a></li>
                            <li><a class="dropdown-item filter-option" href="#" data-filter-type="role" data-filter-value="user" style="padding: 8px 16px;">User</a></li>
                        </ul>
                    </div>
                </div>

                <!-- Right side: Pagination -->
                <div style="display: flex; gap: 6px; flex-wrap: wrap;">
                    <button id="prevPage" class="btn btn-sm btn-outline-secondary"
                        style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;"
                        title="Halaman Sebelumnya">
                        <i class="fa fa-chevron-left" style="font-size: 11px;"></i>
                    </button>
                    <button id="nextPage" class="btn btn-sm btn-outline-secondary"
                        style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;"
                        title="Halaman Berikutnya">
                        <i class="fa fa-chevron-right" style="font-size: 11px;"></i>
                    </button>
                </div>
            </div>

            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ========================
        // 0. Inisialisasi Variabel
        // ========================
        const rowsPerPage = 10;
        let usersData = [];
        let currentPage = 1;
        let totalPages = 1;
        let currentSort = {
            column: 'createdAt',
            order: 'desc'
        };
        let currentFilters = {
            role: ''
        };
        let currentSearch = '';

        // Ambil semua row ke array
        $('#UserTable tbody tr').each(function() {
            const $tr = $(this);
            usersData.push({
                trElement: $tr,
                name: $tr.data('name').toString().toLowerCase(),
                email: $tr.data('email').toString().toLowerCase(),
                role: $tr.data('role').toString().toLowerCase(),
                createdAt: parseInt($tr.data('created-at'))
            });
        });

        // ========================
        // 1. Filter
        // ========================
        function applyFilters(data) {
            return data.filter(item => {
                const matchRole = currentFilters.role ? item.role === currentFilters.role : true;
                return matchRole;
            });
        }

        // ========================
        // 2. Search
        // ========================
        function applySearch(data) {
            if (!currentSearch) return data;
            const keyword = currentSearch.toLowerCase();
            return data.filter(item =>
                item.name.includes(keyword) || item.email.includes(keyword) || item.role.includes(keyword)
            );
        }

        // ========================
        // 3. Sort
        // ========================
        function applySort(data) {
            const sorted = [...data];
            const { column, order } = currentSort;
            sorted.sort((a, b) => {
                let valA = a[column];
                let valB = b[column];

                // String (name, email, role) -> alfabetis
                if (typeof valA === 'string') {
                    valA = valA.toLowerCase();
                    valB = valB.toLowerCase();
                    if (valA < valB) return order === 'asc' ? -1 : 1;
                    if (valA > valB) return order === 'asc' ? 1 : -1;
                    return order === 'asc' ? a.createdAt - b.createdAt : b.createdAt - a.createdAt;
                }

                // Number (createdAt)
                if (valA < valB) return order === 'asc' ? -1 : 1;
                if (valA > valB) return order === 'asc' ? 1 : -1;
                return 0;
            });
            return sorted;
        }

        // ========================
        // 4. Render Table & Pagination
        // ========================
        function renderTable() {
            let filteredData = applyFilters(usersData);
            filteredData = applySearch(filteredData);
            filteredData = applySort(filteredData);

            totalPages = Math.ceil(filteredData.length / rowsPerPage);
            if (currentPage > totalPages) currentPage = totalPages || 1;

            // Hide all rows first
            $('#UserTable tbody tr').hide();

            // Hitung start & end index
            const startIndex = (currentPage - 1) * rowsPerPage;
            const endIndex = startIndex + rowsPerPage;

            // Ambil subset data untuk halaman ini
            const pageData = filteredData.slice(startIndex, endIndex);
            const $tbody = $('#UserTable tbody');
            const rowsToShow = pageData.map(item => item.trElement.detach());
            $tbody.append(rowsToShow);
            rowsToShow.forEach(r => r.show());

            // Update pagination display
            const totalRows = filteredData.length;

            if (totalRows === 0) {
                $('#paginationDisplay').text('0-0 dari 0');
            } else {
                const displayStart = startIndex + 1;
                const displayEnd = Math.min(endIndex, totalRows);
                $('#paginationDisplay').text(`${displayStart}-${displayEnd} dari ${totalRows}`);
            }

            // Enable/disable Prev/Next
            $('#prevPage').prop('disabled', currentPage <= 1).css('opacity', currentPage <= 1 ? 0.5 : 1).css(
                'cursor', currentPage <= 1 ? 'not-allowed' : 'pointer');
            $('#nextPage').prop('disabled', currentPage >= totalPages).css('opacity', currentPage >=
                totalPages ? 0.5 : 1).css('cursor', currentPage >= totalPages ? 'not-allowed' : 'pointer');

            // Update sorting icons
            $('#UserTable thead th.sorting').removeClass('sorting_asc sorting_desc');
            $('#UserTable thead th.sorting .sort-icons').remove();

            $('#UserTable thead th.sorting').each(function() {
                const colText = $(this).text().trim().toLowerCase();
                if (currentSort.column === colText) {
                    $(this).addClass(currentSort.order === 'asc' ? 'sorting_asc' : 'sorting_desc');
                }
                if (!$(this).find('.sort-icons').length) {
                    $(this).append('<span class="sort-icons"></span>');
                }
            });
        }

        // ========================
        // 5. Event Handlers
        // ========================

        // Search
        $('#search').on('input', function() {
            currentSearch = $(this).val().toLowerCase();
            currentPage = 1;
            renderTable();
        });

        // Filter dropdown
        $('.filter-option').click(function(e) {
            e.preventDefault();

            const filterType = $(this).data('filter-type');
            const filterValue = $(this).data('filter-value') || '';

            currentFilters[filterType] = filterValue.toLowerCase();

            // Update teks dropdown
            if (filterType === 'role') {
                const displayText = filterValue ? `Role: ${$(this).text()}` : 'Role';
                $('#filterRoleDisplay').text(displayText);
            }

            currentPage = 1;
            renderTable();
        });

        // Dropdown sort terbaru/terlama
        $('.page-sort-option').click(function(e) {
            e.preventDefault();
            const sortOrder = $(this).data('sort');
            currentSort.column = 'createdAt';
            currentSort.order = sortOrder;
            currentPage = 1;
            renderTable();
        });

        // Sorting klik th
        $('#UserTable thead th.sorting').click(function() {
            const colText = $(this).text().trim().toLowerCase();
            if (colText === 'nama') currentSort.column = 'name';
            else if (colText === 'email') currentSort.column = 'email';
            else if (colText === 'role') currentSort.column = 'role';
            else return;

            currentSort.order = (currentSort.order === 'asc') ? 'desc' : 'asc';
            currentPage = 1;
            renderTable();
        });

        // Pagination Prev/Next
        $('#prevPage').click(function() {
            if (currentPage > 1) currentPage--;
            renderTable();
        });
        $('#nextPage').click(function() {
            if (currentPage < totalPages) currentPage++;
            renderTable();
        });

        // Initial render
        renderTable();
    });
</script>

<style>
    /* Tetapkan space untuk ikon supaya kolom tidak bergeser */
    th.sorting {
        position: relative;
        cursor: pointer;
        user-select: none;
        padding-right: 30px;
        width: 150px;
    }

    /* container ikon */
    th.sorting .sort-icons {
        position: absolute;
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        display: flex;
        flex-direction: column;
        font-size: 1em;
        line-height: 0.7em;
        width: 16px;
        height: 16px;
    }

    /* default abu-abu */
    th.sorting .sort-icons::before,
    th.sorting .sort-icons::after {
        color: #ccc;
    }

    /* ascending active */
    th.sorting.sorting_asc .sort-icons::before {
        color: #000;
    }

    th.sorting.sorting_asc .sort-icons::after {
        color: #ccc;
    }

    /* descending active */
    th.sorting.sorting_desc .sort-icons::before {
        color: #ccc;
    }

    th.sorting.sorting_desc .sort-icons::after {
        color: #000;
    }

    /* isi segitiga */
    th.sorting .sort-icons::before {
        content: "▲";
    }

    th.sorting .sort-icons::after {
        content: "▼";
    }
</style>

@endsection