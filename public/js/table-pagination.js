/**
 * Table Pagination and Sorting Helper
 * Reusable function untuk semua table dengan id yang sesuai
 * 
 * Usage:
 * initTablePagination('TicketTable', 10);
 */

function initTablePagination(tableId = 'DataTable', itemsPerPage = 10) {
  const table = $(`#${tableId}`);
  if (table.length === 0) return;

  // Initialize DataTable
  if (!$.fn.DataTable.isDataTable(`#${tableId}`)) {
    table.DataTable({
      searching: true,
      ordering: false,
      paging: false,
      lengthChange: false,
      info: false,
      columnDefs: [{
        targets: [table.find('tbody tr').find('td').length - 1, table.find('tbody tr').find('td').length - 2],
        orderable: false
      }]
    });
  }

  const dataTable = table.DataTable();
  let currentSort = 'desc'; // Default: Terbaru
  let currentPage = 1;

  // Hide default DataTable elements
  $(`#${tableId}_filter`).hide();
  $(`#${tableId}_length`).hide();
  $(`#${tableId}_paginate`).hide();

  // Initial pagination
  updatePaginationDisplay();

  // Custom search
  const searchInput = $(`#search`);
  if (searchInput.length > 0) {
    searchInput.on('keyup', function() {
      const searchTerm = this.value.toLowerCase();
      $.fn.dataTable.ext.search = [];
      $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
        return data.some(cell => cell.toLowerCase().includes(searchTerm));
      });
      dataTable.draw();
      currentPage = 1;
      updatePaginationDisplay();
    });
  }

  // Sort option clicks
  $(document).on('click', '.page-sort-option', function(e) {
    e.preventDefault();
    currentSort = $(this).data('sort');
    sortTableByDate(currentSort);
    currentPage = 1;
    updatePaginationDisplay();
  });

  // Pagination buttons
  $(document).on('click', '.prevPage[data-table="' + tableId + '"]', function() {
    if (currentPage > 1) {
      currentPage--;
      updatePaginationDisplay();
    }
  });

  $(document).on('click', '.nextPage[data-table="' + tableId + '"]', function() {
    const allRows = table.find('tbody tr');
    const totalRows = allRows.length;
    const totalPages = Math.ceil(totalRows / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      updatePaginationDisplay();
    }
  });

  // Function to sort table by date
  function sortTableByDate(direction) {
    const rows = table.find('tbody tr').get();
    
    rows.sort(function(a, b) {
      // Try to extract date from first column
      const aText = $(a).find('td:first').text();
      const bText = $(b).find('td:first').text();
      
      const aDate = extractDateFromText(aText);
      const bDate = extractDateFromText(bText);
      
      return direction === 'desc' ? new Date(bDate) - new Date(aDate) : new Date(aDate) - new Date(bDate);
    });

    $.each(rows, function(index, row) {
      table.find('tbody').append(row);
    });
  }

  // Extract date from ticket format (sp-xxxddmmyy)
  function extractDateFromText(text) {
    const match = text.match(/sp-(\d{3})(\d{6})/);
    if (match) {
      const dateStr = match[2];
      const day = dateStr.substring(0, 2);
      const month = dateStr.substring(2, 4);
      const year = '20' + dateStr.substring(4, 6);
      return new Date(year, parseInt(month) - 1, day);
    }
    return new Date(0);
  }

  // Update pagination display
  function updatePaginationDisplay() {
    const allRows = table.find('tbody tr');
    const totalRows = allRows.length;
    const totalPages = Math.ceil(totalRows / itemsPerPage);

    if (currentPage > totalPages) {
      currentPage = totalPages || 1;
    }

    // Hide all rows
    allRows.hide();

    // Show current page rows
    const startIndex = (currentPage - 1) * itemsPerPage;
    const endIndex = startIndex + itemsPerPage;
    allRows.slice(startIndex, endIndex).show();

    // Update pagination display for all elements with matching data-table
    const displayElements = $(`.paginationDisplay[data-table="${tableId}"]`);
    displayElements.each(function() {
      let displayStart, displayEnd;
      
      if (currentSort === 'desc') {
        displayStart = totalRows === 0 ? 0 : startIndex + 1;
        displayEnd = Math.min(endIndex, totalRows);
      } else {
        displayStart = totalRows - startIndex;
        displayEnd = totalRows - endIndex + 1;
        if (displayEnd < 1) displayEnd = 1;
      }
      
      $(this).text(displayStart + '-' + displayEnd + ' dari ' + totalRows);
    });

    // Update page input
    $(`.pageInput[data-table="${tableId}"]`).val(totalPages === 0 ? '0/0' : currentPage + '/' + totalPages);

    // Update button states
    const prevBtn = $(`.prevPage[data-table="${tableId}"]`);
    const nextBtn = $(`.nextPage[data-table="${tableId}"]`);
    
    prevBtn.prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
    nextBtn.prop('disabled', currentPage === totalPages || totalPages === 0).css('opacity', currentPage === totalPages || totalPages === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalPages === 0 ? 'not-allowed' : 'pointer');
  }
}
