// Customer Dashboard Scripts
$(document).ready(function() {
  let currentSort = 'desc'; // Default: Terbaru
  let currentPage = 1;
  const itemsPerPage = 10;
  
  var table = $('#TicketTable').DataTable({
    searching: true,
    ordering: false,
    paging: false,
    lengthChange: false,
    info: false,
    columnDefs: [{
      targets: [2, 3],
      orderable: false
    }]
  });

  // Menyembunyikan elemen pencarian bawaan
  $('#TicketTable_filter').hide();
  $('#TicketTable_length').hide();
  $('#TicketTable_paginate').hide();

  // Initial pagination
  updatePagination();

  // Custom search untuk kolom Subject dan Status
  $('#search').on('keyup', function() {
    var searchTerm = this.value.toLowerCase();

    $.fn.dataTable.ext.search = [];
    $.fn.dataTable.ext.search.push(
      function(settings, data, dataIndex) {
        // Kolom subject (kolom 0) dan status (kolom 1)
        var subject = data[0].toLowerCase(); // subject
        var status = data[1].toLowerCase(); // status
        var description = data[2].toLowerCase(); // description

        return subject.includes(searchTerm) || status.includes(searchTerm) || description.includes(searchTerm);
      }
    );

    table.draw();
    currentPage = 1;
    updatePagination();
  });

  // Handle sorting option clicks
  $(document).on('click', '.sort-option', function(e) {
    e.preventDefault();
    currentSort = $(this).data('sort');
    
    // Update label
    const sortLabel = currentSort === 'desc' ? 'Terbaru' : 'Terlama';
    $('#sortLabel').text(sortLabel);
    
    // Sort rows
    sortTableByDate(currentSort);
    currentPage = 1;
    updatePagination();
  });

  // Handle pagination sort option clicks (from paginationInfo dropdown)
  $(document).on('click', '.page-sort-option', function(e) {
    e.preventDefault();
    currentSort = $(this).data('sort');
    
    // Update dropdown display
    const sortLabel = currentSort === 'desc' ? 'Terbaru' : 'Terlama';
    
    // Sort rows
    sortTableByDate(currentSort);
    currentPage = 1;
    updatePagination();
  });

  // Function to sort table by date
  function sortTableByDate(direction) {
    var rows = $('#TicketTable tbody tr').get();
    
    rows.sort(function(a, b) {
      // Ambil teks dari kolom pertama (yang berisi nomor tiket dengan tanggal)
      var aText = $(a).find('li:first').text(); // sp-123012401
      var bText = $(b).find('li:first').text(); // sp-456012402
      
      // Ekstrak tanggal dari format sp-xxxddmmyy
      var aDate = extractDateFromTicket(aText);
      var bDate = extractDateFromTicket(bText);
      
      if (direction === 'desc') {
        return new Date(bDate) - new Date(aDate);
      } else {
        return new Date(aDate) - new Date(bDate);
      }
    });

    $.each(rows, function(index, row) {
      $('#TicketTable tbody').append(row);
    });
  }

  // Function to extract date from ticket format
  function extractDateFromTicket(ticketText) {
    // Format: sp-xxxddmmyy
    // Ambil 6 karakter terakhir: ddmmyy
    var match = ticketText.match(/sp-(\d{3})(\d{6})/);
    if (match) {
      var dateStr = match[2]; // ddmmyy
      var day = dateStr.substring(0, 2);
      var month = dateStr.substring(2, 4);
      var year = '20' + dateStr.substring(4, 6);
      return new Date(year, parseInt(month) - 1, day);
    }
    return new Date(0);
  }

  // Function to update pagination display
  function updatePagination() {
    var allRows = $('#TicketTable tbody tr');
    var totalRows = allRows.length;
    const totalPages = Math.ceil(totalRows / itemsPerPage);

    // Batasi halaman
    if (currentPage > totalPages) {
      currentPage = totalPages || 1;
    }

    // Hide semua rows
    allRows.hide();

    // Show rows untuk halaman saat ini
    var startIndex = (currentPage - 1) * itemsPerPage;
    var endIndex = startIndex + itemsPerPage;
    allRows.slice(startIndex, endIndex).show();

    // Update pagination display berdasarkan sorting
    var displayStart, displayEnd;
    
    if (currentSort === 'desc') {
      // Terbaru: tampil normal (1-10, 11-20, dst)
      displayStart = totalRows === 0 ? 0 : startIndex + 1;
      displayEnd = Math.min(endIndex, totalRows);
    } else {
      // Terlama: tampil terbalik (100-90, 90-80, dst)
      displayStart = totalRows - startIndex;
      displayEnd = totalRows - endIndex + 1;
      
      // Pastikan displayEnd tidak kurang dari 1
      if (displayEnd < 1) {
        displayEnd = 1;
      }
    }
    
    $('#paginationDisplay').text(displayStart + '-' + displayEnd + ' dari ' + totalRows);

    // Update page input
    $('#pageInput').val(totalPages === 0 ? '0/0' : currentPage + '/' + totalPages);

    // Update button states
    $('#prevPage').prop('disabled', currentPage === 1).css('opacity', currentPage === 1 ? '0.5' : '1').css('cursor', currentPage === 1 ? 'not-allowed' : 'pointer');
    $('#nextPage').prop('disabled', currentPage === totalPages || totalPages === 0).css('opacity', currentPage === totalPages || totalPages === 0 ? '0.5' : '1').css('cursor', currentPage === totalPages || totalPages === 0 ? 'not-allowed' : 'pointer');
  }

  // Handle pagination buttons
  $('#prevPage').on('click', function() {
    if (currentPage > 1) {
      currentPage--;
      updatePagination();
    }
  });

  $('#nextPage').on('click', function() {
    var totalRows = $('#TicketTable tbody tr').length;
    const totalPages = Math.ceil(totalRows / itemsPerPage);
    if (currentPage < totalPages) {
      currentPage++;
      updatePagination();
    }
  });
});

