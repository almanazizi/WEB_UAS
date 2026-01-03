<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #000000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .header {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        Laporan Data Pengunjung Laboratorium<br>
        Tanggal Unduh: {{ date('d F Y') }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 50px;">No</th>
                <th style="width: 120px;">Tanggal</th>
                <th style="width: 80px;">Jam</th>
                <th style="width: 100px;">NIM</th>
                <th style="width: 200px;">Nama</th>
                <th style="width: 200px;">Laboratorium</th>
                <th style="width: 300px;">Keperluan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($guests as $index => $guest)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $guest->check_in_time->format('d/m/Y') }}</td>
                <td>{{ $guest->check_in_time->format('H:i') }}</td>
                <td style="mso-number-format:'\@'">{{ $guest->nim }}</td>
                <td>{{ $guest->nama }}</td>
                <td>{{ $guest->lab->name }}</td>
                <td>{{ $guest->purpose }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
