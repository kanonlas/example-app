<!-- conflicting_emotions.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Conflicting Emotions</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <h1>Diary Entries with Conflicting Emotions</h1>

    @if ($entries->isEmpty())
        <p>No diary entries found with conflicting emotions.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Date</th>
                    <th>Content</th>
                    <th>Emotions</th>
                    <th>Intensity</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($entries as $entry)
                    <tr>
                        <td>{{ $entry->id }}</td>
                        <td>{{ $entry->date }}</td>
                        <td>{{ $entry->content }}</td>
                        <td>{{ $entry->name }}</td>
                        <td>{{ $entry->intensity }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>
