// Admin Jadwal Pelajaran JavaScript
(function () {
    "use strict";

    // Load terms data for all modals
    function loadTermsData() {
        fetch('/admin/terms/data', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            credentials: 'same-origin'
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data && data.length > 0) {
                    // Use all terms (not just active ones) for Kelas X filter
                    
                    // Load for Kelas X semester filter (show all terms)
                    const kelasXSemesterFilter = document.getElementById('kelasXSemesterFilter');
                    if (kelasXSemesterFilter) {
                        kelasXSemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                        data.forEach(term => {
                            const option = document.createElement('option');
                            option.value = term.id;
                            option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                            kelasXSemesterFilter.appendChild(option);
                        });
                        
                        // Set default to first active term, or first term if no active
                        const activeTerm = data.find(term => term.is_active === true);
                        if (activeTerm) {
                            kelasXSemesterFilter.value = activeTerm.id;
                            // Initialize table with active term
                            reloadKelasXTable(activeTerm.id);
                        } else if (data.length > 0) {
                            kelasXSemesterFilter.value = data[0].id;
                            // Initialize table with first term
                            reloadKelasXTable(data[0].id);
                        }

                        // Add event listener for semester filter change
                        kelasXSemesterFilter.addEventListener('change', function() {
                            const selectedTermId = this.value;
                            reloadKelasXTable(selectedTermId);
                        });
                    }

                    // Load classes for Kelas X filter
                    loadClassesForKelasX();

                            // Load for Kelas XI semester filter (show all terms)
                            const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                            if (kelasXISemesterFilter) {
                                kelasXISemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXISemesterFilter.appendChild(option);
                                });
                                
                                // Check if term_id is already in URL (from filter or previous selection)
                                const urlParams = new URLSearchParams(window.location.search);
                                const urlTermId = urlParams.get("term_id");
                                
                                // Set default to first active term, or first term if no active
                                const activeTermXI = data.find(term => term.is_active === true);
                                
                                // Use term from URL if exists and valid, otherwise use active or first
                                let selectedTermId = null;
                                if (urlTermId && data.find(t => t.id == urlTermId)) {
                                    // Term from URL exists in data, use it
                                    selectedTermId = urlTermId;
                                    kelasXISemesterFilter.value = urlTermId;
                                } else if (activeTermXI) {
                                    selectedTermId = activeTermXI.id;
                                    kelasXISemesterFilter.value = activeTermXI.id;
                                } else if (data.length > 0) {
                                    selectedTermId = data[0].id;
                                    kelasXISemesterFilter.value = data[0].id;
                                }
                                
                                // Initialize table with selected term (will preserve filters from URL)
                                if (selectedTermId) {
                                    reloadKelasXITable(selectedTermId);
                                }
                            }
                        
                        // Filter only active terms for other modals
                        const activeTerms = data.filter(term => term.is_active === true);
                        
                        if (activeTerms.length > 0) {
                            // Load for import XI modal
                            const xiTermSelect = document.getElementById('xiTerm');
                            if (xiTermSelect) {
                                xiTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    xiTermSelect.appendChild(option);
                                });
                            }

                            // Load for import XI modal (alternative)
                            const termXISelect = document.getElementById('termXI');
                            if (termXISelect) {
                                termXISelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    termXISelect.appendChild(option);
                                });
                            }

                            // Load for manual subject modal
                            const manualTermSelect = document.getElementById('manual_term_id');
                            if (manualTermSelect) {
                                manualTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    manualTermSelect.appendChild(option);
                                });
                            }

                            // Load for jadwal import modal (Kelas X)
                            const jadwalTermSelect = document.getElementById('jadwalTerm');
                            if (jadwalTermSelect) {
                                jadwalTermSelect.innerHTML = '<option value="">Pilih Semester</option>';
                                activeTerms.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name;
                                    jadwalTermSelect.appendChild(option);
                                });
                            }
                        } else {
                            // Show no active terms message in dropdowns (except Kelas X filter)
                            const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm'];
                            dropdowns.forEach(id => {
                                const select = document.getElementById(id);
                                if (select) {
                                    select.innerHTML = '<option value="">Tidak ada semester aktif</option>';
                                }
                            });
                            
                            // For Kelas X filter, show all terms even if no active
                            const kelasXSemesterFilter = document.getElementById('kelasXSemesterFilter');
                            if (kelasXSemesterFilter && data.length > 0) {
                                kelasXSemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXSemesterFilter.appendChild(option);
                                });
                                
                                // Set default to first term
                                kelasXSemesterFilter.value = data[0].id;
                                // Initialize table with first term
                                reloadKelasXTable(data[0].id);
                            }

                            // For Kelas XI filter, show all terms even if no active
                            const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                            if (kelasXISemesterFilter && data.length > 0) {
                                kelasXISemesterFilter.innerHTML = '<option value="">Pilih Semester</option>';
                                data.forEach(term => {
                                    const option = document.createElement('option');
                                    option.value = term.id;
                                    option.textContent = term.name + (term.is_active ? ' (Aktif)' : '');
                                    kelasXISemesterFilter.appendChild(option);
                                });
                                
                                // Check if term_id is already in URL (from filter or previous selection)
                                const urlParams = new URLSearchParams(window.location.search);
                                const urlTermId = urlParams.get("term_id");
                                
                                // Use term from URL if exists and valid, otherwise use first
                                let selectedTermId = null;
                                if (urlTermId && data.find(t => t.id == urlTermId)) {
                                    // Term from URL exists in data, use it
                                    selectedTermId = urlTermId;
                                    kelasXISemesterFilter.value = urlTermId;
                                } else if (data.length > 0) {
                                    selectedTermId = data[0].id;
                                    kelasXISemesterFilter.value = data[0].id;
                                }
                                
                                // Initialize table with selected term (will preserve filters from URL)
                                if (selectedTermId) {
                                    reloadKelasXITable(selectedTermId);
                                }
                            }
                        }
                    } else {
                        // Show no data message in dropdowns
                        const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm', 'kelasXSemesterFilter', 'kelasXISemesterFilter'];
                        dropdowns.forEach(id => {
                            const select = document.getElementById(id);
                            if (select) {
                                select.innerHTML = '<option value="">Tidak ada semester tersedia</option>';
                            }
                        });
                    }
                })
                .catch(error => {
                    // Show error message in dropdowns
                    const dropdowns = ['xiTerm', 'termXI', 'manual_term_id', 'jadwalTerm', 'kelasXSemesterFilter', 'kelasXISemesterFilter'];
                    dropdowns.forEach(id => {
                        const select = document.getElementById(id);
                        if (select) {
                            select.innerHTML = '<option value="">Error loading semester data</option>';
                        }
                    });
                });
        }

        // Function to reload Kelas X table with selected semester
        function reloadKelasXTable(termId = null, classId = null, day = null) {
            // Get current filter values if not provided
            if (termId === null) {
                const termFilter = document.getElementById('kelasXSemesterFilter');
                termId = termFilter ? termFilter.value : null;
            }
            if (classId === null) {
                const classFilter = document.getElementById('kelasXClassFilter');
                classId = classFilter && classFilter.value ? classFilter.value : null;
            }
            if (day === null) {
                const dayFilter = document.getElementById('kelasXDayFilter');
                day = dayFilter && dayFilter.value ? dayFilter.value : null;
            }

            // Build URL with filter parameters
            const params = new URLSearchParams();
            if (termId) {
                params.append('term_id', termId);
            }
            if (classId) {
                params.append('class_id', classId);
            }
            if (day) {
                params.append('day', day);
            }
            
            const baseUrl = "/admin/jadwal";
            const urlWithParams = params.toString() ? `${baseUrl}?${params.toString()}` : baseUrl;

            // Check if GridJS instance exists
            if (window.gridInstanceJadwal) {
                // Update the data source URL with filter parameters
                window.gridInstanceJadwal.config.server.url = urlWithParams;
                window.gridInstanceJadwal.forceRender();
            } else {
                // If no GridJS instance, try to initialize the table
                if (window.tabelJadwalInstance && window.tabelJadwalInstance.initJadwalTable) {
                    // Pass filter parameters to the initialization
                    window.tabelJadwalInstance.initJadwalTable(termId, classId, day);
                }
            }
        }

        // Function to reload Kelas XI table with selected semester
        function reloadKelasXITable(termId) {
            // Get current filter parameters from URL to preserve them
            const urlParams = new URLSearchParams(window.location.search);
            const filterParams = {
                class: urlParams.get("class") || "",
                group_type: urlParams.get("group_type") || "",
                week_type: urlParams.get("week_type") || "",
                location_type: urlParams.get("location_type") || "",
                day: urlParams.get("day") || "",
            };
            
            // Add term_id parameter if provided
            if (termId) {
                filterParams.term_id = termId;
            }
            
            // Build query string preserving all filters
            const queryString = new URLSearchParams(filterParams).toString();
            const baseUrl = "/admin/jadwal-xi";
            const urlWithParams = queryString ? `${baseUrl}?${queryString}` : baseUrl;
            
            // Check if GridJS instance exists
            if (window.gridInstanceJadwalXI) {
                // Update the server URL with all parameters
                window.gridInstanceJadwalXI.config.server.url = urlWithParams;
                window.gridInstanceJadwalXI.forceRender();
            } else {
                // If no GridJS instance, try to initialize the table
                if (window.tabelJadwalInstance && window.tabelJadwalInstance.initJadwalXiTable) {
                    // Pass term_id to the initialization (initJadwalXiTable will read filters from URL)
                    window.tabelJadwalInstance.initJadwalXiTable(termId);
                }
            }
        }

        // Function to load classes for Kelas X filter
        function loadClassesForKelasX() {
            fetch('/admin/classes', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data && data.length > 0) {
                        // Filter only grade 10 classes
                        const kelasXClasses = data.filter(cls => cls.grade === '10' || cls.grade === 10);
                        
                        const kelasXClassFilter = document.getElementById('kelasXClassFilter');
                        if (kelasXClassFilter) {
                            kelasXClassFilter.innerHTML = '<option value="">Semua Kelas</option>';
                            kelasXClasses.forEach(cls => {
                                const option = document.createElement('option');
                                option.value = cls.id;
                                option.textContent = cls.display_grade || cls.name;
                                kelasXClassFilter.appendChild(option);
                            });

                            // Add event listener for class filter change
                            kelasXClassFilter.addEventListener('change', function() {
                                reloadKelasXTable();
                            });
                        }
                    }
                })
                .catch(error => {
                    // Silent error handling
                });
        }

        // Initialize notification modal
        document.addEventListener('DOMContentLoaded', function() {
            // Wait a bit to ensure all elements are ready
            setTimeout(function() {
                loadTermsData();

                // Add event listener for Kelas X day filter
                const kelasXDayFilter = document.getElementById('kelasXDayFilter');
                if (kelasXDayFilter) {
                    kelasXDayFilter.addEventListener('change', function() {
                        reloadKelasXTable();
                    });
                }

                // Add event listener for Kelas XI semester filter
                const kelasXISemesterFilter = document.getElementById('kelasXISemesterFilter');
                
                if (kelasXISemesterFilter) {
                    kelasXISemesterFilter.addEventListener('change', function() {
                        const selectedTermId = this.value;
                        
                        if (selectedTermId) {
                            // Get current filter parameters from URL to preserve them
                            const urlParams = new URLSearchParams(window.location.search);
                            const filterParams = {
                                class: urlParams.get("class") || "",
                                group_type: urlParams.get("group_type") || "",
                                week_type: urlParams.get("week_type") || "",
                                location_type: urlParams.get("location_type") || "",
                                day: urlParams.get("day") || "",
                                term_id: selectedTermId
                            };
                            
                            // Update URL with new term_id but preserve other filters
                            const queryString = new URLSearchParams(filterParams).toString();
                            const newUrl = queryString ? `?${queryString}` : window.location.pathname;
                            window.history.pushState({}, "", newUrl);
                            
                            // Reload Kelas XI table with selected semester (will preserve filters)
                            reloadKelasXITable(selectedTermId);
                        } else {
                            // Clear table if no semester selected
                            const tableContainer = document.getElementById('table-search-xi');
                            if (tableContainer) {
                                tableContainer.innerHTML = '<div class="text-center text-muted">Pilih semester untuk melihat jadwal</div>';
                            }
                            
                            // Remove term_id from URL but preserve other filters
                            const urlParams = new URLSearchParams(window.location.search);
                            urlParams.delete("term_id");
                            const queryString = urlParams.toString();
                            const newUrl = queryString ? `?${queryString}` : window.location.pathname;
                            window.history.pushState({}, "", newUrl);
                        }
                    });
                }
            }, 100);
            
            const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

            // Track if notification is from import XI or delete all
            let isImportXINotification = false;
            let isDeleteAllXNotification = false;
            let isDeleteAllXINotification = false;

            // Ensure close buttons work
            document.querySelector('#notificationModal .btn-close').addEventListener('click', () => {
                notificationModal.hide();
            });
            document.querySelector('#notificationModal .btn-light').addEventListener('click', () => {
                notificationModal.hide();
            });

            // Auto-reload when notification modal is closed after import XI or delete all
            document.getElementById('notificationModal').addEventListener('hidden.bs.modal', function() {
                if (isImportXINotification) {
                    // Reload Kelas XI table
                    if (window.gridInstanceJadwalXI) {
                        window.gridInstanceJadwalXI.forceRender();
                    } else if (window.gridXiReload) {
                        window.gridXiReload();
                    } else {
                        // Get current term_id from URL or filter
                        const urlParams = new URLSearchParams(window.location.search);
                        const termId = urlParams.get('term_id') || document.getElementById('kelasXISemesterFilter')?.value;
                        if (termId) {
                            reloadKelasXITable(termId);
                        } else {
                            location.reload();
                        }
                    }
                    isImportXINotification = false;
                } else if (isDeleteAllXNotification) {
                    // Reload page for Kelas X
                    location.reload();
                    isDeleteAllXNotification = false;
                } else if (isDeleteAllXINotification) {
                    // Reload page for Kelas XI
                    location.reload();
                    isDeleteAllXINotification = false;
                }
            });

            // Function to show notification (same as manage-user)
            function showNotification(message, isSuccess = true, options = {}) {
                document.getElementById('notificationModalLabel').innerText = isSuccess ? 'Berhasil' : 'Gagal';
                document.getElementById('notificationMessage').innerText = message;
                
                // Reset all flags
                isImportXINotification = false;
                isDeleteAllXNotification = false;
                isDeleteAllXINotification = false;
                
                // Handle backward compatibility: if options is boolean (old format), treat as fromImportXI
                if (typeof options === 'boolean') {
                    options = { fromImportXI: options };
                }
                
                // Set flags based on options
                if (isSuccess && options && typeof options === 'object') {
                    if (options.fromImportXI) {
                        isImportXINotification = true;
                    } else if (options.fromDeleteAllX) {
                        isDeleteAllXNotification = true;
                    } else if (options.fromDeleteAllXI) {
                        isDeleteAllXINotification = true;
                    }
                }
                
                notificationModal.show();
            }

            // Make showNotification available globally
            window.showNotification = showNotification;

            // Handle single delete for Kelas X
            const confirmDeleteJadwalButton = document.getElementById('confirmDeleteJadwalButton');
            if (confirmDeleteJadwalButton) {
                confirmDeleteJadwalButton.addEventListener('click', function() {
                    const id = document.getElementById('deleteJadwalId').value;
                    if (!id) return;

                    fetch(`/admin/jadwal/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Close delete modal
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteJadwalModal'));
                        if (deleteModal) {
                            deleteModal.hide();
                        }

                        // Show notification
                        showNotification(data.message || 'Jadwal berhasil dihapus', data.success !== false);

                        // Reload table after notification is shown (delay to ensure modal is visible)
                        if (data.success !== false) {
                            setTimeout(() => {
                                if (window.gridInstanceJadwal) {
                                    window.gridInstanceJadwal.forceRender();
                                } else {
                                    location.reload();
                                }
                            }, 2000); // Wait 2 seconds for notification to be visible
                        }
                    })
                    .catch(error => {
                        // Close delete modal
                        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteJadwalModal'));
                        if (deleteModal) {
                            deleteModal.hide();
                        }
                        showNotification('Gagal menghapus jadwal: ' + error.message, false);
                    });
                });
            }

            // Handle bulk delete for Kelas X
            const confirmBulkDeleteJadwalButton = document.getElementById('confirmBulkDeleteJadwalButton');
            if (confirmBulkDeleteJadwalButton) {
                confirmBulkDeleteJadwalButton.addEventListener('click', function() {
                    const ids = document.getElementById('deleteJadwalIds').value;
                    if (!ids) {
                        showNotification('Tidak ada jadwal yang dipilih', false);
                        return;
                    }

                    fetch('/admin/jadwal/bulk-delete', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ ids: ids })
                    })
                    .then(response => response.json())
                    .then(data => {
                        // Close bulk delete modal
                        const bulkDeleteModal = bootstrap.Modal.getInstance(document.getElementById('bulkDeleteJadwalModal'));
                        if (bulkDeleteModal) {
                            bulkDeleteModal.hide();
                        }

                        // Show notification
                        showNotification(data.message || 'Jadwal berhasil dihapus', data.success !== false);

                        // Reset checkboxes
                        const selectAllCheckbox = document.getElementById('select-all-jadwal-checkbox');
                        if (selectAllCheckbox) selectAllCheckbox.checked = false;
                        document.querySelectorAll('.row-checkbox-jadwal').forEach(cb => cb.checked = false);

                        // Reload table after notification is shown (delay to ensure modal is visible)
                        if (data.success !== false) {
                            setTimeout(() => {
                                if (window.gridInstanceJadwal) {
                                    window.gridInstanceJadwal.forceRender();
                                } else {
                                    location.reload();
                                }
                            }, 2000); // Wait 2 seconds for notification to be visible
                        }
                    })
                    .catch(error => {
                        // Close bulk delete modal
                        const bulkDeleteModal = bootstrap.Modal.getInstance(document.getElementById('bulkDeleteJadwalModal'));
                        if (bulkDeleteModal) {
                            bulkDeleteModal.hide();
                        }
                        showNotification('Gagal menghapus jadwal: ' + error.message, false);
                    });
                });
            }

            // Hook bulk delete button to open modal with selected IDs for Kelas X
            const bulkDeleteJadwalButton = document.getElementById('bulk-delete-jadwal');
            if (bulkDeleteJadwalButton) {
                bulkDeleteJadwalButton.addEventListener('click', function() {
                    const selectedIds = Array.from(
                        document.querySelectorAll('.row-checkbox-jadwal:checked')
                    ).map(cb => cb.dataset.id || cb.value);

                    if (selectedIds.length === 0) {
                        showNotification('Pilih minimal satu jadwal untuk dihapus', false);
                        return;
                    }

                    document.getElementById('deleteJadwalIds').value = selectedIds.join(',');
                    const modal = new bootstrap.Modal(document.getElementById('bulkDeleteJadwalModal'));
                    modal.show();
                });
            }

            // Override deleteAllJadwal function to use notification with auto-reload
            window.deleteAllJadwal = function() {
                fetch('/admin/jadwal/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Close delete all modal
                    const deleteAllModal = bootstrap.Modal.getInstance(document.getElementById('deleteAllJadwalModal'));
                    if (deleteAllModal) {
                        deleteAllModal.hide();
                    }

                    // Show notification with flag for auto-reload
                    showNotification(data.message || 'Semua data jadwal kelas X berhasil dihapus!', data.success !== false, { fromDeleteAllX: true });
                })
                .catch(error => {
                    // Close delete all modal
                    const deleteAllModal = bootstrap.Modal.getInstance(document.getElementById('deleteAllJadwalModal'));
                    if (deleteAllModal) {
                        deleteAllModal.hide();
                    }
                    showNotification('Gagal menghapus semua data jadwal: ' + error.message, false);
                });
            };

            // Override deleteAllJadwalXi function to use notification with auto-reload
            window.deleteAllJadwalXi = function() {
                fetch('/admin/jadwal-xi/delete-all', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Close delete all modal
                    const deleteAllModal = bootstrap.Modal.getInstance(document.getElementById('deleteAllJadwalXiModal'));
                    if (deleteAllModal) {
                        deleteAllModal.hide();
                    }

                    // Show notification with flag for auto-reload
                    showNotification(data.message || 'Semua data jadwal kelas XI berhasil dihapus!', data.success !== false, { fromDeleteAllXI: true });
                })
                .catch(error => {
                    // Close delete all modal
                    const deleteAllModal = bootstrap.Modal.getInstance(document.getElementById('deleteAllJadwalXiModal'));
                    if (deleteAllModal) {
                        deleteAllModal.hide();
                    }
                    showNotification('Gagal menghapus semua data jadwal XI: ' + error.message, false);
                });
            };

            // Handle import XI form submission with AJAX
            const importJadwalXiForm = document.getElementById('importJadwalXiForm');
            if (importJadwalXiForm) {
                importJadwalXiForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]') || 
                                        document.querySelector('button[form="importJadwalXiForm"]');
                    
                    // Disable submit button and show loading
                    if (submitButton) {
                        submitButton.disabled = true;
                        const originalText = submitButton.textContent;
                        submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Mengimport...';
                        
                        fetch('/admin/jadwal-xi/import', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                return response.json().then(data => {
                                    throw new Error(data.message || 'Gagal mengimport jadwal');
                                });
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Close import modal
                            const importModal = bootstrap.Modal.getInstance(document.getElementById('importJadwalXIModal'));
                            if (importModal) {
                                importModal.hide();
                            }
                            
                            // Show notification with flag for auto-reload
                            showNotification(data.message || 'Jadwal berhasil diimport!', data.success !== false, { fromImportXI: true });
                            
                            // Reset form
                            importJadwalXiForm.reset();
                        })
                        .catch(error => {
                            // Close import modal
                            const importModal = bootstrap.Modal.getInstance(document.getElementById('importJadwalXIModal'));
                            if (importModal) {
                                importModal.hide();
                            }
                            
                            // Show error notification
                            showNotification(error.message || 'Gagal mengimport jadwal. Silakan coba lagi.', false, false);
                        })
                        .finally(() => {
                            // Re-enable submit button
                            if (submitButton) {
                                submitButton.disabled = false;
                                submitButton.textContent = originalText;
                            }
                        });
                    }
                });
            }
        });

        // Export functions for Jadwal X and XI
        function exportJadwalX(format = 'pdf') {
            try {
                // Check if export is already in progress
                if (window.exportNavigating) {
                    return;
                }

                // Get current filter values
                const termId = document.getElementById('kelasXSemesterFilter')?.value || '';
                const classId = document.getElementById('kelasXClassFilter')?.value || '';
                const day = document.getElementById('kelasXDayFilter')?.value || '';
                
                // Build export URL
                let exportUrl = (window.jadwalExportUrl || '/admin/jadwal/export') + '?format=' + format;
                if (termId) {
                    exportUrl += '&term_id=' + termId;
                }
                if (classId) {
                    exportUrl += '&class_id=' + classId;
                }
                if (day) {
                    exportUrl += '&day=' + day;
                }
                
                // Show loading indicator
                showExportLoading(format, 'Jadwal Kelas X');
                
                // Mark as navigating to prevent duplicate
                window.exportNavigating = true;
                
                // Use window.location.href for direct download (more reliable)
                window.location.href = exportUrl;
                
                // Show success message after a delay
                setTimeout(function() {
                    showExportSuccess('Export berhasil! File sedang diunduh.');
                    window.exportNavigating = false;
                }, 2000);
                
            } catch (error) {
                showExportError('Terjadi kesalahan saat export: ' + error.message);
                window.exportNavigating = false;
            }
        }
        
        function exportJadwalXI(format = 'pdf') {
            try {
                // Get current term_id and filters from URL
                const urlParams = new URLSearchParams(window.location.search);
                const termId = urlParams.get('term_id') || document.getElementById('kelasXISemesterFilter')?.value || '';
                const classFilter = urlParams.get('class') || '';
                const groupType = urlParams.get('group_type') || '';
                const weekType = urlParams.get('week_type') || '';
                const locationType = urlParams.get('location_type') || '';
                const day = urlParams.get('day') || '';
                
                // Build export URL
                let exportUrl = (window.jadwalXiExportUrl || '/admin/jadwal-xi/export') + '?format=' + format;
                if (termId) exportUrl += '&term_id=' + termId;
                if (classFilter) exportUrl += '&class=' + classFilter;
                if (groupType) exportUrl += '&group_type=' + groupType;
                if (weekType) exportUrl += '&week_type=' + weekType;
                if (locationType) exportUrl += '&location_type=' + locationType;
                if (day) exportUrl += '&day=' + day;
                
                // Show loading indicator
                showExportLoading(format, 'Jadwal Kelas XI');
                
                // Flag to track if export is in progress
                let exportInProgress = true;
                let fallbackTimeout = null;
                
                // Create a hidden iframe to handle the download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = exportUrl;
                document.body.appendChild(iframe);
                
                // Clean up after download starts
                setTimeout(function() {
                    if (iframe.parentNode) {
                        document.body.removeChild(iframe);
                    }
                    exportInProgress = false;
                    showExportSuccess('Export berhasil! File sedang diunduh.');
                    
                    // Clear fallback timeout if it exists
                    if (fallbackTimeout) {
                        clearTimeout(fallbackTimeout);
                        fallbackTimeout = null;
                    }
                }, 1000);
                
                // Fallback if iframe doesn't work (only if export hasn't succeeded)
                fallbackTimeout = setTimeout(function() {
                    if (exportInProgress) {
                        exportInProgress = false; // Prevent multiple fallback calls
                        tryFallbackExportJadwalXI(format);
                    }
                }, 3000);
                
            } catch (error) {
                showExportError('Terjadi kesalahan saat export: ' + error.message);
            }
        }
        
        
        // Fallback export method using direct link
        function tryFallbackExportJadwalXI(format = 'pdf') {
            try {
                // Check if export already succeeded (prevent duplicate downloads)
                const existingIframe = document.querySelector('iframe[src*="jadwal-xi.export"]');
                if (existingIframe) {
                    return;
                }
                
                // Check if we're already navigating (prevent duplicate)
                if (window.exportNavigating) {
                    return;
                }
                
                // Get current term_id and filters from URL
                const urlParams = new URLSearchParams(window.location.search);
                const termId = urlParams.get('term_id') || document.getElementById('kelasXISemesterFilter')?.value || '';
                const classFilter = urlParams.get('class') || '';
                const groupType = urlParams.get('group_type') || '';
                const weekType = urlParams.get('week_type') || '';
                const locationType = urlParams.get('location_type') || '';
                const day = urlParams.get('day') || '';
                
                // Build export URL
                let exportUrl = (window.jadwalXiExportUrl || '/admin/jadwal-xi/export') + '?format=' + format;
                if (termId) exportUrl += '&term_id=' + termId;
                if (classFilter) exportUrl += '&class=' + classFilter;
                if (groupType) exportUrl += '&group_type=' + groupType;
                if (weekType) exportUrl += '&week_type=' + weekType;
                if (locationType) exportUrl += '&location_type=' + locationType;
                if (day) exportUrl += '&day=' + day;
                
                // Mark as navigating to prevent duplicate
                window.exportNavigating = true;
                
                // Show loading again
                showExportLoading(format, 'Jadwal Kelas XI');
                
                // Use window.location as ultimate fallback
                window.location.href = exportUrl;
                
                // Hide loading after a short delay
                setTimeout(function() {
                    showExportSuccess('Export berhasil! File sedang diunduh.');
                    window.exportNavigating = false;
                }, 2000);
                
            } catch (error) {
                showExportError('Gagal mengexport data. Silakan coba lagi atau hubungi administrator.');
                window.exportNavigating = false;
            }
        }
        
        // Show loading indicator for export
        function showExportLoading(format, reportType = '', message = '', type = 'info') {
            const formatText = format === 'pdf' ? 'PDF' : 'File';
            const alertClass = type === 'success' ? 'alert-success' : (type === 'danger' ? 'alert-danger' : 'alert-info');
            const iconClass = type === 'success' ? 'bx-check-circle' : (type === 'danger' ? 'bx-x-circle' : '');
            const spinnerHtml = type === 'success' || type === 'danger' ? '' : '<div class="spinner-border spinner-border-sm me-2" role="status"><span class="visually-hidden">Loading...</span></div>';
            const iconHtml = iconClass ? `<i class="bx ${iconClass} me-2" style="font-size: 1.2em;"></i>` : '';
            
            const loadingHtml = `
                <div id="exportLoading" class="alert ${alertClass} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                    <div class="d-flex align-items-center">
                        ${spinnerHtml}
                        ${iconHtml}
                        <div>
                            <strong>${message || `Sedang memproses export ${formatText}${reportType ? ' - ' + reportType : ''}...`}</strong>
                            ${message ? '' : '<br><small>File akan segera diunduh</small>'}
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing loading if any
            const existingLoading = document.getElementById('exportLoading');
            if (existingLoading) {
                existingLoading.remove();
            }
            
            // Add new loading indicator
            document.body.insertAdjacentHTML('beforeend', loadingHtml);
        }
        
        // Hide loading indicator
        function hideExportLoading() {
            const loadingElement = document.getElementById('exportLoading');
            if (loadingElement) {
                loadingElement.classList.remove('show');
                setTimeout(function() {
                    loadingElement.remove();
                }, 150);
            }
        }
        
        // Show success message in loading indicator
        function showExportSuccess(message = 'Export berhasil! File sedang diunduh.') {
            showExportLoading('pdf', '', message, 'success');
            setTimeout(function() {
                hideExportLoading();
            }, 3000);
        }
        
        // Show error message in loading indicator
        function showExportError(message = 'Gagal mengexport data. Silakan coba lagi.') {
            showExportLoading('pdf', '', message, 'danger');
            setTimeout(function() {
                hideExportLoading();
            }, 5000);
        }
        
        // Show alert message (same as menu laporan)
        function showAlert(message, type = 'info') {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            document.body.insertAdjacentHTML('beforeend', alertHtml);
            
            // Auto-dismiss after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            alert.remove();
                        }, 150);
                    }
                });
            }, 5000);
        }
        
        // Make functions globally available
        window.exportJadwalX = exportJadwalX;
        window.exportJadwalXI = exportJadwalXI;
        window.showAlert = showAlert;
        
        // Persist active tab across reloads (hash + localStorage fallback)
        (function() {
            const STORAGE_KEY = 'admin-jadwal-active-tab';
            function activateTabBySelector(selector) {
                const link = document.querySelector(`a[data-bs-toggle="tab"][href="${selector}"]`);
                if (link) { new bootstrap.Tab(link).show(); return true; }
                return false;
            }

            window.addEventListener('load', function() {
                // Prefer URL hash if present
                if (location.hash && activateTabBySelector(location.hash)) {
                    // synced
                } else {
                    const saved = localStorage.getItem(STORAGE_KEY);
                    if (saved) activateTabBySelector(saved);
                }
            });

            document.querySelectorAll('a[data-bs-toggle="tab"]').forEach(link => {
                link.addEventListener('shown.bs.tab', function (e) {
                    const target = e.target.getAttribute('href');
                    if (!target) return;
                    // Update hash and storage
                    history.replaceState(null, '', target);
                    localStorage.setItem(STORAGE_KEY, target);
                    
                    // Initialize table when Kelas XI tab is shown
                    if (target === '#kelasxi' && window.tabelJadwalInstance) {
                        // Check if table is already initialized
                        if (!window.gridInstanceJadwalXI) {
                            if (window.tabelJadwalInstance.initJadwalXiTable) {
                                // Get term_id from URL to preserve it
                                const urlParams = new URLSearchParams(window.location.search);
                                const termId = urlParams.get("term_id");
                                window.tabelJadwalInstance.initJadwalXiTable(termId);
                            }
                        }
                    }
                    
                    // Initialize terms table when Info Akademik tab is shown
                    if (target === '#mapel') {
                        if (window.tabelJadwalInstance && window.tabelJadwalInstance.initTermsTable) {
                            window.tabelJadwalInstance.initTermsTable();
                        }
                    }
                });
            });
        })();

        // Grade is now fixed to 'X' for import jadwal, so no need for change event listener
        // Ensure weekTypeContainer is hidden for Kelas X
        const weekTypeContainer = document.getElementById('weekTypeContainer');
        if (weekTypeContainer) {
            weekTypeContainer.style.display = 'none';
        }

        // Manual Class Subject Functions - Step by Step
        let currentStep = 1;
        let formData = {
            day_of_week: null,
            start_time: null,
            end_time: null,
            class_id: null,
            class_type: null,
            week_type: null,
            subject_id: null,
            teacher_id: null
        };

        // Initialize step navigation
        document.addEventListener('DOMContentLoaded', function() {
            initializeStepNavigation();
        });

        function initializeStepNavigation() {
            // Step 1: Semester, Day and Time validation
            const manualTermSelect = document.getElementById('manual_term_id');
            const daySelect = document.getElementById('manual_day');
            const startTimeInput = document.getElementById('manual_start_time');
            const endTimeInput = document.getElementById('manual_end_time');
            const nextToStep2Btn = document.getElementById('nextToStep2');

            function validateStep1() {
                const isValid = manualTermSelect.value && daySelect.value && startTimeInput.value && endTimeInput.value && 
                               startTimeInput.value < endTimeInput.value;
                nextToStep2Btn.disabled = !isValid;
            }

            manualTermSelect.addEventListener('change', function() {
                formData.term_id = this.value;
                validateStep1();
            });

            daySelect.addEventListener('change', function() {
                formData.day_of_week = this.value;
                validateStep1();
            });

            startTimeInput.addEventListener('change', function() {
                formData.start_time = this.value;
                validateStep1();
            });

            endTimeInput.addEventListener('change', function() {
                formData.end_time = this.value;
                validateStep1();
            });

            // Step navigation buttons
            document.getElementById('nextToStep2').addEventListener('click', function() {
                loadAvailableClasses();
                showStep(2);
            });

            document.getElementById('backToStep1').addEventListener('click', function() {
                showStep(1);
            });

            document.getElementById('nextToStep3').addEventListener('click', function() {
                loadAvailableSubjects();
                showStep(3);
            });

            document.getElementById('backToStep2').addEventListener('click', function() {
                showStep(2);
            });

            document.getElementById('nextToStep4').addEventListener('click', function() {
                loadAvailableTeachers();
                showStep(4);
            });

            document.getElementById('backToStep3').addEventListener('click', function() {
                showStep(3);
            });

            document.getElementById('submitForm').addEventListener('click', function() {
                submitManualClassSubject();
            });
        }

        function showStep(stepNumber) {
            // Hide all steps
            document.querySelectorAll('.step-container').forEach(step => {
                step.style.display = 'none';
            });
            
            // Show current step
            document.getElementById(`step${stepNumber}`).style.display = 'block';
            currentStep = stepNumber;
        }

        function loadAvailableClasses() {
            const classSelect = document.getElementById('manual_class_id');
            classSelect.innerHTML = '<option value="">Memuat kelas yang tersedia...</option>';

            fetch((window.manualFormDataUrl || '/admin/manual-form-data') + `?day=${formData.day_of_week}&start_time=${formData.start_time}&end_time=${formData.end_time}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                        data.data.classes.forEach(classItem => {
                            const option = document.createElement('option');
                            option.value = classItem.id;
                            option.textContent = classItem.display_name;
                            option.setAttribute('data-grade', classItem.grade);
                            classSelect.appendChild(option);
                        });

                        // Show conflict information if available
                        if (data.data.filter_info) {
                            const filterInfo = data.data.filter_info;
                            const conflictingClasses = data.data.conflicting_classes || [];
                            
                            if (conflictingClasses.length > 0) {
                                showConflictInfo(filterInfo, conflictingClasses);
                            }
                        }

                        // Add event listener for class change
                        classSelect.addEventListener('change', function() {
                            const selectedOption = this.options[this.selectedIndex];
                            const grade = selectedOption.getAttribute('data-grade');
                            
                            formData.class_id = this.value;
                            formData.class_type = null;
                            formData.week_type = null;
                            
                            const classTypeContainer = document.getElementById('manual_class_type_container');
                            const weekTypeContainer = document.getElementById('manual_week_type_container');
                            const classTypeSelect = document.getElementById('manual_class_type');
                            const weekTypeSelect = document.getElementById('manual_week_type');
                            const nextToStep3Btn = document.getElementById('nextToStep3');
                            
                            if (grade === '11') {
                                classTypeContainer.style.display = 'block';
                                weekTypeContainer.style.display = 'block';
                                classTypeSelect.required = true;
                                weekTypeSelect.required = true;
                                
                                // Add event listeners for class type and week type
                                classTypeSelect.addEventListener('change', function() {
                                    formData.class_type = this.value;
                                    validateStep2();
                                });
                                
                                weekTypeSelect.addEventListener('change', function() {
                                    formData.week_type = this.value;
                                    validateStep2();
                                });
                            } else {
                                classTypeContainer.style.display = 'none';
                                weekTypeContainer.style.display = 'none';
                                classTypeSelect.required = false;
                                weekTypeSelect.required = false;
                                classTypeSelect.value = '';
                                weekTypeSelect.value = '';
                                nextToStep3Btn.disabled = false;
                            }
                        });
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat kelas yang tersedia');
                });
        }

        function showConflictInfo(filterInfo, conflictingClasses) {
            const days = ['', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const dayName = days[filterInfo.day];
            
            let conflictMessage = `
                <div class="alert alert-warning mt-3">
                    <i class="bx bx-info-circle me-2"></i>
                    <strong>Informasi Konflik Jadwal:</strong><br>
                    Pada <strong>${dayName} ${filterInfo.start_time}-${filterInfo.end_time}</strong>, 
                    terdapat <strong>${filterInfo.conflicting_classes_count}</strong> kelas yang sudah memiliki jadwal 
                    dari total <strong>${filterInfo.total_classes}</strong> kelas.
                    <br><br>
                    <strong>Kelas yang tidak tersedia:</strong><br>
            `;
            
            conflictingClasses.forEach(conflictClass => {
                conflictMessage += `â€¢ ${conflictClass.display_name}<br>`;
            });
            
            conflictMessage += `
                    <br>
                    <small class="text-muted">
                        <i class="bx bx-lightbulb me-1"></i>
                        <strong>Tips:</strong> Coba pilih waktu yang berbeda atau kelas lain yang tersedia.
                    </small>
                </div>
            `;
            
            // Remove existing conflict info
            const existingConflictInfo = document.querySelector('.alert-warning');
            if (existingConflictInfo) {
                existingConflictInfo.remove();
            }
            
            // Add new conflict info after the class select
            const classSelectContainer = document.getElementById('manual_class_id').parentElement;
            classSelectContainer.insertAdjacentHTML('afterend', conflictMessage);
        }

        function validateStep2() {
            const classSelect = document.getElementById('manual_class_id');
            const classTypeSelect = document.getElementById('manual_class_type');
            const weekTypeSelect = document.getElementById('manual_week_type');
            const nextToStep3Btn = document.getElementById('nextToStep3');
            
            const selectedClass = classSelect.options[classSelect.selectedIndex];
            const grade = selectedClass.getAttribute('data-grade');
            
            let isValid = classSelect.value;
            
            if (grade === '11') {
                isValid = isValid && classTypeSelect.value && weekTypeSelect.value;
            }
            
            nextToStep3Btn.disabled = !isValid;
        }

        function loadAvailableSubjects() {
            const subjectSelect = document.getElementById('manual_subject_id');
            subjectSelect.innerHTML = '<option value="">Memuat mata pelajaran yang tersedia...</option>';

            const params = new URLSearchParams({
                day: formData.day_of_week,
                start_time: formData.start_time,
                end_time: formData.end_time,
                class_id: formData.class_id
            });

            fetch((window.manualFormDataUrl || '/admin/manual-form-data') + `?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                        data.data.subjects.forEach(subject => {
                            const option = document.createElement('option');
                            option.value = subject.id;
                            option.textContent = subject.name;
                            subjectSelect.appendChild(option);
                        });

                        subjectSelect.addEventListener('change', function() {
                            formData.subject_id = this.value;
                            validateStep3();
                        });
                        
                        function validateStep3() {
                            const nextToStep4Btn = document.getElementById('nextToStep4');
                            nextToStep4Btn.disabled = !subjectSelect.value;
                        }
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat mata pelajaran yang tersedia');
                });
        }

        function loadAvailableTeachers() {
            const teacherSelect = document.getElementById('manual_teacher_id');
            teacherSelect.innerHTML = '<option value="">Memuat guru yang tersedia...</option>';

            const params = new URLSearchParams({
                day: formData.day_of_week,
                start_time: formData.start_time,
                end_time: formData.end_time,
                class_id: formData.class_id,
                subject_id: formData.subject_id
            });

            fetch((window.manualFormDataUrl || '/admin/manual-form-data') + `?${params}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
                        data.data.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = `${teacher.name} (${teacher.nip || teacher.kode_guru})`;
                            teacherSelect.appendChild(option);
                        });

                        teacherSelect.addEventListener('change', function() {
                            formData.teacher_id = this.value;
                            const submitBtn = document.getElementById('submitForm');
                            submitBtn.disabled = !this.value;
                        });
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat guru yang tersedia');
                });
        }


        function submitManualClassSubject() {
            // Create FormData object with all collected data
            const submitData = new FormData();
            submitData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            submitData.append('day_of_week', formData.day_of_week);
            submitData.append('start_time', formData.start_time);
            submitData.append('end_time', formData.end_time);
            submitData.append('class_id', formData.class_id);
            submitData.append('subject_id', formData.subject_id);
            submitData.append('teacher_id', formData.teacher_id);
            submitData.append('term_id', document.getElementById('manual_term_id').value);
            
            if (formData.class_type) {
                submitData.append('class_type', formData.class_type);
            }
            if (formData.week_type) {
                submitData.append('week_type', formData.week_type);
            }

            fetch(window.manualClassSubjectStoreUrl || '/admin/manual/class-subject', {
                method: 'POST',
                body: submitData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showManualNotification('success', data.message, data.data);
                    resetForm();
                    bootstrap.Modal.getInstance(document.getElementById('addManualClassSubjectModal')).hide();
                    
                    // Refresh tabel jadwal berdasarkan kelas yang ditambahkan
                    refreshJadwalTable(data.data.class);
                } else {
                    showManualNotification('error', data.message);
                }
            })
            .catch(error => {
                showManualNotification('error', 'Gagal menambahkan jadwal mata pelajaran');
            });
        }

        function resetForm() {
            // Reset form data
            formData = {
                term_id: null,
                day_of_week: null,
                start_time: null,
                end_time: null,
                class_id: null,
                class_type: null,
                week_type: null,
                subject_id: null,
                teacher_id: null
            };

            // Reset form elements
            document.getElementById('manual_term_id').value = '';
            document.getElementById('manual_day').value = '';
            document.getElementById('manual_start_time').value = '';
            document.getElementById('manual_end_time').value = '';
            document.getElementById('manual_class_id').innerHTML = '<option value="">Memuat kelas yang tersedia...</option>';
            document.getElementById('manual_subject_id').innerHTML = '<option value="">Memuat mata pelajaran yang tersedia...</option>';
            document.getElementById('manual_teacher_id').innerHTML = '<option value="">Memuat guru yang tersedia...</option>';
            
            // Hide additional fields
            document.getElementById('manual_class_type_container').style.display = 'none';
            document.getElementById('manual_week_type_container').style.display = 'none';
            
            // Reset buttons
            document.getElementById('nextToStep2').disabled = true;
            document.getElementById('nextToStep3').disabled = true;
            document.getElementById('nextToStep4').disabled = true;
            document.getElementById('submitForm').disabled = true;
            
            // Show step 1
            showStep(1);
        }


        function showAlert(type, message) {
            const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
            const alertHTML = `
                <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            
            // Remove existing alerts
            document.querySelectorAll('.alert').forEach(alert => alert.remove());
            
            // Add new alert
            const container = document.querySelector('.container-fluid');
            container.insertAdjacentHTML('afterbegin', alertHTML);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                const alert = document.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 5000);
        }

        function showManualNotification(type, message, data = null) {
            const modal = document.getElementById('manualNotificationModal');
            const header = document.getElementById('manualNotificationHeader');
            const icon = document.getElementById('manualNotificationIcon');
            const iconClass = document.getElementById('manualNotificationIconClass');
            const title = document.getElementById('manualNotificationTitle');
            const subtitle = document.getElementById('manualNotificationSubtitle');
            const alert = document.getElementById('manualNotificationAlert');
            const alertIcon = document.getElementById('manualNotificationAlertIcon');
            const alertMessage = document.getElementById('manualNotificationMessage');
            const details = document.getElementById('manualNotificationDetails');
            const button = document.getElementById('manualNotificationButton');

            if (type === 'success') {
                // Success styling
                header.className = 'modal-header border-0 pb-0 bg-success bg-opacity-10';
                icon.className = 'avatar-sm bg-success bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center';
                iconClass.className = 'bx bx-check-circle fs-24 text-success';
                title.textContent = 'Berhasil!';
                subtitle.textContent = 'Jadwal mata pelajaran berhasil ditambahkan';
                alert.className = 'alert alert-success border-0 mb-0';
                alertIcon.className = 'bx bx-check-circle fs-20 text-success';
                button.className = 'btn btn-success w-100';
                button.innerHTML = '<i class="bx bx-check me-1"></i> Baik, Mengerti';

                // Show details if data is provided
                if (data) {
                    document.getElementById('detailClass').textContent = data.class || '-';
                    document.getElementById('detailSubject').textContent = data.subject || '-';
                    document.getElementById('detailTeacher').textContent = data.teacher || '-';
                    document.getElementById('detailDay').textContent = data.day || '-';
                    document.getElementById('detailTime').textContent = data.time || '-';
                    document.getElementById('detailType').textContent = data.type || '-';
                    details.style.display = 'block';
                } else {
                    details.style.display = 'none';
                }
            } else {
                // Error styling
                header.className = 'modal-header border-0 pb-0 bg-danger bg-opacity-10';
                icon.className = 'avatar-sm bg-danger bg-opacity-10 rounded-circle me-3 d-flex align-items-center justify-content-center';
                iconClass.className = 'bx bx-x-circle fs-24 text-danger';
                title.textContent = 'Gagal!';
                subtitle.textContent = 'Terjadi kesalahan saat menambahkan jadwal';
                alert.className = 'alert alert-danger border-0 mb-0';
                alertIcon.className = 'bx bx-error-circle fs-20 text-danger';
                button.className = 'btn btn-danger w-100';
                button.innerHTML = '<i class="bx bx-x me-1"></i> Tutup';
                details.style.display = 'none';
            }

            alertMessage.textContent = message;

            // Remove any existing event listeners to prevent conflicts
            const newButton = button.cloneNode(true);
            button.parentNode.replaceChild(newButton, button);

            // Add event listener to close modal properly
            newButton.addEventListener('click', function() {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                } else {
                    // Fallback: hide modal manually
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    document.body.classList.remove('modal-open');
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) {
                        backdrop.remove();
                    }
                }
            });

            // Also handle close button (X) in header
            const closeButton = modal.querySelector('.btn-close');
            if (closeButton) {
                const newCloseButton = closeButton.cloneNode(true);
                closeButton.parentNode.replaceChild(newCloseButton, closeButton);
                
                newCloseButton.addEventListener('click', function() {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    } else {
                        // Fallback: hide modal manually
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        const backdrop = document.querySelector('.modal-backdrop');
                        if (backdrop) {
                            backdrop.remove();
                        }
                    }
                });
            }

            // Show modal
            const bsModal = new bootstrap.Modal(modal);
            bsModal.show();

            // Add event listener for modal hidden event to clean up
            modal.addEventListener('hidden.bs.modal', function() {
                // Clean up any remaining backdrop
                const backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) {
                    backdrop.remove();
                }
                // Ensure body is not locked
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
                document.body.style.paddingRight = '';
            });

            // Handle ESC key to close modal
            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape' && modal.classList.contains('show')) {
                    const modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                }
            });
        }

        function refreshJadwalTable(className) {
            // Determine which table to refresh based on class name
            const isClassX = className.includes('-X') && !className.includes('-XI');
            const isClassXI = className.includes('-XI');
            
            if (isClassX) {
                // Refresh Kelas X table
                if (window.gridInstanceJadwal) {
                    window.gridInstanceJadwal.forceRender();
                } else {
                    location.reload();
                }
            } else if (isClassXI) {
                // Refresh Kelas XI table - same logic as Kelas X
                if (window.gridInstanceJadwalXI) {
                    window.gridInstanceJadwalXI.forceRender();
                } else if (window.gridXiReload) {
                    window.gridXiReload();
                } else {
                    location.reload();
                }
            } else {
                // Fallback: reload page if class type cannot be determined
                location.reload();
            }
        }

        // Function to load jadwal data for editing
        function loadJadwalForEdit(id) {
            // Fetch data jadwal
            fetch(`/admin/jadwal/${id}`, {
                headers: { Accept: 'application/json' }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Populate form dengan data yang ada
                    document.getElementById('editJadwalId').value = data.jadwal.id;
                    document.getElementById('editJadwalHari').value = data.jadwal.day_of_week;
                    document.getElementById('editJadwalJamMulai').value = data.jadwal.start_time;
                    document.getElementById('editJadwalJamSelesai').value = data.jadwal.end_time;
                    
                    if (data.jadwal.week_type) {
                        document.getElementById('editJadwalWeekType').value = data.jadwal.week_type;
                    }

                    // Load dropdown data
                    loadEditJadwalDropdowns(data.jadwal);
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('editJadwalModal'));
                    modal.show();
                } else {
                    showAlert('error', 'Gagal memuat data jadwal');
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal memuat data jadwal');
            });
        }

        // Load teachers by subject for edit modal
        function loadEditTeachersBySubject(subjectId, selectedTeacherId = null) {
            if (!subjectId) {
                const teacherSelect = document.getElementById('editJadwalGuru');
                teacherSelect.innerHTML = '<option value="">Pilih Mata Pelajaran terlebih dahulu</option>';
                return;
            }

            // Show loading state
            const teacherSelect = document.getElementById('editJadwalGuru');
            teacherSelect.innerHTML = '<option value="">Memuat guru...</option>';

            fetch(`/admin/manual-form-data?subject_id=${subjectId}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'same-origin'
            })
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 401) {
                            throw new Error('Unauthorized - Silakan login ulang');
                        } else if (response.status === 403) {
                            throw new Error('Forbidden - Anda tidak memiliki akses');
                        } else {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                    }
                    return response.json();
                })
                .then(data => {
                    teacherSelect.innerHTML = '<option value="">Pilih Guru</option>';
                    
                    if (data.teachers && data.teachers.length > 0) {
                        data.teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.user_id;
                            option.textContent = teacher.user.full_name;
                            if (teacher.user_id == selectedTeacherId) {
                                option.selected = true;
                            }
                            teacherSelect.appendChild(option);
                        });
                    } else {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Tidak ada guru yang mengajar mata pelajaran ini';
                        option.disabled = true;
                        teacherSelect.appendChild(option);
                    }
                })
                .catch(error => {
                    // Check if it's an authentication error
                    if (error.message.includes('401') || error.message.includes('Unauthorized')) {
                        teacherSelect.innerHTML = '<option value="">Silakan login ulang</option>';
                    } else if (error.message.includes('403') || error.message.includes('Forbidden')) {
                        teacherSelect.innerHTML = '<option value="">Anda tidak memiliki akses</option>';
                    } else {
                        teacherSelect.innerHTML = '<option value="">Error memuat guru</option>';
                    }
                });
        }

        // Load dropdown data untuk edit jadwal
        function loadEditJadwalDropdowns(jadwalData) {
            // Load classes
            fetch('/admin/classes')
                .then(response => response.json())
                .then(data => {
                    const classSelect = document.getElementById('editJadwalKelas');
                    classSelect.innerHTML = '<option value="">Pilih Kelas</option>';
                    data.forEach(cls => {
                        const option = document.createElement('option');
                        option.value = cls.id;
                        option.textContent = cls.name + '-' + cls.grade;
                        if (cls.id == jadwalData.class_id) {
                            option.selected = true;
                        }
                        classSelect.appendChild(option);
                    });
                    
                    // Show/hide week type based on class grade
                    const selectedClass = data.find(cls => cls.id == jadwalData.class_id);
                    const weekTypeContainer = document.getElementById('editJadwalWeekTypeContainer');
                    if (selectedClass && selectedClass.grade == '11') {
                        weekTypeContainer.style.display = 'block';
                    } else {
                        weekTypeContainer.style.display = 'none';
                    }
                });

            // Load subjects
            fetch('/admin/subjects')
                .then(response => response.json())
                .then(data => {
                    const subjectSelect = document.getElementById('editJadwalMataPelajaran');
                    subjectSelect.innerHTML = '<option value="">Pilih Mata Pelajaran</option>';
                    data.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name;
                        if (subject.id == jadwalData.subject_id) {
                            option.selected = true;
                        }
                        subjectSelect.appendChild(option);
                    });
                });

            // Load teachers based on selected subject
            loadEditTeachersBySubject(jadwalData.subject_id, jadwalData.teacher_id);
        }

        // Function to check for schedule conflicts
        function checkEditJadwalConflict() {
            const form = document.getElementById('editJadwalForm');
            const formData = new FormData(form);
            
            const dayOfWeek = formData.get('day_of_week');
            const startTime = formData.get('start_time');
            const endTime = formData.get('end_time');
            const classId = formData.get('class_id');
            const jadwalId = formData.get('id');
            
            if (!dayOfWeek || !startTime || !endTime || !classId) {
                hideEditJadwalConflictAlert();
                return;
            }
            
            // Check for conflicts
            fetch(`/admin/manual-form-data?day=${dayOfWeek}&start_time=${startTime}&end_time=${endTime}&class_id=${classId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.conflicting_classes && data.conflicting_classes.length > 0) {
                        showEditJadwalConflictAlert(data.conflicting_classes, data.filter_info);
                    } else {
                        hideEditJadwalConflictAlert();
                    }
                })
                .catch(error => {
                    hideEditJadwalConflictAlert();
                });
        }
        
        // Show conflict alert
        function showEditJadwalConflictAlert(conflictingClasses, filterInfo) {
            const alert = document.getElementById('editJadwalConflictAlert');
            const message = document.getElementById('editJadwalConflictMessage');
            
            let conflictText = `âš ï¸ Konflik jadwal terdeteksi! `;
            if (conflictingClasses.length > 0) {
                conflictText += `Kelas yang bentrok: ${conflictingClasses.join(', ')}. `;
            }
            if (filterInfo && filterInfo.total_classes > 0) {
                conflictText += `Total ${filterInfo.total_classes} kelas, ${filterInfo.available_classes} tersedia. `;
            }
            conflictText += `Silakan pilih waktu atau kelas yang berbeda.`;
            
            message.textContent = conflictText;
            alert.style.display = 'block';
        }
        
        // Hide conflict alert
        function hideEditJadwalConflictAlert() {
            const alert = document.getElementById('editJadwalConflictAlert');
            alert.style.display = 'none';
        }

        // Event listener untuk edit jadwal
        document.addEventListener('DOMContentLoaded', function() {
            // Event listener untuk mengubah kelas
            const classSelect = document.getElementById('editJadwalKelas');
            if (classSelect) {
                classSelect.addEventListener('change', function() {
                    const selectedOption = this.options[this.selectedIndex];
                    const weekTypeContainer = document.getElementById('editJadwalWeekTypeContainer');
                    
                    if (selectedOption.textContent.includes('-11')) {
                        weekTypeContainer.style.display = 'block';
                    } else {
                        weekTypeContainer.style.display = 'none';
                    }
                });
            }

            // Event listener untuk mengubah mata pelajaran
            const subjectSelect = document.getElementById('editJadwalMataPelajaran');
            if (subjectSelect) {
                subjectSelect.addEventListener('change', function() {
                    const subjectId = this.value;
                    loadEditTeachersBySubject(subjectId);
                });
            }

            // Event listeners untuk mengecek konflik
            const conflictFields = ['editJadwalHari', 'editJadwalJamMulai', 'editJadwalJamSelesai', 'editJadwalKelas'];
            conflictFields.forEach(fieldId => {
                const field = document.getElementById(fieldId);
                if (field) {
                    field.addEventListener('change', function() {
                        // Delay untuk memastikan form sudah ter-update
                        setTimeout(checkEditJadwalConflict, 100);
                    });
                }
            });
            
            const saveEditBtn = document.getElementById('saveEditJadwalBtn');
            if (saveEditBtn) {
                saveEditBtn.addEventListener('click', function() {
                    const form = document.getElementById('editJadwalForm');
                    const formData = new FormData(form);
                    const jadwalId = formData.get('id');

                    // Disable button
                    this.disabled = true;
                    this.textContent = 'Menyimpan...';

                    fetch(`/admin/jadwal/${jadwalId}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            day_of_week: formData.get('day_of_week'),
                            start_time: formData.get('start_time'),
                            end_time: formData.get('end_time'),
                            class_id: formData.get('class_id'),
                            subject_id: formData.get('subject_id'),
                            teacher_id: formData.get('teacher_id'),
                            type: 'teori', // Default value
                            week_type: formData.get('week_type')
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showAlert('success', data.message);
                            bootstrap.Modal.getInstance(document.getElementById('editJadwalModal')).hide();
                            
                            // Refresh table
                            if (window.gridInstanceJadwal) {
                                window.gridInstanceJadwal.forceRender();
                            }
                        } else {
                            showAlert('error', data.message);
                        }
                    })
                    .catch(error => {
                        showAlert('error', 'Gagal memperbarui jadwal');
                    })
                    .finally(() => {
                        this.disabled = false;
                        this.textContent = 'Simpan Perubahan';
                    });
                });
            }
        });

        // Clean up modal instances when they are hidden
        document.addEventListener('DOMContentLoaded', function() {
            // Clean up edit term modal
            const editTermModal = document.getElementById('editTermModal');
            if (editTermModal) {
                editTermModal.addEventListener('hidden.bs.modal', function(event) {
                    // Only dispose if the event target is the modal itself
                    if (event.target === editTermModal) {
                        const instance = bootstrap.Modal.getInstance(editTermModal);
                        if (instance) {
                            instance.dispose();
                        }
                    }
                });
            }

            // Clean up delete term modal
            const deleteTermModal = document.getElementById('deleteTermModal');
            if (deleteTermModal) {
                deleteTermModal.addEventListener('hidden.bs.modal', function(event) {
                    // Only dispose if the event target is the modal itself
                    if (event.target === deleteTermModal) {
                        const instance = bootstrap.Modal.getInstance(deleteTermModal);
                        if (instance) {
                            try {
                                instance.dispose();
                            } catch (e) {
                                // Error disposing delete modal
                            }
                        }
                    }
                });
            }
        });

        // Semester Management Functions - GridJS Version
        // Add Term
        function addTerm() {
            const form = document.getElementById('addTermForm');
            const formData = new FormData(form);
            
            const data = {
                name: formData.get('name'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                is_active: formData.get('is_active') ? 1 : 0
            };

            fetch('/admin/terms', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide add modal first
                    bootstrap.Modal.getInstance(document.getElementById('addTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    form.reset();
                    // Reload terms table
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        window.gridInstanceTerms.forceRender();
                    } else {
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                if (window.gridInstanceTerms) {
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                showNotification('Gagal menambahkan semester', false);
            });
        }

        // Edit Term
        function editTerm(id) {
            fetch(`/admin/terms/${id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const term = data.data;
                        document.getElementById('edit_term_id').value = term.id;
                        document.getElementById('edit_term_name').value = term.name;
                        
                        // Convert date format for input type="date"
                        // Handle both ISO format and simple date format
                        let formattedStartDate, formattedEndDate;
                        
                        if (term.start_date.includes('T')) {
                            // ISO format: 2025-06-30T16:00:00.000000Z
                            formattedStartDate = term.start_date.split('T')[0];
                            formattedEndDate = term.end_date.split('T')[0];
                        } else {
                            // Simple format: 2025-06-30
                            formattedStartDate = term.start_date;
                            formattedEndDate = term.end_date;
                        }
                        
                        
                        document.getElementById('edit_term_start_date').value = formattedStartDate;
                        document.getElementById('edit_term_end_date').value = formattedEndDate;
                        document.getElementById('edit_term_is_active').checked = term.is_active;
                        
                        // Show modal using setTimeout to ensure DOM is ready
                        setTimeout(() => {
                            const editModalElement = document.getElementById('editTermModal');
                            if (editModalElement) {
                                // Remove any existing modal instances
                                const existingInstance = bootstrap.Modal.getInstance(editModalElement);
                                if (existingInstance) {
                                    existingInstance.dispose();
                                }
                                
                                // Create and show new modal instance
                                const modal = new bootstrap.Modal(editModalElement, {
                                    backdrop: true,
                                    keyboard: true,
                                    focus: true
                                });
                                modal.show();
                            }
                        }, 100);
                    } else {
                        showAlert('error', data.message);
                    }
                })
                .catch(error => {
                    showAlert('error', 'Gagal memuat data semester: ' + error.message);
                });
        }

        // Update Term
        function updateTerm() {
            const form = document.getElementById('editTermForm');
            const formData = new FormData(form);
            const id = formData.get('id');
            
            const data = {
                name: formData.get('name'),
                start_date: formData.get('start_date'),
                end_date: formData.get('end_date'),
                is_active: formData.get('is_active') ? 1 : 0
            };

            fetch(`/admin/terms/${id}`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hide edit modal first
                    bootstrap.Modal.getInstance(document.getElementById('editTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    // Reload terms table
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        window.gridInstanceTerms.forceRender();
                    } else {
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                if (window.gridInstanceTerms) {
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                showNotification('Gagal memperbarui semester', false);
            });
        }

        // Delete Term - Safe Bootstrap approach
        function deleteTerm(id) {
            document.getElementById('deleteTermId').value = id;
            
            try {
                const modalElement = document.getElementById('deleteTermModal');
                if (!modalElement) {
                    return;
                }
                
                // Dispose any existing instance first
                const existingInstance = bootstrap.Modal.getInstance(modalElement);
                if (existingInstance) {
                    try {
                        existingInstance.dispose();
                    } catch (e) {
                        // Error disposing existing modal
                    }
                }
                
                // Create new modal instance
                const modal = new bootstrap.Modal(modalElement, {
                    backdrop: true,
                    keyboard: true,
                    focus: true
                });
                
                modal.show();
            } catch (error) {
                // Fallback: try to show modal using data-bs-toggle
                const modalElement = document.getElementById('deleteTermModal');
                if (modalElement) {
                    modalElement.setAttribute('data-bs-toggle', 'modal');
                    modalElement.setAttribute('data-bs-target', '#deleteTermModal');
                    modalElement.click();
                }
            }
        }

        // Confirm Delete Term
        function confirmDeleteTerm() {
            const id = document.getElementById('deleteTermId').value;
            
            fetch(`/admin/terms/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Hide delete modal first
                    bootstrap.Modal.getInstance(document.getElementById('deleteTermModal')).hide();
                    
                    // Show success notification
                    showNotification(data.message, true);
                    
                    // Reload terms table
                    // Try immediate reload first
                    if (window.gridInstanceTerms) {
                        window.gridInstanceTerms.forceRender();
                    } else {
                        // Ensure Info Akademik tab is active first
                        const infoAkademikTab = document.querySelector('a[href="#mapel"]');
                        if (infoAkademikTab) {
                            const tab = new bootstrap.Tab(infoAkademikTab);
                            tab.show();
                            
                            // Wait for tab to be active, then try again
                            setTimeout(() => {
                                if (window.gridInstanceTerms) {
                                    window.gridInstanceTerms.forceRender();
                                } else {
                                    location.reload();
                                }
                            }, 300);
                        } else {
                            location.reload();
                        }
                    }
                } else {
                    showNotification(data.message, false);
                }
            })
            .catch(error => {
                showNotification('Gagal menghapus semester', false);
            });
        }

        // Delete All Terms
        function deleteAllTerms() {
            fetch('/admin/terms/delete-all', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', data.message);
                    bootstrap.Modal.getInstance(document.getElementById('deleteAllTermsModal')).hide();
                    // Reload terms table
                    if (window.tabelJadwalInstance && window.tabelJadwalInstance.renderTermsTable) {
                        window.tabelJadwalInstance.renderTermsTable();
                    }
                } else {
                    showAlert('error', data.message);
                }
            })
            .catch(error => {
                showAlert('error', 'Gagal menghapus semua semester');
            });
        }

    // Make functions globally available
    window.loadTermsData = loadTermsData;
    window.reloadKelasXTable = reloadKelasXTable;
    window.reloadKelasXITable = reloadKelasXITable;
    window.loadClassesForKelasX = loadClassesForKelasX;
    window.exportJadwalX = exportJadwalX;
    window.exportJadwalXI = exportJadwalXI;
    window.showAlert = showAlert;
    window.addTerm = addTerm;
    window.editTerm = editTerm;
    window.updateTerm = updateTerm;
    window.deleteTerm = deleteTerm;
    window.confirmDeleteTerm = confirmDeleteTerm;
    window.deleteAllTerms = deleteAllTerms;
})();

