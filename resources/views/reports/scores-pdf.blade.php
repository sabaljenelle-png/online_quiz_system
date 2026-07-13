<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Results</title>

    <style>
        body{
            font-family: DejaVu Sans, sans-serif;
            font-size:12px;
            margin:20px;
        }

        h2{
            text-align:center;
            margin-bottom:5px;
        }

        p{
            text-align:center;
            margin-bottom:20px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        th,td{
            border:1px solid #000;
            padding:8px;
        }

        th{
            background:#eeeeee;
        }

        tr:nth-child(even){
            background:#f9f9f9;
        }
    </style>

</head>

<body>

<h2>Quiz Results</h2>

<p><strong>{{ $quiz->title }}</strong></p>

<table>

<thead>

<tr>

<th>Student</th>

<th>Email</th>

<th>Score</th>

<th>Passed</th>

<th>Status</th>

<th>Attempt</th>

<th>Passing Score</th>

</tr>

</thead>

<tbody>

@forelse($quiz->attempts as $attempt)

@php
    $attemptNumber = \App\Models\Attempt::where('student_id',$attempt->student_id)
        ->where('quiz_id',$attempt->quiz_id)
        ->where('id','<=',$attempt->id)
        ->count();
@endphp

<tr>

<td>{{ $attempt->student?->name }}</td>

<td>{{ $attempt->student?->email }}</td>

<td>{{ number_format($attempt->score,2) }}%</td>

<td>{{ $attempt->is_passed ? 'Yes' : 'No' }}</td>

<td>{{ ucfirst(str_replace('_',' ',$attempt->status)) }}</td>

<td>
Attempt #{{ $attemptNumber }}
{{ $attemptNumber > 1 ? '(Retake)' : '(First)' }}
</td>

<td>{{ $quiz->passing_score }}%</td>

</tr>

@empty

<tr>

<td colspan="7" align="center">

No attempts yet.

</td>

</tr>

@endforelse

</tbody>

</table>

</body>
</html>
