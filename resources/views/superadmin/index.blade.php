@extends('admin.layouts.main')

@section('content')

    <style>
        .modal-dialog {
            max-width: 520px;
        }

        .backup-modal-content {
            border-radius: 14px;
            overflow: hidden;
        }

        .backup-modal-content .modal-header {
            padding: 22px 24px;
            border-bottom: 1px solid #e9ecef;
        }

        .backup-modal-content .modal-title {
            font-size: 20px;
            font-weight: 700;
            color: #344767;
        }

        .backup-modal-content .modal-body {
            padding: 28px;
        }

        .backup-progress-wrapper {
            height: 26px;
            border-radius: 30px;
            background: #edf2f7;
            overflow: hidden;
            position: relative;
        }

        .backup-progress-bar {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            color: #000;
            white-space: nowrap;
            transition: width 0.4s ease;
        }

        .backup-progress-text {
            margin-top: 20px;
            font-size: 16px;
            color: #67748e;
            text-align: left;
        }

        .progress-bar-striped {
            background-image: linear-gradient(
                45deg,
                rgba(255, 255, 255, .15) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, .15) 50%,
                rgba(255, 255, 255, .15) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
        }

        .progress-bar-animated {
            animation: progress-bar-stripes 1s linear infinite;
        }

        @keyframes progress-bar-stripes {
            from {
                background-position: 1rem 0;
            }

            to {
                background-position: 0 0;
            }
        }

        @media (max-width: 576px) {
            .backup-modal-content .modal-body,
            .backup-modal-content .modal-header {
                padding: 18px;
            }

            .backup-progress-text {
                font-size: 14px;
            }
        }
    </style>

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

        @if (Auth::user()->type == 'superadmin')
            <div class="backup-panel mb-3">
                <a href="/refresh/all" class="btn btn-primary">Refresh All</a>
                <a href="/cache-clear" class="btn btn-primary">Cache Clear</a>
                <a href="/pay-noti" class="btn btn-primary">Client Payment Notification</a>

                <hr>

                <a href="javascript:void(0)" class="btn btn-success" id="openBackupSelectModal">
                    Take Backup
                </a>

                <a href="javascript:void(0)" class="btn btn-dark" id="openDriveUploadModal">
                    Upload To Drive
                </a>

                <a href="javascript:void(0)" class="btn btn-warning" id="openDriveRestoreModal">
                    Restore From Drive
                </a>

                <a href="javascript:void(0)" class="btn btn-danger" id="openDeleteSelectModal">
                    Delete Backups
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Auto Backup Status</h6>
                            <p class="text-sm mb-0">
                                <span class="badge bg-gradient-@if(($nightlyBackupStatus['status'] ?? 'idle') === 'completed')success@elseif(($nightlyBackupStatus['status'] ?? 'idle') === 'failed')danger@elseif(($nightlyBackupStatus['status'] ?? 'idle') === 'running')warning@else secondary @endif">
                                    {{ ucfirst($nightlyBackupStatus['status'] ?? 'idle') }}
                                </span>
                            </p>
                        </div>
                        <div class="card-body pt-3">
                            <p class="text-sm mb-2">
                                <strong>Last auto backup time:</strong><br>
                                {{ !empty($nightlyBackupStatus['time']) ? \Carbon\Carbon::parse($nightlyBackupStatus['time'])->timezone('Asia/Dhaka')->format('d M Y, h:i A') : 'Not available yet' }}
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Backup status:</strong><br>
                                <span class="badge bg-gradient-@if(($nightlyBackupStatus['backup_status'] ?? 'idle') === 'completed')success@elseif(($nightlyBackupStatus['backup_status'] ?? 'idle') === 'failed')danger@elseif(($nightlyBackupStatus['backup_status'] ?? 'idle') === 'running')warning@else secondary @endif">
                                    {{ ucfirst($nightlyBackupStatus['backup_status'] ?? 'idle') }}
                                </span>
                                <br>
                                {{ $nightlyBackupStatus['backup_message'] ?? 'No backup status available.' }}
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Drive upload status:</strong><br>
                                <span class="badge bg-gradient-@if(($nightlyBackupStatus['upload_status'] ?? 'idle') === 'completed')success@elseif(($nightlyBackupStatus['upload_status'] ?? 'idle') === 'failed')danger@elseif(($nightlyBackupStatus['upload_status'] ?? 'idle') === 'running')warning@elseif(($nightlyBackupStatus['upload_status'] ?? 'idle') === 'pending')info@else secondary @endif">
                                    {{ ucfirst($nightlyBackupStatus['upload_status'] ?? 'idle') }}
                                </span>
                                <br>
                                {{ $nightlyBackupStatus['upload_message'] ?? 'No upload status available.' }}
                            </p>
                            <p class="text-sm mb-2">
                                <strong>Last auto backup status:</strong><br>
                                {{ $nightlyBackupStatus['message'] ?? 'No status available.' }}
                            </p>
                            <p class="text-sm mb-0">
                                <strong>Next scheduled run:</strong><br>
                                {{ isset($nextNightlyBackupRun) ? $nextNightlyBackupRun->format('d M Y, h:i A') : 'Every day at 11:00 PM' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Progress Modal --}}
        <div class="modal fade" id="backupProgressModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content backup-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="backupModalTitle">Backup in Progress</h5>
                    </div>
                    <div class="modal-body">
                        <div class="backup-progress-wrapper">
                            <div id="backupProgressBar"
                                class="backup-progress-bar progress-bar-striped progress-bar-animated bg-success"
                                style="width:0%">
                                <span id="backupProgressPercent">0%</span>
                            </div>
                        </div>

                        <p id="backupProgressText" class="backup-progress-text mb-0">
                            Preparing...
                        </p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Drive Upload Selection Modal --}}
        <div class="modal fade" id="driveUploadSelectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content backup-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Select Backup To Upload</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input drive-upload-option" type="checkbox" value="database"
                                id="uploadDatabase">
                            <label class="form-check-label" for="uploadDatabase">
                                Database Backup
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input drive-upload-option" type="checkbox" value="code"
                                id="uploadCode">
                            <label class="form-check-label" for="uploadCode">
                                Code Backup
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input drive-upload-option" type="checkbox" value="public"
                                id="uploadPublic">
                            <label class="form-check-label" for="uploadPublic">
                                Public Backup
                            </label>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-dark" id="startSelectedDriveUpload">
                                Start Upload
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Drive Restore Selection Modal --}}
        <div class="modal fade" id="driveRestoreSelectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content backup-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Restore Backup From Drive</h5>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-warning py-2 px-3 small">
                            This will restore the selected backup date from Google Drive and overwrite current data/files.
                        </div>

                        <div class="mb-3">
                            <label for="driveRestoreDate" class="form-label fw-bold">Backup Date</label>
                            <select class="form-control" id="driveRestoreDate">
                                <option value="">Loading available backup dates...</option>
                            </select>
                            <div id="driveRestoreDateHelp" class="small text-muted mt-1"></div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input drive-restore-option" type="checkbox" value="database"
                                id="restoreDatabase">
                            <label class="form-check-label" for="restoreDatabase">
                                Database Backup
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input drive-restore-option" type="checkbox" value="code"
                                id="restoreCode">
                            <label class="form-check-label" for="restoreCode">
                                Code Backup
                            </label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input drive-restore-option" type="checkbox" value="public"
                                id="restorePublic">
                            <label class="form-check-label" for="restorePublic">
                                Public Backup
                            </label>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-warning" id="startSelectedDriveRestore">
                                Start Restore
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Backup Selection Modal --}}
        <div class="modal fade" id="backupSelectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content backup-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Select Backup Option</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input backup-option" type="checkbox" value="database"
                                id="backupDatabase">
                            <label class="form-check-label" for="backupDatabase">Database Backup</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input backup-option" type="checkbox" value="code"
                                id="backupCode">
                            <label class="form-check-label" for="backupCode">Code Backup</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input backup-option" type="checkbox" value="public"
                                id="backupPublic">
                            <label class="form-check-label" for="backupPublic">Public Backup</label>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-success" id="startSelectedBackup">
                                Start Backup
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Delete Selection Modal --}}
        <div class="modal fade" id="deleteSelectModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content backup-modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Delete Backup Options</h5>
                    </div>
                    <div class="modal-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input delete-option" type="checkbox" value="database"
                                id="deleteDatabase">
                            <label class="form-check-label" for="deleteDatabase">Database Backup</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input delete-option" type="checkbox" value="code"
                                id="deleteCode">
                            <label class="form-check-label" for="deleteCode">Code Backup</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input delete-option" type="checkbox" value="public"
                                id="deletePublic">
                            <label class="form-check-label" for="deletePublic">Public Backup</label>
                        </div>

                        <hr>

                        <div class="mb-2 fw-bold">Delete backups older than:</div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delete_days" id="delete7" value="7" checked>
                            <label class="form-check-label" for="delete7">7 Days</label>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="delete_days" id="delete15" value="15">
                            <label class="form-check-label" for="delete15">15 Days</label>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="delete_days" id="delete30" value="30">
                            <label class="form-check-label" for="delete30">30 Days</label>
                        </div>

                        <div class="mt-4 d-flex gap-2">
                            <button type="button" class="btn btn-danger" id="startSelectedDelete">
                                Delete Now
                            </button>
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header pb-0">
                            <h6>Orders overview</h6>

                            <p class="text-sm">
                                <i class="fa fa-arrow-up text-success"></i>
                                <span class="font-weight-bold">24%</span>
                                this month
                            </p>
                        </div>

                        <div class="card-body p-3">
                            <div class="timeline timeline-one-side">
                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-success text-gradient">notifications</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            $2400, Design changes
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            22 DEC 7:20 PM
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-danger text-gradient">code</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            New order #1832412
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            21 DEC 11 PM
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-info text-gradient">shopping_cart</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            Server payments for April
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            21 DEC 9:34 PM
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-warning text-gradient">credit_card</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            New card added for order #4395133
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            20 DEC 2:20 AM
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-block mb-3">
                                    <span class="timeline-step">
                                        <i class="material-icons text-primary text-gradient">key</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            Unlock packages for development
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            18 DEC 4:54 AM
                                        </p>
                                    </div>
                                </div>

                                <div class="timeline-block">
                                    <span class="timeline-step">
                                        <i class="material-icons text-dark text-gradient">payments</i>
                                    </span>

                                    <div class="timeline-content">
                                        <h6 class="text-dark text-sm font-weight-bold mb-0">
                                            New order #9583120
                                        </h6>

                                        <p class="text-secondary font-weight-bold text-xs mt-1 mb-0">
                                            17 DEC
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @include('superadmin.share.paymentNotification')
            </div>
        </div>
    </main>

