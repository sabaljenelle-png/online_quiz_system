<h1>Scores Report</h1>
<table width="100%" border="1" cellspacing="0" cellpadding="5">
<tr><th>Student</th><th>Quiz</th><th>Question</th><th>Correct</th><th>Points</th></tr>
@foreach($scores as $score)
<tr>
<td>{{ $score->attempt?->student?->name }}</td>
<td>{{ $score->attempt?->quiz?->title }}</td>
<td>{{ $score->question?->question_text }}</td>
<td>{{ $score->is_correct ? 'Yes' : 'No' }}</td>
<td>{{ $score->points_earned }}</td>
</tr>
@endforeach
</table>
