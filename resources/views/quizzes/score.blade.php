@extends('layouts.app')

@section('content')
<div class="container">
    <h1>My Scores</h1>

    <table class="table">
        <thead>
            <tr>
                <th>Quiz</th>
                <th>Score</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse($attempts as $attempt)
                <tr>
                    <td>{{ $attempt->quiz->title ?? 'Unknown Quiz' }}</td>
                    <td>{{ $attempt->score }}</td>
                    <td>{{ $attempt->created_at }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">No scores found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection