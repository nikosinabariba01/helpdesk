@extends('mainlayout.layout')
@section('navbar')
@include('mainlayout.navbar.nav')
@endsection
@section('pages')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-white" href="javascript:;">Pages</a></li>
        <li class="breadcrumb-item text-sm text-white active" aria-current="page">Ticket List</li>
    </ol>
    <h6 class="font-weight-bolder text-white mb-0">Ticket List</h6>
</nav>
@endsection
@section('upnav')
@include('mainlayout.navbar.upnavtek')
@endsection

@section('container')
<link rel="stylesheet" href="//cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css">

<div class="col-lg-12 mb-lg-0 mb-4">
    <div class="card z-index-2 h-100 d-flex flex-column shadow-lg" style="border: 1px solid #e4e4e4;">
        <div class="card-header pb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
            <h6 class="mb-0">All Ticket List</h6>

            <div class="d-flex" style="min-width: 280px;">
                <div class="input-group input-group-sm">
                    <span class="input-group-text text-body">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </span>
                    <input
                        type="text"
                        id="customSearch"
                        class="form-control"
                        placeholder="Search subject atau user"
                        onfocus="focused(this)"
                        onfocusout="defocused(this)">
                </div>
            </div>
        </div>

        <div class="card-body px-0 pt-0 pb-0">
            <style>
                #TicketTable {
                    border-collapse: collapse !important;
                    width: 100% !important;
                }

                #TicketTable thead th,
                #TicketTable tbody td {
                    text-align: center !important;
                    vertical-align: middle !important;
                }

                #TicketTable thead th span.dt-column-title,
                #TicketTable thead th span.dt-column-order {
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    vertical-align: middle;
                }

                #TicketTable thead th {
                    border-bottom: none !important;
                    border-left: none !important;
                    border-right: none !important;
                    border-top: 1px solid #e9ecef !important;
                    background: #fff !important;
                    white-space: nowrap;
                    position: sticky;
                    top: 0;
                    z-index: 2;
                    padding: 12px 10px !important;
                    text-align: center !important;
                }

                #TicketTable tbody td {
                    border-bottom: 1px solid #e9ecef !important;
                    border-right: 1px solid #e9ecef !important;
                    padding: 12px 10px !important;
                    background: #fff;
                }

                #TicketTable tbody td:first-child {
                    border-left: 1px solid #e9ecef !important;
                }

                #TicketTable tbody tr:hover td {
                    background: #fafafa !important;
                }

                #TicketTable tbody td>div {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    min-height: 44px;
                    text-align: center;
                }

                .ticket-subject-wrap {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 4px;
                    text-align: center;
                    width: 100%;
                }

                .ticket-subject-wrap a {
                    text-align: center;
                    display: inline-block;
                }

                .ticket-subject-wrap .ticket-meta {
                    display: flex;
                    flex-wrap: wrap;
                    justify-content: center;
                    gap: 6px 12px;
                    list-style: none;
                    margin: 0;
                    padding: 0;
                }

                .ticket-subject-wrap .ticket-meta li {
                    margin: 0;
                    padding: 0;
                }

                .ticket-action-wrap {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                }

                .ticket-action-wrap form {
                    margin: 0 !important;
                }

                /* hilangkan icon sorting ganda */
                #TicketTable thead th.sorting::before,
                #TicketTable thead th.sorting::after,
                #TicketTable thead th.sorting_asc::before,
                #TicketTable thead th.sorting_asc::after,
                #TicketTable thead th.sorting_desc::before,
                #TicketTable thead th.sorting_desc::after {
                    display: none !important;
                    content: none !important;
                }

                #TicketTable thead th .sort-icons {
                    display: none !important;
                }

                /* dropdown footer */
                .ticket-footer-dropdown .btn {
                    border: 1px solid #ffffff !important;
                    color: #495057 !important;
                    background-color: #ffffff !important;
                    padding: 6px 12px !important;
                    font-size: 12px !important;
                    border-radius: 8px !important;
                    display: inline-flex;
                    align-items: center;
                    gap: 8px;
                    cursor: pointer;
                    min-width: 0;
                    max-width: 220px;
                    width: auto;
                    justify-content: space-between;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                }

                .ticket-footer-dropdown .btn span {
                    overflow: hidden;
                    text-overflow: ellipsis;
                    white-space: nowrap;
                }

                .ticket-footer-dropdown .btn:hover,
                .ticket-footer-dropdown .btn:focus,
                .ticket-footer-dropdown .btn.show {
                    background-color: #fff !important;
                    color: #344767 !important;
                    border-color: #ffffff !important;
                    box-shadow: 0 0 0 0.1rem rgba(94, 114, 228, 0.08) !important;
                }

                .ticket-footer-dropdown .dropdown-menu {
                    font-size: 13px;
                    min-width: 190px;
                    border-radius: 10px;
                    border: 1px solid #ececec;
                    padding: 6px 0;
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
                }

                .ticket-footer-dropdown .dropdown-item {
                    padding: 8px 12px;
                }

                .ticket-footer-dropdown .dropdown-item:hover {
                    background: #f8f9fa;
                    color: #344767;
                }

                /* paging border putih */
                .ticket-table-footer .dt-paging .dt-paging-button {
                    border-radius: 8px !important;
                    min-width: 34px;
                    height: 34px;
                    margin: 0 2px;
                    border: 1px solid #ffffff !important;
                    background: #fff !important;
                    color: #344767 !important;
                }

                .ticket-table-footer .dt-paging .dt-paging-button.current {
                    background: #5e72e4 !important;
                    border-color: #5e72e4 !important;
                    color: #fff !important;
                    box-shadow: none !important;
                }

                .ticket-table-shell {
                    display: flex;
                    flex-direction: column;
                    min-height: 620px;
                }

                .ticket-table-scroller {
                    flex: 1 1 auto;
                    overflow-y: auto;
                    overflow-x: auto;
                    margin-right: 15px;
                }

                #TicketTable_wrapper {
                    padding: 0;
                }

                #TicketTable_wrapper .dt-layout-row:first-child,
                #TicketTable_wrapper .dt-layout-row:last-child {
                    display: none !important;
                }

                .ticket-table-footer {
                    flex: 0 0 auto;
                    border-top: 1px solid #ececec;
                    background: #fff;
                    padding: 14px 16px;
                }

                .ticket-table-footer-top,
                .ticket-table-footer-bottom {
                    display: flex;
                    align-items: center;
                    justify-content: space-between;
                    gap: 12px;
                    flex-wrap: wrap;
                }

                .ticket-table-footer-top {
                    margin-bottom: 12px;
                }

                .ticket-table-footer-group {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    flex-wrap: wrap;
                }

                .ticket-footer-title {
                    font-size: 11px;
                    text-transform: uppercase;
                    letter-spacing: 0.4px;
                    color: #8392ab;
                    font-weight: 700;
                    margin-bottom: 4px;
                }

                .ticket-table-footer .dt-length {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin: 0;
                }

                .ticket-table-footer .dt-length label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    margin: 0;
                    font-size: 12px;
                    color: #67748e;
                    font-weight: 600;
                }

                .ticket-table-footer .dt-length select {
                    border: 1px solid #d2d6da;
                    border-radius: 8px;
                    padding: 6px 12px;
                    min-width: 90px;
                    font-size: 13px;
                    background-color: #fff;
                    color: #344767;
                }

                .ticket-table-footer .dt-info {
                    margin: 0;
                    font-size: 13px;
                    color: #67748e;
                }

                .ticket-table-footer .dt-paging {
                    margin: 0;
                }

                .ticket-table-footer .dt-paging .dt-paging-button:hover {
                    background: #f8f9fa !important;
                    color: #344767 !important;
                    border-color: #ffffff !important;
                }

                .ticket-table-footer .dt-paging .dt-paging-button.current:hover {
                    background: #5e72e4 !important;
                    color: #fff !important;
                    border-color: #5e72e4 !important;
                }

                .ticket-table-footer .dt-paging .disabled {
                    opacity: 0.5 !important;
                    cursor: not-allowed !important;
                }

                @media (max-width: 768px) {
                    .ticket-table-scroller {
                        min-height: 400px;
                        max-height: 400px;
                    }

                    .ticket-table-footer {
                        padding: 12px;
                    }

                    .ticket-table-footer-top,
                    .ticket-table-footer-bottom {
                        flex-direction: column;
                        align-items: stretch;
                    }

                    .ticket-table-footer-group {
                        width: 100%;
                    }

                    .ticket-footer-dropdown {
                        position: relative;
                        display: inline-block;
                        width: 100%;
                    }

                    .ticket-footer-dropdown .btn {
                        width: 100%;
                    }

                    .ticket-table-footer .dt-paging {
                        overflow-x: auto;
                        width: 100%;
                    }
                }

                @media (min-width: 769px) and (max-width: 1024px) {
                    .ticket-table-scroller {
                        min-height: 600px;
                        max-height: 600px;
                    }
                }

                @media (min-width: 1025px) {
                    .ticket-table-scroller {
                        min-height: 550px;
                        max-height: 550px;
                    }
                }
            </style>

            <div class="ticket-table-shell">
                <div class="table-responsive ticket-table-scroller">
                    <table class="table align-items-center mb-0" id="TicketTable" style="width:100%">
                        <thead>
                            <tr>
                                <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    subject
                                </th>
                                <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    User
                                </th>
                                <th class="sorting text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    Status
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    Deskripsi
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    Aksi Status
                                </th>
                                <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center" style="padding: 10px;">
                                    aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>

                <div class="ticket-table-footer">
                    <div class="ticket-table-footer-top">
                        <div class="ticket-table-footer-group">
                            <div>
                                <div class="ticket-footer-title">Tampilan</div>
                                <div id="ticket-length-slot"></div>
                            </div>
                        </div>

                        <div class="ticket-table-footer-group">
                            <div class="ticket-footer-dropdown dropdown">
                                <div class="ticket-footer-title">Filter</div>
                                <button class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    id="filterJenisPengaduanBtn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span id="filterJenisPengaduanDisplay">Jenis Pengaduan</span>
                                    <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterJenisPengaduanBtn">
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="jenis_pengaduan"
                                            data-filter-value=""
                                            data-filter-label="Jenis Pengaduan">
                                            Semua
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="jenis_pengaduan"
                                            data-filter-value="perbaikan"
                                            data-filter-label="Jenis Pengaduan: Perbaikan">
                                            Perbaikan
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="jenis_pengaduan"
                                            data-filter-value="permintaan"
                                            data-filter-label="Jenis Pengaduan: Permintaan">
                                            Permintaan
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="ticket-footer-dropdown dropdown">
                                <div class="ticket-footer-title">Filter</div>
                                <button class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    id="filterStatusBtn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span id="filterStatusDisplay">Status</span>
                                    <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="filterStatusBtn">
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="status"
                                            data-filter-value=""
                                            data-filter-label="Status">
                                            Semua
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="status"
                                            data-filter-value="open"
                                            data-filter-label="Status: Open">
                                            Open
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="status"
                                            data-filter-value="on process"
                                            data-filter-label="Status: On Process">
                                            On Process
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="status"
                                            data-filter-value="escalated"
                                            data-filter-label="Status: Escalated">
                                            Escalated
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item filter-option" href="#"
                                            data-filter-type="status"
                                            data-filter-value="close"
                                            data-filter-label="Status: Close">
                                            Close
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div class="ticket-footer-dropdown dropdown">
                                <div class="ticket-footer-title">Urutkan</div>
                                <button class="btn btn-sm btn-outline-secondary"
                                    type="button"
                                    id="sortCreatedAtBtn"
                                    data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <span id="sortCreatedAtDisplay">Tanggal: Terbaru</span>
                                    <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="sortCreatedAtBtn">
                                    <li>
                                        <a class="dropdown-item sort-option" href="#"
                                            data-sort-value="desc"
                                            data-sort-label="Tanggal: Terbaru">
                                            Terbaru
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item sort-option" href="#"
                                            data-sort-value="asc"
                                            data-sort-label="Tanggal: Terlama">
                                            Terlama
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="ticket-table-footer-bottom">
                        <div class="ticket-table-footer-group">
                            <div id="ticket-info-slot"></div>
                        </div>

                        <div class="ticket-table-footer-group">
                            <div id="ticket-paging-slot"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-confirmation" tabindex="-1" role="dialog" aria-labelledby="modal-confirmation" aria-hidden="true">
    <div class="modal-dialog modal-danger modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="modal-title-confirmation">Are you sure?</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="py-3 text-center">
                    <i class="ni ni-bell-55 ni-3x"></i>
                    <h4 class="text-gradient text-danger mt-4">Tindakan ini akan menandai tiket sebagai ditutup</h4>
                    <p>Apakah Anda yakin ingin menutup tiket ini ?</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-white" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" id="modal-submit-btn">Ya, close tiket</button>
            </div>
        </div>
    </div>