@endsection

@section('js')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const progressModalEl = document.getElementById('backupProgressModal');
            const driveSelectModalEl = document.getElementById('driveUploadSelectModal');
            const driveRestoreSelectModalEl = document.getElementById('driveRestoreSelectModal');
            const backupSelectModalEl = document.getElementById('backupSelectModal');
            const deleteSelectModalEl = document.getElementById('deleteSelectModal');

            const progressModal = progressModalEl ? new bootstrap.Modal(progressModalEl) : null;
            const driveSelectModal = driveSelectModalEl ? new bootstrap.Modal(driveSelectModalEl) : null;
            const driveRestoreSelectModal = driveRestoreSelectModalEl ? new bootstrap.Modal(driveRestoreSelectModalEl) : null;
            const backupSelectModal = backupSelectModalEl ? new bootstrap.Modal(backupSelectModalEl) : null;
            const deleteSelectModal = deleteSelectModalEl ? new bootstrap.Modal(deleteSelectModalEl) : null;

            const modalTitle = document.getElementById('backupModalTitle');
            const progressBar = document.getElementById('backupProgressBar');
            const progressPercent = document.getElementById('backupProgressPercent');
            const progressText = document.getElementById('backupProgressText');
            const driveRestoreDate = document.getElementById('driveRestoreDate');
            const driveRestoreDateHelp = document.getElementById('driveRestoreDateHelp');

            function resetProgressUI(title, startText = 'Starting...') {
                if (!progressModal) return;

                modalTitle.innerText = title;
                progressBar.classList.remove('bg-danger');
                progressBar.classList.add('bg-success', 'progress-bar-animated');
                progressBar.style.width = '5%';
                progressPercent.innerText = '5%';
                progressText.innerText = startText;
                progressModal.show();
            }

            function pollBackupStatus() {
                const interval = setInterval(function () {
                    fetch('{{ route('backup.status') }}', {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            const progress = data.progress ?? 0;
                            const message = data.message ?? 'Processing...';
                            const status = data.status ?? 'running';

                            progressBar.style.width = progress + '%';
                            progressPercent.innerText = progress + '%';
                            progressText.innerText = message;

                            if (status === 'completed') {
                                progressBar.classList.remove('progress-bar-animated');
                                clearInterval(interval);

                                setTimeout(function () {
                                    location.reload();
                                }, 1200);
                            }

                            if (status === 'failed') {
                                progressBar.classList.remove('progress-bar-animated', 'bg-success');
                                progressBar.classList.add('bg-danger');
                                clearInterval(interval);

                                setTimeout(function () {
                                    location.reload();
                                }, 1500);
                            }
                        })
                        .catch(function (error) {
                            console.error('Status polling failed:', error);
                        });
                }, 1500);
            }

            function populateDriveRestoreDates(items) {
                if (!driveRestoreDate) return;

                if (!Array.isArray(items) || items.length === 0) {
                    driveRestoreDate.innerHTML = '<option value="">No backup date found</option>';
                    if (driveRestoreDateHelp) {
                        driveRestoreDateHelp.innerText = '';
                    }
                    return;
                }

                driveRestoreDate.innerHTML = '<option value="">Select backup date</option>' + items.map(function(item) {
                    const types = Array.isArray(item.types) ? item.types.join(', ') : '';
                    return '<option value="' + item.date + '" data-types="' + types + '">' + item.date + '</option>';
                }).join('');

                if (driveRestoreDateHelp) {
                    driveRestoreDateHelp.innerText = '';
                }
            }

            function loadDriveRestoreDates() {
                if (!driveRestoreDate) return;

                driveRestoreDate.innerHTML = '<option value="">Loading available backup dates...</option>';
                if (driveRestoreDateHelp) {
                    driveRestoreDateHelp.innerText = '';
                }

                fetch('{{ route('backup.restore.drive.options') }}', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        populateDriveRestoreDates(data.items || []);
                    })
                    .catch(function () {
                        driveRestoreDate.innerHTML = '<option value="">Failed to load backup dates</option>';
                    });
            }

            if (driveRestoreDate) {
                driveRestoreDate.addEventListener('change', function () {
                    const selectedOption = driveRestoreDate.options[driveRestoreDate.selectedIndex];
                    const types = selectedOption ? selectedOption.getAttribute('data-types') : '';

                    if (driveRestoreDateHelp) {
                        driveRestoreDateHelp.innerText = types ? ('Available: ' + types) : '';
                    }
                });
            }

            const openBackupSelectModalBtn = document.getElementById('openBackupSelectModal');
            const startSelectedBackupBtn = document.getElementById('startSelectedBackup');

            if (openBackupSelectModalBtn) {
                openBackupSelectModalBtn.addEventListener('click', function () {
                    if (backupSelectModal) {
                        backupSelectModal.show();
                    }
                });
            }

            if (startSelectedBackupBtn) {
                startSelectedBackupBtn.addEventListener('click', function () {
                    const checked = Array.from(document.querySelectorAll('.backup-option:checked'))
                        .map(el => el.value);

                    if (checked.length === 0) {
                        alert('Please select at least one backup type.');
                        return;
                    }

                    if (backupSelectModal) {
                        backupSelectModal.hide();
                    }

                    resetProgressUI('Take Backup', 'Backup queued...');
                    pollBackupStatus();

                    fetch('{{ route('backup.start.selected') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            types: checked
                        })
                    }).catch(function () {
                        progressBar.classList.remove('progress-bar-animated', 'bg-success');
                        progressBar.classList.add('bg-danger');
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressText.innerText = 'Backup request failed to start.';
                    });
                });
            }

            const openDriveUploadModalBtn = document.getElementById('openDriveUploadModal');
            const startSelectedDriveUploadBtn = document.getElementById('startSelectedDriveUpload');
            const openDriveRestoreModalBtn = document.getElementById('openDriveRestoreModal');
            const startSelectedDriveRestoreBtn = document.getElementById('startSelectedDriveRestore');

            if (openDriveUploadModalBtn) {
                openDriveUploadModalBtn.addEventListener('click', function () {
                    if (driveSelectModal) {
                        driveSelectModal.show();
                    }
                });
            }

            if (startSelectedDriveUploadBtn) {
                startSelectedDriveUploadBtn.addEventListener('click', function () {
                    const checked = Array.from(document.querySelectorAll('.drive-upload-option:checked'))
                        .map(el => el.value);

                    if (checked.length === 0) {
                        alert('Please select at least one backup type.');
                        return;
                    }

                    if (driveSelectModal) {
                        driveSelectModal.hide();
                    }

                    resetProgressUI('Upload To Drive', 'Starting upload...');
                    pollBackupStatus();

                    fetch('{{ route('backup.upload.selected.drive') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            types: checked
                        })
                    }).catch(function () {
                        progressBar.classList.remove('progress-bar-animated', 'bg-success');
                        progressBar.classList.add('bg-danger');
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressText.innerText = 'Upload request failed to start.';
                    });
                });
            }

            if (openDriveRestoreModalBtn) {
                openDriveRestoreModalBtn.addEventListener('click', function () {
                    loadDriveRestoreDates();
                    if (driveRestoreSelectModal) {
                        driveRestoreSelectModal.show();
                    }
                });
            }

            if (startSelectedDriveRestoreBtn) {
                startSelectedDriveRestoreBtn.addEventListener('click', function () {
                    const checked = Array.from(document.querySelectorAll('.drive-restore-option:checked'))
                        .map(el => el.value);

                    if (checked.length === 0) {
                        alert('Please select at least one backup type.');
                        return;
                    }

                    const selectedDate = driveRestoreDate ? driveRestoreDate.value : '';

                    if (!selectedDate) {
                        alert('Please select a backup date.');
                        return;
                    }

                    if (!confirm('Restore selected backup(s) from ' + selectedDate + ' now? This will overwrite current data/files.')) {
                        return;
                    }

                    if (driveRestoreSelectModal) {
                        driveRestoreSelectModal.hide();
                    }

                    resetProgressUI('Restore From Drive', 'Starting restore...');
                    pollBackupStatus();

                    fetch('{{ route('backup.restore.selected.drive') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            types: checked,
                            backup_date: selectedDate
                        })
                    }).catch(function () {
                        progressBar.classList.remove('progress-bar-animated', 'bg-success');
                        progressBar.classList.add('bg-danger');
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressText.innerText = 'Restore request failed to start.';
                    });
                });
            }

            const openDeleteSelectModalBtn = document.getElementById('openDeleteSelectModal');
            const startSelectedDeleteBtn = document.getElementById('startSelectedDelete');

            if (openDeleteSelectModalBtn) {
                openDeleteSelectModalBtn.addEventListener('click', function () {
                    if (deleteSelectModal) {
                        deleteSelectModal.show();
                    }
                });
            }

            if (startSelectedDeleteBtn) {
                startSelectedDeleteBtn.addEventListener('click', function () {
                    const checked = Array.from(document.querySelectorAll('.delete-option:checked'))
                        .map(el => el.value);

                    if (checked.length === 0) {
                        alert('Please select at least one backup type.');
                        return;
                    }

                    const days = document.querySelector('input[name="delete_days"]:checked')?.value || '7';

                    if (!confirm('Delete selected backups older than ' + days + ' days now?')) {
                        return;
                    }

                    if (deleteSelectModal) {
                        deleteSelectModal.hide();
                    }

                    resetProgressUI('Delete Backups', 'Deleting selected backups...');
                    pollBackupStatus();

                    fetch('{{ route('backup.delete.selected') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            types: checked,
                            days: days
                        })
                    }).catch(function () {
                        progressBar.classList.remove('progress-bar-animated', 'bg-success');
                        progressBar.classList.add('bg-danger');
                        progressBar.style.width = '100%';
                        progressPercent.innerText = '100%';
                        progressText.innerText = 'Delete request failed to start.';
                    });
                });
            }
        });
    </script>

    <script src="{{ asset('admin/assets/js/plugins/chartjs.min.js') }}"></script>

    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["M", "T", "W", "T", "F", "S", "S"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "rgba(255,255,255,.8)",
                    data: [50, 20, 10, 22, 50, 10, 40],
                    maxBarThickness: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                }
            }
        });
    </script>

@endsection
