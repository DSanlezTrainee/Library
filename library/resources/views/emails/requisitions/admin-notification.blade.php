<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>New Book Requisition</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background-color: #3b82f6;
            color: white;
            padding: 10px 20px;
            text-align: center;
        }

        .content {
            padding: 20px;
            border: 1px solid #ddd;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8em;
            color: #666;
        }

        .book-cover {
            max-width: 150px;
            margin: 0 auto;
            display: block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }

        table td:first-child {
            font-weight: bold;
            width: 40%;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>New Book Requisition</h1>
        </div>
        <div class="content">
            <p>A new book requisition has been created with the following details:</p>

            <table>
                <tr>
                    <td>Requisition Number:</td>
                    <td>#{{ $requisition->sequential_number }}</td>
                </tr>
                <tr>
                    <td>Book:</td>
                    <td>{{ $requisition->book->name }}</td>
                </tr>
                <tr>
                    <td>User:</td>
                    <td>{{ $requisition->user->name }} ({{ $requisition->user->email }})</td>
                </tr>
                <tr>
                    <td>Start Date:</td>
                    <td>{{ \Carbon\Carbon::parse($requisition->start_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <td>Expected Return Date:</td>
                    <td>{{ \Carbon\Carbon::parse($requisition->end_date)->format('d/m/Y') }}</td>
                </tr>
            </table>

            @if($coverImagePath)
            <div style="text-align: center; margin-top: 20px;">
                <p>Book Cover:</p>
                <img src="{{ $message->embed($coverImagePath) }}" alt="Book Cover" class="book-cover">
            </div>
            @endif

            <p style="margin-top: 20px;">
                You can view this requisition in the <a href="{{ route('requisitions.show', $requisition) }}">library
                    management system</a>.
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Library Management System. All rights reserved.</p>
        </div>
    </div>
</body>

</html>