// Modal Handlers
document.addEventListener('DOMContentLoaded', function() {
  // Edit Ticket Modal
  const editModal = document.getElementById('exampleModalMessage');
  if (editModal) {
    editModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const ticketId = button.getAttribute('data-ticket-id');
      const ticketSubject = button.getAttribute('data-ticket-subject');
      const ticketJenis = button.getAttribute('data-ticket-jenis');
      const ticketLokasi = button.getAttribute('data-ticket-lokasi');
      const ticketDetail = button.getAttribute('data-ticket-detail');

      const form = editModal.querySelector('#editTicketForm');
      form.action = `/tickets/${ticketId}`;
      form.querySelector('#ticketId').value = ticketId;
      form.querySelector('#subject').value = ticketSubject;
      form.querySelector('#Jenis_Pengaduan').value = ticketJenis;
      form.querySelector('#Lokasi').value = ticketLokasi;
      form.querySelector('#Detail').value = ticketDetail;
    });
  }

  // Announcement Modal
  const announcementModal = document.getElementById('announcementModal');
  if (announcementModal) {
    announcementModal.addEventListener('show.bs.modal', function(event) {
      const button = event.relatedTarget;
      const judul = button.getAttribute('data-pengumuman-judul');
      const deskripsi = button.getAttribute('data-pengumuman-deskripsi');
      const creatorName = button.getAttribute('data-creator-name');
      const creatorRole = button.getAttribute('data-creator-role');
      const creatorPhoto = button.getAttribute('data-creator-photo');
      const createdAt = button.getAttribute('data-created-at');

      document.getElementById('modalJudul').textContent = judul;
      document.getElementById('modalDeskripsi').textContent = deskripsi;
      document.getElementById('modalCreatorName').textContent = creatorName;
      document.getElementById('modalCreatorRole').textContent = creatorRole;
      document.getElementById('modalCreatorPhoto').src = creatorPhoto;
      document.getElementById('modalCreatedAt').textContent = new Date(createdAt).toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
      document.getElementById('modalTimeAgo').textContent = moment(createdAt).fromNow();
    });
  }
});