</div>

<script src="//cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>

<script>
    function escapeHtml(text) {
        if (text === null || text === undefined) return '';
        return String(text)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function debounce(fn, delay) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function renderStatusBadge(status) {
        if (status === 'open') {
            return `<span class="badge badge-sm bg-gradient-success">${escapeHtml(status)}</span>`;
        } else if (status === 'on process') {
            return `<span class="badge badge-sm bg-gradient-warning">${escapeHtml(status)}</span>`;
        } else if (status === 'close') {
            return `<span class="badge badge-sm bg-gradient-danger">${escapeHtml(status)}</span>`;
        } else if (status === 'escalated') {
            return `<span class="badge badge-sm bg-gradient-info">${escapeHtml(status)}</span>`;
        } else {
            return `<span class="badge badge-sm bg-gradient-secondary">Unknown Status</span>`;
        }
    }

    function renderSubjectColumn(row) {
        return `
        <div class="ticket-subject-wrap">
            <h6 class="mb-0 text-s text-limit-35" title="Subject">
                <a href="${row.view_url}">
                    ${escapeHtml(row.subject)}
                </a>
            </h6>

            <ul class="ticket-meta">
                <li class="text-xs text-secondary">
                    <i class="fa fa-circle fa-xs text-danger"></i>${escapeHtml(row.ticket_code)}
                </li>
                <li class="text-xs text-secondary" title="type">
                    <i class="fa fa-circle fa-xs text-primary"></i>${escapeHtml(row.Jenis_Pengaduan)}
                </li>
                <li class="text-xs text-secondary" title="Created Date">
                    <i class="fa fa-circle fa-xs text-secondary"></i> ${escapeHtml(row.created_at_formatted)}
                </li>
            </ul>
        </div>
    `;
    }

    function renderActionStatus(row) {
        let html = '';

        if (row.status === 'on process') {
            if (row.is_not_assigned) {
                html += `
                    <form action="${row.routes.contribute}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-sm btn-outline-secondary btn-transparent text-secondary">
                            Contribute
                        </button>
                    </form>
                `;
            } else if (row.role_is_admin_or_pengurus) {
                html += `
                    <form action="${row.routes.cancel_assign}" method="POST" class="mb-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-warning">
                            Cancel Process
                        </button>
                    </form>
                `;

                if (!row.has_owner_assignee && row.routes.request_followup) {
                    html += `
                        <form action="${row.routes.request_followup}" method="POST" class="mb-2">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <button type="submit" class="btn btn-sm btn-outline-info btn-transparent text-info">
                                Escalate
                            </button>
                        </form>
                    `;
                }

                html += `
                    <form action="${row.routes.close}" method="POST" id="closeTicketForm-${row.id}" class="mb-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-confirmation"
                            data-form-id="closeTicketForm-${row.id}">
                            Close
                        </button>
                    </form>
                `;
            } else if (row.role_is_pemilik) {
                html += `
                    <form action="${row.routes.cancel_assign}" method="POST" class="mb-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-warning">
                            Cancel Process
                        </button>
                    </form>
                `;

                html += `
                    <form action="${row.routes.close}" method="POST" id="closeTicketForm-${row.id}" class="mb-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="button"
                            class="btn btn-sm btn-outline-danger btn-transparent text-danger"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-confirmation"
                            data-form-id="closeTicketForm-${row.id}">
                            Close
                        </button>
                    </form>
                `;
            }
        } else if (row.status === 'escalated') {
            if (row.role_is_admin_or_pengurus) {
                html += `
                    <form action="${row.routes.cancel_escalation}" method="POST">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                            Cancel Escalation
                        </button>
                    </form>
                `;
            } else if (row.role_is_pemilik) {
                html += `
                    <form action="${row.routes.accept_escalation}" method="POST" class="mb-2">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PUT">
                        <button type="submit" class="btn btn-sm btn-outline-success btn-transparent text-success">
                            <i class="fa fa-check pe-2 text-success"></i> Accept Escalation
                        </button>
                    </form>
                `;
            }
        } else if (row.status === 'close') {
            html += `
                <form action="${row.routes.assign}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-sm btn-outline-warning btn-transparent text-secondary">
                        Reprocess
                    </button>
                </form>
            `;
        } else if (row.status === 'open') {
            html += `
                <form action="${row.routes.assign}" method="POST">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="PUT">
                    <button type="submit" class="btn btn-sm btn-outline-primary btn-transparent text-primary">
                        Proceed
                    </button>
                </form>
            `;
        }

        return html;
    }

    function renderAksi(row) {
        return `
            <a class="dropdown-item" href="${row.view_url}">
                <i class="fa fa-eye pe-2 text-dark"></i>
            </a>
        `;
    }

    function moveDataTableControls() {
        const wrapper = document.getElementById('TicketTable_wrapper');
        if (!wrapper) return;

        const length = wrapper.querySelector('.dt-length');
        const info = wrapper.querySelector('.dt-info');
        const paging = wrapper.querySelector('.dt-paging');

        const lengthSlot = document.getElementById('ticket-length-slot');
        const infoSlot = document.getElementById('ticket-info-slot');
        const pagingSlot = document.getElementById('ticket-paging-slot');

        if (length && lengthSlot && !lengthSlot.contains(length)) {
            lengthSlot.innerHTML = '';
            lengthSlot.appendChild(length);
        }

        if (info && infoSlot && !infoSlot.contains(info)) {
            infoSlot.innerHTML = '';
            infoSlot.appendChild(info);
        }

        if (paging && pagingSlot && !pagingSlot.contains(paging)) {
            pagingSlot.innerHTML = '';
            pagingSlot.appendChild(paging);
        }
    }
    let currentFilterStatus = '';
    let currentFilterJenisPengaduan = '';
    let currentSortCreatedAt = 'desc';
    document.addEventListener('DOMContentLoaded', function() {
        const table = new DataTable('#TicketTable', {
            processing: true,
            serverSide: true,
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            searchDelay: 350,
            layout: {
                topStart: 'pageLength',
                topEnd: null,
                bottomStart: 'info',
                bottomEnd: 'paging'
            },
            ajax: {
                url: "{{ route('tickets.datatable', ['mode' => 'all']) }}",
                type: "GET",
                data: function(d) {
                    d.filter_status = currentFilterStatus;
                    d.filter_jenis_pengaduan = currentFilterJenisPengaduan;
                    d.sort_created_at = currentSortCreatedAt;
                }
            },
            columns: [{
                    data: null,
                    orderable: true,
                    searchable: true,
                    render: function(data, type, row) {
                        return renderSubjectColumn(row);
                    }
                },
                {
                    data: 'user_name',
                    orderable: true,
                    searchable: true,
                    render: function(data) {
                        return `<div><span class="text-secondary text-xs font-weight-bold">${escapeHtml(data ?? '-')}</span></div>`;
                    }
                },
                {
                    data: 'status',
                    orderable: true,
                    searchable: false,
                    render: function(data) {
                        return `<div>${renderStatusBadge(data)}</div>`;
                    }
                },
                {
                    data: 'detail_short',
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return `<div><span class="text-secondary text-xs font-weight-bold">${escapeHtml(data ?? '')}</span></div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div class="ticket-action-wrap">${renderActionStatus(row)}</div>`;
                    }
                },
                {
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data, type, row) {
                        return `<div>${renderAksi(row)}</div>`;
                    }
                }
            ],
            order: [],
            language: {
                processing: 'Memuat data...',
                search: 'Cari Subject / User:',
                lengthMenu: 'Tampilkan _MENU_ baris',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                infoEmpty: 'Tidak ada data',
                zeroRecords: 'Data tidak ditemukan',
                emptyTable: 'Belum ada data tiket',
                paginate: {
                    first: 'Awal',
                    last: 'Akhir',
                    next: '›',
                    previous: '‹'
                }
            },
            initComplete: function() {
                moveDataTableControls();
            },
            drawCallback: function() {
                moveDataTableControls();
            }
        });

        const debouncedSearch = debounce(function(value) {
            table.search(value).draw();
        }, 350);

        document.getElementById('customSearch').addEventListener('input', function() {
            debouncedSearch(this.value);
        });

        document.querySelectorAll('.filter-option').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                const filterType = this.dataset.filterType;
                const filterValue = this.dataset.filterValue;
                const filterLabel = this.dataset.filterLabel;

                if (filterType === 'jenis_pengaduan') {
                    currentFilterJenisPengaduan = filterValue;
                    document.getElementById('filterJenisPengaduanDisplay').textContent = filterLabel;
                }

                if (filterType === 'status') {
                    currentFilterStatus = filterValue;
                    document.getElementById('filterStatusDisplay').textContent = filterLabel;
                }

                table.ajax.reload();
            });
        });

        document.querySelectorAll('.sort-option').forEach(function(item) {
            item.addEventListener('click', function(e) {
                e.preventDefault();

                currentSortCreatedAt = this.dataset.sortValue;
                document.getElementById('sortCreatedAtDisplay').textContent = this.dataset.sortLabel;

                table.ajax.reload();
            });
        });
    });
</script>

<script>
    $('#modal-submit-btn').on('click', function() {
        var formId = $('#modal-confirmation').data('form-id');
        $('#' + formId).submit();
    });

    $('#modal-confirmation').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        var formId = button.data('form-id');
        $(this).data('form-id', formId);
    });
</script>
@endsection