@extends('layouts.app')

@section('content')
<div class="page-header">
    <h1>التقويم</h1>
    <p>جميع جلسات مكتبك القضائية</p>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="section-card">
                <div class="section-header">
                    <h3 class="section-title">
                        <i class="fas fa-calendar-days ml-2" style="color:#c9a227"></i>
                        جدول الجلسات
                    </h3>
                </div>

                @if($sessions->isEmpty())
                    <div class="empty-state">
                        <i class="fas fa-calendar"></i>
                        <p>لا توجد جلسات مسجلة</p>
                    </div>
                @else
                    <div class="sessions-list">
                        @foreach($sessions->groupBy(function($session) { return $session->session_date->format('Y-m-d'); }) as $date => $dateSessions)
                            <div class="date-group">
                                <h4 class="date-header">
                                    {{ \Carbon\Carbon::parse($date)->translatedFormat('l، j F Y') }}
                                </h4>
                                <div class="sessions-group">
                                    @foreach($dateSessions as $session)
                                        <div class="session-card">
                                            <div class="session-time">
                                                <i class="fas fa-clock"></i>
                                                {{ $session->session_time->format('H:i') }}
                                            </div>
                                            <div class="session-info">
                                                <h5>{{ $session->case->title ?? 'قضية' }}</h5>
                                                <p class="session-court">
                                                    <i class="fas fa-building"></i>
                                                    {{ $session->court_name }}
                                                </p>
                                                <p class="session-status">
                                                    <span class="badge badge-{{ $session->status === 'completed' ? 'success' : ($session->status === 'cancelled' ? 'danger' : 'info') }}">
                                                        {{ $session->status }}
                                                    </span>
                                                </p>
                                            </div>
                                            <div class="session-actions">
                                                <a href="{{ route('cases.show', $session->case_id) }}" class="btn-link">
                                                    عرض القضية
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.date-group {
    margin-bottom: 2rem;
}

.date-header {
    color: #c9a227;
    font-weight: 600;
    margin-bottom: 1rem;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #c9a227;
}

.sessions-group {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.session-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    padding: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    transition: box-shadow 0.2s ease;
}

.session-card:hover {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.session-time {
    font-weight: 600;
    color: #c9a227;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.session-info h5 {
    margin: 0;
    font-size: 1rem;
    color: #333;
}

.session-court {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.session-status {
    margin: 0;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 500;
}

.badge-success {
    background-color: #d4edda;
    color: #155724;
}

.badge-danger {
    background-color: #f8d7da;
    color: #721c24;
}

.badge-info {
    background-color: #d1ecf1;
    color: #0c5460;
}

.session-actions {
    margin-top: 0.5rem;
}

.btn-link {
    color: #c9a227;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.btn-link:hover {
    color: #9d7d1f;
    text-decoration: underline;
}

.empty-state {
    text-align: center;
    padding: 3rem;
    color: #999;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #ddd;
}
</style>
@endsection
