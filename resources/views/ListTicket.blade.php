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

                #TicketTable thead th {
                    border-top: 1px solid #e9ecef !important;
                    border-bottom: none !important;
                    border-left: none !important;
                    border-right: none !important;
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

                #TicketTable thead th span.dt-column-order {
                    transform: scale(1.50) !important;
                    font-weight: 700;
                    color: #5e72e4 !important;
                    opacity: 1! important;
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

                @media (max-width: 768px) {
                    .ticket-table-scroller {
                        min-height: 400px;
                        max-height: 400px;
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

                /* =========================
       CUSTOM SORT ICON
       ========================= */

                #TicketTable thead th.sorting,
                #TicketTable thead th.sorting_asc,
                #TicketTable thead th.sorting_desc {
                    position: relative;
                    cursor: pointer;
                    user-select: none;
                    white-space: nowrap;
                    color: #6c757d;
                }

                #TicketTable thead th.sorting .sort-icons,
                #TicketTable thead th.sorting_asc .sort-icons,
                #TicketTable thead th.sorting_desc .sort-icons {
                    display: inline-block;
                    position: relative;
                    width: 15px;
                    height: 18px;
                    margin-left: 7px;
                    vertical-align: middle;
                    top: -1px;
                }

                #TicketTable thead th.sorting .sort-icons::before,
                #TicketTable thead th.sorting .sort-icons::after,
                #TicketTable thead th.sorting_asc .sort-icons::before,
                #TicketTable thead th.sorting_asc .sort-icons::after,
                #TicketTable thead th.sorting_desc .sort-icons::before,
                #TicketTable thead th.sorting_desc .sort-icons::after {
                    content: '';
                    position: absolute;
                    left: 50%;
                    transform: translateX(-50%);
                    border-left: 4px solid transparent;
                    border-right: 4px solid transparent;
                    transition: opacity 0.2s ease;
                }

                /* panah atas */
                #TicketTable thead th.sorting .sort-icons::before,
                #TicketTable thead th.sorting_asc .sort-icons::before,
                #TicketTable thead th.sorting_desc .sort-icons::before {
                    top: 1px;
                    border-bottom: 6px solid #6c757d;
                }

                /* panah bawah */
                #TicketTable thead th.sorting .sort-icons::after,
                #TicketTable thead th.sorting_asc .sort-icons::after,
                #TicketTable thead th.sorting_desc .sort-icons::after {
                    bottom: 1px;
                    border-top: 6px solid #6c757d;
                }

                /* default */
                #TicketTable thead th.sorting .sort-icons::before,
                #TicketTable thead th.sorting .sort-icons::after {
                    opacity: 0.65;
                }

                /* hover */
                #TicketTable thead th.sorting:hover .sort-icons::before,
                #TicketTable thead th.sorting:hover .sort-icons::after {
                    opacity: 0.9;
                }

                /* ascending aktif */
                #TicketTable thead th.sorting_asc {
                    color: #495057;
                }

                #TicketTable thead th.sorting_asc .sort-icons::before {
                    opacity: 1;
                }

                #TicketTable thead th.sorting_asc .sort-icons::after {
                    opacity: 0.22;
                }

                /* descending aktif */
                #TicketTable thead th.sorting_desc {
                    color: #495057;
                }

                #TicketTable thead th.sorting_desc .sort-icons::before {
                    opacity: 0.22;
                }

                #TicketTable thead th.sorting_desc .sort-icons::after {
                    opacity: 1;
                }

                /* hilangkan icon bawaan plugin jika ada */
                #TicketTable thead th.sorting::before,
                #TicketTable thead th.sorting::after,
                #TicketTable thead th.sorting_asc::before,
                #TicketTable thead th.sorting_asc::after,
                #TicketTable thead th.sorting_desc::before,
                #TicketTable thead th.sorting_desc::after {
                    display: none !important;
                    content: none !important;
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

                .ticket-table-footer label {
                    margin-bottom: 0;
                    font-size: 12px;
                    color: #67748e;
                    font-weight: 600;
                }

                .ticket-table-footer .form-select,
                .ticket-table-footer .dt-length select {
                    border: 1px solid #ffffff !important;
                    border-radius: 8px;
                    padding: 6px 12px;
                    font-size: 13px;
                    background-color: #fff;
                    color: #344767;
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.10) !important;
                }

                .ticket-table-footer .form-select:focus,
                .ticket-table-footer .dt-length select:focus {
                    border-color: #ffffff !important;
                    box-shadow: 0 0 0 0.1rem rgba(94, 114, 228, 0.08) !important;
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

                .ticket-table-footer .dt-info {
                    margin: 0;
                    font-size: 13px;
                    color: #67748e;
                }

                .ticket-table-footer .dt-paging {
                    margin: 0;
                }

                .ticket-table-footer .dt-paging .dt-paging-button {
                    border-radius: 8px !important;
                    min-width: 34px;
                    height: 34px;
                    margin: 0 2px;
                    border: 1px solid #d2d6da !important;
                    background: #ffffff !important;
                    color: #344767 !important;
                }

                .ticket-table-footer .dt-paging .dt-paging-button.current {
                    background: #5e72e4 !important;
                    border-color: #5e72e4 !important;
                    color: #fff !important;
                    box-shadow: none !important;
                }

                .ticket-table-footer .dt-paging .dt-paging-button:hover {
                    background: #f8f9fa !important;
                    color: #344767 !important;
                    border-color: #cfd4da !important;
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

                .ticket-table-footer-divider {
                    width: 1px;
                    align-self: stretch;
                    background: #ececec;
                }

                @media (max-width: 768px) {
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

                    .ticket-table-footer .form-select,
                    .ticket-table-footer .dt-length select {
                        width: 100%;
                        min-width: 100%;
                    }

                    .ticket-table-footer-divider {
                        display: none;
                    }

                    .ticket-table-footer .dt-paging {
                        overflow-x: auto;
                        width: 100%;
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
                                <div id="ticket-length-slot"></div>
                            </div>
                        </div>

                        <div class="ticket-table-footer-group">
                            <div>
                                <select id="filterJenisPengaduan" class="form-select form-select-sm">
                                    <option value="">Jenis Pengaduan</option>
                                    <option value="perbaikan">Jenis Pengaduan: Perbaikan</option>
                                    <option value="permintaan">Jenis Pengaduan: Permintaan</option>
                                </select>
                            </div>

                            <div>
                                <select id="filterStatus" class="form-select form-select-sm">
                                    <option value="">Status</option>
                                    <option value="open">Status: Open</option>
                                    <option value="on process">Status: On Process</option>
                                    <option value="escalated">Status: Escalated</option>
                                    <option value="close">Status: Close</option>
                                </select>
                            </div>

                            <div>
                                <select id="sortCreatedAt" class="form-select form-select-sm">
                                    <option value="desc">Terbaru</option>
                                    <option value="asc">Terlama</option>
                                </select>
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
                    d.filter_status = document.getElementById('filterStatus').value;
                    d.filter_jenis_pengaduan = document.getElementById('filterJenisPengaduan').value;
                    d.sort_created_at = document.getElementById('sortCreatedAt').value;
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
                    first: '‹‹',
                    last: '››',
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

        document.getElementById('filterStatus').addEventListener('change', function() {
            table.ajax.reload();
        });

        document.getElementById('filterJenisPengaduan').addEventListener('change', function() {
            table.ajax.reload();
        });

        document.getElementById('sortCreatedAt').addEventListener('change', function() {
            table.ajax.reload();
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