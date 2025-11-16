@props([
    'showDatabaseStats' => false
])

<div class="card">
    <div class="card-header">
        <h4 class="card-title mb-0 d-flex align-items-center">
            <i class="bx bx-info-circle me-2"></i>
            Informasi Sistem
        </h4>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Versi Aplikasi:</strong></td>
                            <td>{{ config('app.version', '1.0.0') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Versi PHP:</strong></td>
                            <td>{{ phpversion() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Framework:</strong></td>
                            <td>Laravel {{ app()->version() }}</td>
                        </tr>
                        <tr>
                            <td><strong>Database:</strong></td>
                            <td>{{ config('database.default') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Environment:</strong></td>
                            <td>{{ app()->environment() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td><strong>Server:</strong></td>
                            <td>{{ $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' }}</td>
                        </tr>
                        <tr>
                            <td><strong>OS:</strong></td>
                            <td>{{ PHP_OS }}</td>
                        </tr>
                        <tr>
                            <td><strong>Memory Limit:</strong></td>
                            <td>{{ ini_get('memory_limit') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Max Execution Time:</strong></td>
                            <td>{{ ini_get('max_execution_time') }}s</td>
                        </tr>
                        <tr>
                            <td><strong>Upload Max Size:</strong></td>
                            <td>{{ ini_get('upload_max_filesize') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        @if($showDatabaseStats)
            @include('components.pengaturan.statistik-database-card')
        @endif
    </div>
</div>

