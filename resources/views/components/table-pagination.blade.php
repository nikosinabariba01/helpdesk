<!-- Table Pagination and Sorting Controls Component -->
<!-- 
  Usage: @include('components.table-pagination', [
    'tableId' => 'TicketTable',
    'totalRecords' => 100,
    'sortable' => true
  ])
-->

<div style="padding: 15px 16px; border-top: 1px solid #e4e4e4; display: flex; justify-content: space-between; align-items: center; background-color: #ffffff;">
  <div style="display: flex; gap: 12px; align-items: center;">
    <!-- Pagination Info as Dropdown -->
    <div class="dropdown" style="position: relative;">
      <button class="btn btn-sm btn-outline-secondary pagination-toggle" 
              style="border-color: #ffffff; color: #495057; background-color: white; padding: 6px 12px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; gap: 8px; cursor: pointer;" 
              data-bs-toggle="dropdown" 
              aria-expanded="false"
              data-table="{{ $tableId ?? 'DataTable' }}">
        <span class="paginationDisplay" data-table="{{ $tableId ?? 'DataTable' }}">1-10 dari {{ $totalRecords ?? 0 }}</span>
        <i class="fa fa-chevron-down" style="font-size: 11px;"></i>
      </button>
      <ul class="dropdown-menu" style="font-size: 13px; min-width: 150px;">
        <li><a class="dropdown-item page-sort-option" href="#" data-sort="desc" data-table="{{ $tableId ?? 'DataTable' }}" style="padding: 8px 16px;">
          <i class="fa fa-arrow-down me-2" style="color: #6c757d;"></i>Terbaru
        </a></li>
        <li><a class="dropdown-item page-sort-option" href="#" data-sort="asc" data-table="{{ $tableId ?? 'DataTable' }}" style="padding: 8px 16px;">
          <i class="fa fa-arrow-up me-2" style="color: #6c757d;"></i>Terlama
        </a></li>
      </ul>
    </div>
  </div>
  
  <div style="display: flex; gap: 12px; align-items: center;">
    <!-- Pagination Navigation -->
    <div style="display: flex; gap: 6px;">
      <button class="prevPage btn btn-sm btn-outline-secondary" 
              data-table="{{ $tableId ?? 'DataTable' }}"
              style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" 
              title="Halaman Sebelumnya">
        <i class="fa fa-chevron-left" style="font-size: 11px;"></i>
      </button>
      <input type="text" class="pageInput" data-table="{{ $tableId ?? 'DataTable' }}" readonly style="width: 50px; text-align: center; border: 1px solid #dee2e6; padding: 6px 8px; font-size: 12px; border-radius: 4px; background-color: white; color: #495057;" value="1">
      <button class="nextPage btn btn-sm btn-outline-secondary" 
              data-table="{{ $tableId ?? 'DataTable' }}"
              style="border-color: #dee2e6; color: #495057; background-color: white; padding: 6px 10px; font-size: 12px; border-radius: 4px; display: flex; align-items: center; justify-content: center; width: 32px; cursor: pointer;" 
              title="Halaman Berikutnya">
        <i class="fa fa-chevron-right" style="font-size: 11px;"></i>
      </button>
    </div>
  </div>
</div>
