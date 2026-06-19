<h1>All Quiz Results</h1>
@foreach($quizzes as $quiz)
    <h2>{{ $quiz->title }}</h2>
    <table width="100%" border="1" cellspacing="0" cellpadding="5">
        <tr><th>Student</th><th>Score</th><th>Status</th></tr>
        @foreach($quiz->attempts as $attempt)
            <tr><td>{{ $attempt->student?->name }}</td><td>{{ $attempt->score }}%</td><td>{{ $attempt->status }}</td></tr>
        @endforeach
    </table>
@endforeach
